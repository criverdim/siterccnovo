<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\UserPhoto;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Facades\Schema;

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
                                'other' => 'Outro',
                            ]),
                        Forms\Components\Select::make('role')
                            ->label('Nível de Acesso')
                            ->options([
                                'fiel' => 'Fiel',
                                'servo' => 'Servo',
                                'admin' => 'Administrador',
                            ])
                            ->default('fiel')
                            ->required(),
                        Forms\Components\Toggle::make('can_access_admin')
                            ->label('Pode acessar painel /admin')
                            ->inline(false),
                        Forms\Components\Toggle::make('is_master_admin')
                            ->label('Administrador master')
                            ->inline(false)
                            ->helperText('Apenas o administrador master pode acessar Configurações'),
                        $groupField,
                        Forms\Components\Placeholder::make('group_id_sync_info')
                            ->label('Grupo principal')
                            ->content('O primeiro grupo selecionado será usado como grupo principal em filtros e integrações legadas.')
                            ->hintIcon('heroicon-o-information-circle'),
                        Forms\Components\Toggle::make('is_servo')
                            ->label('É Servo?')
                            ->inline(false),
                        $ministriesField,
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
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->header(view('admin.users.header'))
            ->description('Listagem e gestão de usuários')
            // Visual profissional em linhas: removemos o grid de cartões
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->label('Nome')
                            ->weight('bold')
                            ->size('lg')
                            ->searchable()
                            ->limit(40)
                            ->tooltip(fn ($state) => (string) $state),
                        Tables\Columns\TextColumn::make('email')
                            ->icon('heroicon-o-envelope')
                            ->label('E-mail')
                            ->wrap()
                            ->lineClamp(2)
                            ->limit(60)
                            ->tooltip(fn ($state) => (string) $state)
                            ->copyable()
                            ->searchable()
                            ->extraAttributes(['class' => 'text-gray-700 max-w-[320px]']),
                        Tables\Columns\TextColumn::make('phone')
                            ->label('Telefone')
                            ->icon('heroicon-o-phone')
                            ->toggleable()
                            ->searchable()
                            ->wrap()
                            ->lineClamp(1)
                            ->limit(30)
                            ->tooltip(fn ($state) => (string) $state)
                            ->extraAttributes(['class' => 'text-gray-700 max-w-[240px]']),
                        Tables\Columns\TextColumn::make('whatsapp')
                            ->label('WhatsApp')
                            ->icon('heroicon-o-chat-bubble-left')
                            ->toggleable()
                            ->searchable()
                            ->wrap()
                            ->lineClamp(1)
                            ->limit(30)
                            ->tooltip(fn ($state) => (string) $state)
                            ->extraAttributes(['class' => 'text-gray-700 max-w-[240px]']),
                    ])->space(2)->extraAttributes(['class' => 'fi-ta-record-content']),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('group.name')
                            ->label('Grupo principal')
                            ->icon('heroicon-o-user-group')
                            ->sortable(),
                        Tables\Columns\TagsColumn::make('groups.name')
                            ->label('Grupos')
                            ->limit(3)
                            ->toggleable(),
                        Tables\Columns\TagsColumn::make('ministries.name')
                            ->label('Ministérios')
                            ->limit(3)
                            ->toggleable(),
                        Tables\Columns\TextColumn::make('role')
                            ->label('Nível')
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
                            ->boolean(),
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
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('updated_at')
                            ->icon('heroicon-o-clock')
                            ->dateTime('d/m/Y H:i')
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                    ])->space(2)->extraAttributes(['class' => 'fi-ta-record-actions']),
                ]),
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
                            ->acceptedFileTypes(['image/jpeg','image/png','image/jpg'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->visibility('public')
                            ->directory(fn (User $record) => 'user-photos/' . $record->id)
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
                                $path = $file->store('user-photos/' . $record->id, 'public');
                            } elseif (is_string($file)) {
                                $path = $file;
                            }
                            if ($path) {
                                if (!empty($data['is_active'])) {
                                    $record->photos()->update(['is_active' => false]);
                                }
                                UserPhoto::create([
                                    'user_id' => $record->id,
                                    'file_path' => $path,
                                    'file_name' => basename($path),
                                    'file_size' => method_exists($file, 'getSize') ? $file->getSize() : 0,
                                    'mime_type' => method_exists($file, 'getMimeType') ? $file->getMimeType() : 'image/jpeg',
                                    'is_active' => !empty($data['is_active']),
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
}
