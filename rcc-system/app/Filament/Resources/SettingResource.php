<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Configurações';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Marca')
                    ->schema([
                        Forms\Components\FileUpload::make('value.logo')
                            ->label('Logotipo')
                            ->directory('brand')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([null, '4:1', '3:1', '1:1'])
                            ->imageEditorViewportWidth(1024)
                            ->imageEditorViewportHeight(512)
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png','image/svg+xml','image/jpeg'])
                            ->helperText('Formatos: PNG, SVG, JPG • Máx: 2MB • Mín: 200x50px')
                            ->validationMessages([
                                'maxSize' => 'Tamanho máximo 2MB',
                                'acceptedFileTypes' => 'Somente PNG, SVG ou JPG',
                            ])
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth(1024)
                            ->imageResizeTargetHeight(256)
                            ->imageResizeUpscale(false)
                            ->downloadable()
                            ->previewable(true),
                    ])
                    ->visible(fn(Forms\Get $get) => $get('key') === 'brand'),

                Forms\Components\Section::make('Site')
                    ->schema([
                        Forms\Components\TextInput::make('value.address')->label('Endereço'),
                        Forms\Components\TextInput::make('value.phone')->label('Telefone')->mask('(99) 9999-9999'),
                        Forms\Components\TextInput::make('value.whatsapp')->label('WhatsApp')->mask('(99) 9 9999-9999'),
                        Forms\Components\TextInput::make('value.email')->label('Email')->email(),
                    ])->columns(2)->visible(fn(Forms\Get $get) => $get('key') === 'site'),

                Forms\Components\Section::make('Servidor de Email')
                    ->schema([
                        Forms\Components\TextInput::make('value.host')->label('Host')->placeholder('smtp.seudominio.com')->required(),
                        Forms\Components\TextInput::make('value.port')->label('Porta')->numeric()->placeholder('587')->required(),
                        Forms\Components\TextInput::make('value.username')->label('Usuário')->required(),
                        Forms\Components\TextInput::make('value.password')->label('Senha')->password()->required(),
                        Forms\Components\Select::make('value.encryption')->label('Criptografia')->options(['tls'=>'TLS','ssl'=>'SSL'])->native(false)->required(),
                        Forms\Components\TextInput::make('value.from_email')->label('Email Remetente')->email()->required(),
                        Forms\Components\TextInput::make('value.from_name')->label('Nome Remetente')->required(),
                        Forms\Components\TextInput::make('value.subject')->label('Assunto padrão')->default('RCC - Recuperação de senha'),
                        Forms\Components\TextInput::make('value.test_recipient')->label('Destinatário de teste')->email(),
                        Forms\Components\Placeholder::make('spf_dkim_dmarc')
                            ->label('Checklist SPF/DKIM/DMARC')
                            ->content('<ul style="list-style: disc; padding-left: 1rem;">
                                <li>SPF: adicione registro TXT com seu provedor. <a href="https://mxtoolbox.com/spf.aspx" target="_blank">Verificar SPF</a></li>
                                <li>DKIM: habilite e publique chave pública no DNS. <a href="https://mxtoolbox.com/dkim.aspx" target="_blank">Verificar DKIM</a></li>
                                <li>DMARC: crie TXT em _dmarc com política. <a href="https://dmarcian.com/dmarc-inspector/" target="_blank">Verificar DMARC</a></li>
                                <li>Use remetente consistente e domínio verificado para evitar SPAM.</li>
                            </ul>'),
                    ])->columns(2)->visible(fn(Forms\Get $get) => $get('key') === 'email'),

                Forms\Components\Section::make('Servidor de SMS')
                    ->schema([
                        Forms\Components\TextInput::make('value.api_url')->label('API URL')->url()->required(),
                        Forms\Components\TextInput::make('value.api_key')->label('API Key')->password()->required(),
                        Forms\Components\TextInput::make('value.sender_id')->label('Sender ID')->required(),
                    ])->columns(2)->visible(fn(Forms\Get $get) => $get('key') === 'sms'),

                Forms\Components\Section::make('Redes Sociais')
                    ->schema([
                        Forms\Components\TextInput::make('value.facebook')->label('Facebook URL')->url(),
                        Forms\Components\TextInput::make('value.instagram')->label('Instagram URL')->url(),
                        Forms\Components\TextInput::make('value.youtube')->label('YouTube URL')->url(),
                        Forms\Components\TextInput::make('value.whatsapp')->label('WhatsApp URL')->url(),
                        Forms\Components\TextInput::make('value.tiktok')->label('TikTok URL')->url(),
                    ])->columns(2)->visible(fn(Forms\Get $get) => $get('key') === 'social'),
                Forms\Components\Section::make('Mercado Pago')
                    ->schema([
                        Forms\Components\TextInput::make('value.access_token')->label('Access Token')->password()->required(),
                        Forms\Components\TextInput::make('value.public_key')->label('Public Key')->required(),
                        Forms\Components\Select::make('value.mode')->options([
                            'sandbox' => 'Sandbox',
                            'production' => 'Produção',
                        ])->label('Modo')->required(),
                        Forms\Components\TextInput::make('value.webhook_url')->label('Webhook URL')->url()->required(),
                    ])->columns(2)->visible(fn(Forms\Get $get) => $get('key') === 'mercadopago'),

                Forms\Components\Section::make('WhatsApp Business')
                    ->schema([
                        Forms\Components\TextInput::make('value.url')->label('API URL')->url()->required(),
                        Forms\Components\TextInput::make('value.token')->label('Token')->password()->required(),
                        Forms\Components\TextInput::make('value.phone_id')->label('Phone ID')->required(),
                        Forms\Components\Toggle::make('value.enabled')->label('Habilitado'),
                    ])->columns(2)->visible(fn(Forms\Get $get) => $get('key') === 'whatsapp'),

                Forms\Components\Section::make('Templates de Automação')
                    ->schema([
                        Forms\Components\RichEditor::make('value.template_saudades')->label('Template Saudades')->columnSpanFull(),
                        Forms\Components\RichEditor::make('value.template_aniversario')->label('Template Aniversário')->columnSpanFull(),
                        Forms\Components\RichEditor::make('value.template_confirmacao')->label('Template Confirmação')->columnSpanFull(),
                    ])->visible(fn(Forms\Get $get) => $get('key') === 'templates'),

                Forms\Components\Select::make('key')
                    ->label('Chave')
                    ->options([
                        'brand' => 'Marca',
                        'site' => 'Site (Endereço & Contato)',
                        'email' => 'Email Server',
                        'sms' => 'SMS Server',
                        'social' => 'Redes Sociais',
                        'mercadopago' => 'Mercado Pago',
                        'whatsapp' => 'WhatsApp',
                        'templates' => 'Templates de Automação',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Chave')->badge(),
                Tables\Columns\TextColumn::make('updated_at')->label('Atualizado em')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('test_email')
                    ->label('Enviar e-mail de teste')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->action(function() {
                        $userId = auth()->id();
                        $rlKey = 'smtp_test_user_' . ($userId ?? 'guest');
                        if (cache()->get($rlKey, 0) >= 3) {
                            \Filament\Notifications\Notification::make()->title('Muitas tentativas. Tente novamente em alguns minutos.')->warning()->send();
                            return;
                        }
                        $cfg = \App\Models\Setting::where('key','email')->first();
                        $to = $cfg?->value['test_recipient'] ?? null;
                        if (!$cfg || !$to) {
                            \Filament\Notifications\Notification::make()->title('Configure o destinatário de teste em "Servidor de Email"')->warning()->send();
                            return;
                        }
                        try {
                            \Mail::raw('Teste de envio RCC', function($m) use ($to, $cfg){
                                $fromEmail = $cfg->value['from_email'] ?? config('mail.from.address');
                                $fromName = $cfg->value['from_name'] ?? config('mail.from.name');
                                $m->from($fromEmail, $fromName)->to($to)->subject($cfg->value['subject'] ?? 'Teste');
                            });
                            cache()->increment($rlKey);
                            cache()->put($rlKey, cache()->get($rlKey), now()->addMinutes(10));
                            \Filament\Notifications\Notification::make()->title('E-mail de teste enviado').success()->send();
                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()->title('Falha ao enviar')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Tables\Actions\Action::make('validate_smtp')
                    ->label('Validar SMTP (sem enviar)')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function() {
                        $cfg = \App\Models\Setting::where('key','email')->first();
                        if (!$cfg) { \Filament\Notifications\Notification::make()->title('Configure o Servidor de Email').warning()->send(); return; }
                        $host = $cfg->value['host'] ?? null;
                        $port = (int)($cfg->value['port'] ?? 0);
                        $enc = $cfg->value['encryption'] ?? null;
                        $start = microtime(true);
                        $code = 0; $err = '';
                        try {
                            $target = ($enc === 'ssl' || $port === 465) ? ('ssl://'.$host) : $host;
                            $fp = @fsockopen($target, $port, $errno, $errstr, 10.0);
                            if ($fp) {
                                stream_set_timeout($fp, 10);
                                $banner = fgets($fp);
                                if ($banner !== false && preg_match('/^\d{3}/', $banner, $m)) { $code = (int)$m[0]; }
                                fclose($fp);
                            } else { $err = $errstr ?: 'Conexão falhou'; }
                        } catch (\Throwable $e) { $err = $e->getMessage(); }
                        $lat = round((microtime(true) - $start) * 1000);
                        if ($err) {
                            \Filament\Notifications\Notification::make()->title('Falha na conexão SMTP')->body("Erro: $err\nLatência: {$lat}ms")->danger()->send();
                        } else {
                            \Filament\Notifications\Notification::make()->title('Conexão SMTP OK')->body("Código: {$code}\nLatência: {$lat}ms")->success()->send();
                        }
                    }),
                Tables\Actions\Action::make('export_email_report')
                    ->label('Exportar relatório de e-mail')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function() {
                        $cfg = \App\Models\Setting::where('key','email')->first();
                        $data = [
                            'env' => env('APP_ENV'),
                            'smtp' => [
                                'host' => $cfg?->value['host'] ?? null,
                                'port' => $cfg?->value['port'] ?? null,
                                'encryption' => $cfg?->value['encryption'] ?? null,
                                'from_email' => $cfg?->value['from_email'] ?? null,
                            ],
                            'timestamp' => now()->toIso8601String(),
                        ];
                        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        return response($json, 200, [
                            'Content-Type' => 'application/json',
                            'Content-Disposition' => 'attachment; filename="email-config-report.json"',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
