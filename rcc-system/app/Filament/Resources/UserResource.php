<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\UserPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Gerenciamento';

    protected static ?string $navigationLabel = 'Usuários';

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        $groupField = Schema::hasTable('groups')
            ? Forms\Components\CheckboxList::make('groups')
                ->label('Grupos de Oração')
                ->relationship('groups', 'name')
                ->columns(2)
            : Forms\Components\TextInput::make('group_name')
                ->label('Grupos de Oração')
                ->disabled()
                ->placeholder('Tabela de grupos ausente');

        $ministriesField = Schema::hasTable('ministries')
            ? Forms\Components\Select::make('ministries')
                ->label('Ministérios')
                ->relationship('ministries', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->visible(fn (\Filament\Forms\Get $get): bool => (bool) $get('is_servo'))
            : Forms\Components\TextInput::make('ministries_info')
                ->label('Ministérios')
                ->disabled()
                ->placeholder('Tabela de ministérios ausente');

        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome Completo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Data de Nascimento')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Forms\Components\TextInput::make('cpf')
                            ->label('CPF')
                            ->maxLength(14)
                            ->regex('/^\d{11}$/')
                            ->helperText('Digite somente números (11 dígitos).'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Endereço')
                    ->schema([
                        Forms\Components\TextInput::make('cep')
                            ->label('CEP')
                            ->maxLength(9),
                        Forms\Components\TextInput::make('address')
                            ->label('Endereço')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('number')
                            ->label('Número')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('complement')
                            ->label('Complemento')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('district')
                            ->label('Bairro')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->label('Cidade')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->label('Estado')
                            ->maxLength(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Select::make('gender')
                            ->label('Gênero')
                            ->options([
                                'male' => 'Masculino',
                                'female' => 'Feminino',
                            ])
                            ->required(),
                        Forms\Components\Select::make('role')
                            ->label('Nível de Acesso')
                            ->options([
                                'fiel' => 'Fiel',
                                'servo' => 'Servo',
                                'admin' => 'Administrador',
                            ])
                            ->default('fiel')
                            ->required(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('can_access_admin')
                                    ->label('Pode acessar painel /admin')
                                    ->inline(false),
                                Forms\Components\Toggle::make('is_master_admin')
                                    ->label('Administrador master')
                                    ->inline(false)
                                    ->helperText('Apenas o administrador master pode acessar Configurações'),
                            ]),
                        $groupField,
                        Forms\Components\Placeholder::make('group_id_sync_info')
                            ->label('Grupo principal')
                            ->content('O primeiro grupo selecionado será usado como grupo principal em filtros e integrações legadas.')
                            ->hintIcon('heroicon-o-information-circle'),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_servo')
                                    ->label('É Servo?')
                                    ->inline(false),
                                Forms\Components\Toggle::make('is_coordinator')
                                    ->label('É Coordenador?')
                                    ->inline(false)
                                    ->visible(fn () => auth()->user()?->isMasterAdmin() || auth()->user()?->role === 'admin'),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'active' => 'Ativo',
                                        'inactive' => 'Inativo',
                                        'blocked' => 'Bloqueado',
                                    ])
                                    ->default('active')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('profile_completed_at')
                                    ->label('Perfil Completo em')
                                    ->disabled(),
                                Forms\Components\DateTimePicker::make('consent_at')
                                    ->label('Consentimento LGPD em')
                                    ->disabled(),
                            ]),
                        $ministriesField,
                        Forms\Components\Select::make('coordinator_ministry_id')
                            ->label('Ministério sob coordenação')
                            ->relationship('coordinatorMinistry', 'name')
                            ->preload()
                            ->searchable()
                            ->visible(fn (\Filament\Forms\Get $get) => (bool) $get('is_coordinator'))
                            ->required(fn (\Filament\Forms\Get $get) => (bool) $get('is_coordinator')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Controle de Acesso')
                    ->schema([
                        Forms\Components\CheckboxList::make('allowed_pages')
                            ->label('Páginas autorizadas')
                            ->options(self::getRestrictedPagesOptions())
                            ->columns(2)
                            ->helperText('Selecione quais páginas restritas este usuário pode acessar.'),
                        Forms\Components\Placeholder::make('allowed_pages_info')
                            ->label('Permissões atuais')
                            ->content(function (User $record = null) {
                                $opts = self::getRestrictedPagesOptions();
                                $allowed = collect((array) ($record?->allowed_pages ?? []));
                                return collect($opts)->map(function ($label, $path) use ($allowed) {
                                    $has = $allowed->contains($path);
                                    return sprintf('%s: %s', $label, $has ? 'permitido' : 'negado');
                                })->implode(' | ');
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Listagem e gestão de usuários')
            // Visual profissional em linhas: removemos o grid de cartões
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->weight('bold')
                    ->size('sm')
                    ->lineClamp(1)
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->limit(60)
                    ->tooltip(fn ($state) => (string) $state),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->wrap()
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(60),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->visibleFrom('md')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('group.name')
                    ->label('Grupo principal')
                    ->wrap()
                    ->lineClamp(2)
                    ->visibleFrom('lg')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Nível')
                    ->visibleFrom('md')
                    ->badge()
                    ->icon(fn ($state) => match ($state) {
                        'admin' => 'heroicon-o-shield-check',
                        'servo' => 'heroicon-o-hand-thumb-up',
                        default => 'heroicon-o-user',
                    })
                    ->color(fn ($state) => match ($state) {
                        'admin' => 'danger',
                        'servo' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_servo')
                    ->label('Servo')
                    ->hiddenFrom('sm')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_coordinator')
                    ->label('Coord.')
                    ->visibleFrom('lg')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('coordinatorMinistry.name')
                    ->label('Ministério')
                    ->wrap()
                    ->lineClamp(2)
                    ->visibleFrom('lg')
                    ->badge()
                    ->color('warning')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->icon(fn ($state) => match ($state) {
                        'active' => 'heroicon-o-check-badge',
                        'inactive' => 'heroicon-o-pause-circle',
                        'blocked' => 'heroicon-o-exclamation-triangle',
                    })
                    ->color(fn ($state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'blocked' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->icon('heroicon-o-clock')
                    ->dateTime('d/m/Y H:i')
                    ->visibleFrom('lg')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('allowed_pages')
                    ->label('Permissões')
                    ->html()
                    ->formatStateUsing(function ($state) {
                        $opts = self::getRestrictedPagesOptions();
                        $allowed = collect((array) ($state ?? []));
                        $parts = collect($opts)->map(function ($label, $path) use ($allowed) {
                            $has = $allowed->contains($path);
                            $color = $has ? '#065f46' : '#b91c1c';
                            $bg = $has ? '#ecfdf5' : '#fee2e2';
                            $txt = $has ? 'permitido' : 'negado';
                            return "<span style=\"display:inline-block;margin:.125rem;padding:.25rem .5rem;border-radius:.5rem;background:$bg;color:$color;border:1px solid rgba(0,0,0,.05)\">$label: $txt</span>";
                        })->implode(' ');
                        return $parts ?: '<span class="text-gray-400">—</span>';
                    })
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'blocked' => 'Bloqueado',
                    ]),
                Tables\Filters\SelectFilter::make('group_id')
                    ->label('Grupo principal')
                    ->relationship('group', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_servo')
                    ->label('É Servo?'),
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->headerActions([
                Tables\Actions\ExportAction::make()->label('Exportar CSV'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->label('')
                    ->tooltip('Editar')
                    ->size('sm')
                    ->color('primary'),
                Tables\Actions\Action::make('uploadPhoto')
                    ->icon('heroicon-o-photo')
                    ->label('')
                    ->tooltip('Enviar foto')
                    ->size('sm')
                    ->color('primary')
                    ->form([
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->visibility('public')
                            ->directory(fn (User $record) => 'user-photos/'.$record->id)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Marcar como ativa')
                            ->default(true),
                    ])
                    ->action(function (User $record, array $data) {
                        $file = $data['photo'] ?? null;
                        if ($file) {
                            $path = null;
                            if ($file instanceof TemporaryUploadedFile) {
                                $path = $file->store('user-photos/'.$record->id, 'public');
                            } elseif (is_string($file)) {
                                $path = $file;
                            }
                            if ($path) {
                                if (! empty($data['is_active'])) {
                                    $record->photos()->update(['is_active' => false]);
                                }
                                UserPhoto::create([
                                    'user_id' => $record->id,
                                    'file_path' => $path,
                                    'file_name' => basename($path),
                                    'file_size' => method_exists($file, 'getSize') ? $file->getSize() : 0,
                                    'mime_type' => method_exists($file, 'getMimeType') ? $file->getMimeType() : 'image/jpeg',
                                    'is_active' => ! empty($data['is_active']),
                                ]);
                            }
                        }
                    })
                    ->modalHeading('Enviar foto do usuário'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->tooltip('Excluir')
                    ->size('sm')
                    ->color('danger')
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('more')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->label('')
                    ->tooltip('Mais ações')
                    ->size('sm')
                    ->action(fn ($record) => null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()->label('Exportar Selecionados'),
                ]),
            ])
            ->searchPlaceholder('Buscar por nome, e-mail, grupo...')
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->paginationPageOptions([12, 24, 48])
            ->defaultSort('name')
            ->recordClasses(fn (\App\Models\User $record) => [
                'ring-red-600/10 bg-red-50' => $record->status === 'blocked',
                'opacity-70' => $record->status === 'inactive',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EventParticipationsRelationManager::class,
            RelationManagers\GroupAttendanceRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function getRestrictedPagesOptions(): array
    {
        return [
            '/pastoreio' => 'Pastoreio',
        ];
    }
}
