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
                            ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/jpeg'])
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
                    ->visible(fn (Forms\Get $get) => $get('key') === 'brand'),

                Forms\Components\Section::make('Site')
                    ->schema([
                        Forms\Components\TextInput::make('value.address')->label('Endereço'),
                        Forms\Components\TextInput::make('value.phone')->label('Telefone')->mask('(99) 9999-9999'),
                        Forms\Components\TextInput::make('value.whatsapp')->label('WhatsApp')->mask('(99) 9 9999-9999'),
                        Forms\Components\TextInput::make('value.email')->label('Email')->email(),
                    ])->columns(2)->visible(fn (Forms\Get $get) => $get('key') === 'site'),

                Forms\Components\Section::make('Servidor de Email')
                    ->schema([
                        Forms\Components\TextInput::make('value.host')->label('Host')->placeholder('smtp.seudominio.com')->required(),
                        Forms\Components\TextInput::make('value.port')->label('Porta')->numeric()->placeholder('587')->required(),
                        Forms\Components\TextInput::make('value.username')->label('Usuário')->required(),
                        Forms\Components\TextInput::make('value.password')->label('Senha')->password()->required(),
                        Forms\Components\Select::make('value.encryption')->label('Criptografia')->options(['tls' => 'TLS', 'ssl' => 'SSL'])->native(false)->required(),
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
                    ])->columns(2)->visible(fn (Forms\Get $get) => $get('key') === 'email'),

                Forms\Components\Section::make('Servidor de SMS')
                    ->schema([
                        Forms\Components\TextInput::make('value.api_url')->label('API URL')->url()->required(),
                        Forms\Components\TextInput::make('value.api_key')->label('API Key')->password()->required(),
                        Forms\Components\TextInput::make('value.sender_id')->label('Sender ID')->required(),
                    ])->columns(2)->visible(fn (Forms\Get $get) => $get('key') === 'sms'),

                Forms\Components\Section::make('Redes Sociais')
                    ->schema([
                        Forms\Components\TextInput::make('value.facebook')->label('Facebook URL')->url(),
                        Forms\Components\TextInput::make('value.instagram')->label('Instagram URL')->url(),
                        Forms\Components\TextInput::make('value.youtube')->label('YouTube URL')->url(),
                        Forms\Components\TextInput::make('value.whatsapp')->label('WhatsApp URL')->url(),
                        Forms\Components\TextInput::make('value.tiktok')->label('TikTok URL')->url(),
                    ])->columns(2)->visible(fn (Forms\Get $get) => $get('key') === 'social'),
                Forms\Components\Section::make('Mercado Pago')
                    ->schema([
                        Forms\Components\TextInput::make('value.access_token')->label('Access Token')->password()->required(),
                        Forms\Components\TextInput::make('value.public_key')->label('Public Key')->required(),
                        Forms\Components\Select::make('value.mode')->options([
                            'sandbox' => 'Sandbox',
                            'production' => 'Produção',
                        ])->label('Modo')->required(),
                        Forms\Components\TextInput::make('value.webhook_url')->label('Webhook URL')->url()->required(),
                    ])->columns(2)->visible(fn (Forms\Get $get) => $get('key') === 'mercadopago'),

                Forms\Components\Section::make('WhatsApp Business')
                    ->schema([
                        Forms\Components\TextInput::make('value.url')
                            ->label('API URL')
                            ->helperText('Ex.: https://graph.facebook.com/v19.0 ou endpoint completo com /{phone_id}/messages')
                            ->url()
                            ->required()
                            ->regex('/^https:\\/\\/graph\\.facebook\\.com\\/v\\d+\\.\\d+(?:\\/.*)?$/')
                            ->validationMessages(['regex' => 'Informe uma URL do Graph API válida (ex.: https://graph.facebook.com/v19.0)']),
                        Forms\Components\TextInput::make('value.token')
                            ->label('Token')
                            ->password()
                            ->required()
                            ->dehydrated(true),
                        Forms\Components\TextInput::make('value.phone_id')
                            ->label('Phone ID')
                            ->required()
                            ->regex('/^\d{10,}$/')
                            ->validationMessages(['regex' => 'Informe um Phone ID numérico válido (10+ dígitos)']),
                        Forms\Components\Toggle::make('value.enabled')->label('Habilitado'),
                        Forms\Components\TextInput::make('value.test_number')
                            ->label('Número de teste (com DDI)')
                            ->helperText('Ex.: 5511999999999')
                            ->mask('999999999999999')
                            ->maxLength(15),
                    ])->columns(2)->visible(fn (Forms\Get $get) => $get('key') === 'whatsapp'),

                Forms\Components\Section::make('Templates de Automação')
                    ->schema([
                        Forms\Components\RichEditor::make('value.template_saudades')->label('Template Saudades')->columnSpanFull(),
                        Forms\Components\RichEditor::make('value.template_aniversario')->label('Template Aniversário')->columnSpanFull(),
                        Forms\Components\RichEditor::make('value.template_confirmacao')->label('Template Confirmação')->columnSpanFull(),
                    ])->visible(fn (Forms\Get $get) => $get('key') === 'templates'),

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
                Tables\Actions\Action::make('validate_whatsapp')
                    ->label('Validar WhatsApp')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->key === 'whatsapp')
                    ->action(function () {
                        $cfg = \App\Models\Setting::where('key', 'whatsapp')->first();
                        if (! $cfg) {
                            \Filament\Notifications\Notification::make()->title('Configure a seção WhatsApp').warning()->send();

                            return;
                        }
                        $url = $cfg->value['url'] ?? null;
                        $token = $cfg->value['token'] ?? null;
                        $phoneId = $cfg->value['phone_id'] ?? null;
                        $enabled = (bool) ($cfg->value['enabled'] ?? false);
                        if (! $url || ! $token || ! $phoneId) {
                            \Filament\Notifications\Notification::make()->title('Credenciais incompletas').body('Informe URL, Token e Phone ID').danger()->send();

                            return;
                        }
                        $endpoint = str_contains($url, '/messages') ? rtrim($url, '/') : (rtrim($url, '/')."/{$phoneId}/messages");
                        $start = microtime(true);
                        try {
                            $resp = \Http::withToken($token)->timeout(10)->post($endpoint, [
                                'messaging_product' => 'whatsapp',
                                'to' => '00000000000',
                                'type' => 'text',
                                'text' => ['body' => 'ping'],
                            ]);
                            $lat = round((microtime(true) - $start) * 1000);
                            $n = \Filament\Notifications\Notification::make()
                                ->title('Validação WhatsApp')
                                ->body('Endpoint: '.$endpoint."\nStatus: ".$resp->status()."\nLatência: {$lat}ms");
                            if ($resp->successful()) { $n->success()->send(); } else { $n->warning()->send(); }
                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()->title('Falha na conexão')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Tables\Actions\Action::make('send_whatsapp_test')
                    ->label('Enviar WhatsApp de teste')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->key === 'whatsapp')
                    ->action(function () {
                        $cfg = \App\Models\Setting::where('key', 'whatsapp')->first();
                        $url = $cfg?->value['url'] ?? null;
                        $token = $cfg?->value['token'] ?? null;
                        $phoneId = $cfg?->value['phone_id'] ?? null;
                        $enabled = (bool) ($cfg?->value['enabled'] ?? false);
                        $num = $cfg?->value['test_number'] ?? null;
                        $numSan = preg_replace('/\D+/', '', (string) ($num ?? ''));
                        if (str_starts_with($numSan, '0')) { $numSan = ltrim($numSan, '0'); }

                        if (! $enabled) {
                            \Filament\Notifications\Notification::make()->title('WhatsApp desabilitado').body('Habilite nas Configurações para enviar.').warning()->send();

                            return;
                        }
                        if (! $url || ! $token || ! $phoneId) {
                            \Filament\Notifications\Notification::make()->title('Credenciais incompletas').body('Informe URL, Token e Phone ID').danger()->send();

                            return;
                        }
                        if (! $numSan || strlen($numSan) < 10) {
                            \Filament\Notifications\Notification::make()->title('Número de teste inválido').body('Use DDI, ex.: 5511999999999').danger()->send();

                            return;
                        }
                        $endpoint = str_contains($url, '/messages') ? rtrim($url, '/') : (rtrim($url, '/')."/{$phoneId}/messages");

                        try {
                            $actorId = auth()->id() ?: (\App\Models\User::where('is_master_admin', true)->value('id')
                                ?: \App\Models\User::where('role', 'admin')->value('id')
                                ?: \App\Models\User::value('id'));
                            if (! $actorId) {
                                \Filament\Notifications\Notification::make()->title('Nenhum usuário disponível')->body('Crie um usuário administrador para associar o envio.')->danger()->send();

                                return;
                            }
                            $wa = \App\Models\WaMessage::create([
                                'user_id' => $actorId,
                                'message' => 'Mensagem de teste RCC',
                                'payload' => ['ui' => true, 'endpoint' => $endpoint, 'force_template' => true],
                                'status' => 'pending',
                            ]);
                            $u = new \App\Models\User();
                            $u->whatsapp = $numSan;
                            $wa->setRelation('user', $u);
                            $svc = app(\App\Services\WhatsAppService::class);
                            $status = $svc->send($wa);

                            try {
                                $actorId = auth()->id() ?: (\App\Models\User::where('is_master_admin', true)->value('id')
                                    ?: \App\Models\User::where('role', 'admin')->value('id'));
                                if ($actorId) {
                                    \App\Models\UserActivity::create([
                                        'user_id' => $actorId,
                                        'activity_type' => 'whatsapp_test_send',
                                        'details' => ['number' => $numSan, 'status' => $status],
                                        'ip_address' => request()?->ip() ?? '0.0.0.0',
                                    ]);
                                }
                            } catch (\Throwable $e) {
                                // ignora falha de log para não impactar o envio
                            }

                            if (in_array($status, ['sent','delivered'], true)) {
                                \Filament\Notifications\Notification::make()->title('Mensagem enviada')->success()->send();
                            } else {
                                \Filament\Notifications\Notification::make()->title('Falha ao enviar')->body(json_encode($wa->payload))->danger()->send();
                            }
                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()->title('Erro no envio')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Tables\Actions\Action::make('wa_next_steps')
                    ->label('Próximos passos WhatsApp')
                    ->icon('heroicon-o-light-bulb')
                    ->visible(fn ($record) => $record->key === 'whatsapp')
                    ->action(function () {
                        $links = [
                            ['label' => 'Templates de Automação', 'url' => '/admin/settings?filter[key]=templates'],
                            ['label' => 'Logs WhatsApp', 'url' => '/admin/logs'],
                            ['label' => 'Fluxos de Atendimento', 'url' => '/admin/pastoreio'],
                        ];
                        $msg = "Recomendações:\n- Teste tipos de mensagem (texto, template)\n- Configure templates personalizados\n- Integre com fluxos de Pastoreio (atendimento)\n\nAtalhos:";
                        foreach ($links as $l) {
                            $msg .= "\n• {$l['label']}: {$l['url']}";
                        }
                        \Filament\Notifications\Notification::make()->title('Próximos passos').body($msg)->success()->send();
                    }),
                Tables\Actions\Action::make('test_email')
                    ->label('Enviar e-mail de teste')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->key === 'email')
                    ->action(function () {
                        $userId = auth()->id();
                        $rlKey = 'smtp_test_user_'.($userId ?? 'guest');
                        if (cache()->get($rlKey, 0) >= 3) {
                            \Filament\Notifications\Notification::make()->title('Muitas tentativas. Tente novamente em alguns minutos.')->warning()->send();

                            return;
                        }
                        $cfg = \App\Models\Setting::where('key', 'email')->first();
                        $to = $cfg?->value['test_recipient'] ?? null;
                        if (! $cfg || ! $to) {
                            \Filament\Notifications\Notification::make()->title('Configure o destinatário de teste em "Servidor de Email"')->warning()->send();

                            return;
                        }
                        try {
                            \Mail::raw('Teste de envio RCC', function ($m) use ($to, $cfg) {
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
                    ->visible(fn ($record) => $record->key === 'email')
                    ->action(function () {
                        $cfg = \App\Models\Setting::where('key', 'email')->first();
                        if (! $cfg) {
                            \Filament\Notifications\Notification::make()->title('Configure o Servidor de Email').warning()->send();

                            return;
                        }
                        $host = $cfg->value['host'] ?? null;
                        $port = (int) ($cfg->value['port'] ?? 0);
                        $enc = $cfg->value['encryption'] ?? null;
                        $start = microtime(true);
                        $code = 0;
                        $err = '';
                        try {
                            $target = ($enc === 'ssl' || $port === 465) ? ('ssl://'.$host) : $host;
                            $fp = @fsockopen($target, $port, $errno, $errstr, 10.0);
                            if ($fp) {
                                stream_set_timeout($fp, 10);
                                $banner = fgets($fp);
                                if ($banner !== false && preg_match('/^\d{3}/', $banner, $m)) {
                                    $code = (int) $m[0];
                                }
                                fclose($fp);
                            } else {
                                $err = $errstr ?: 'Conexão falhou';
                            }
                        } catch (\Throwable $e) {
                            $err = $e->getMessage();
                        }
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
                    ->visible(fn ($record) => $record->key === 'email')
                    ->action(function () {
                        $cfg = \App\Models\Setting::where('key', 'email')->first();
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
                Tables\Actions\Action::make('quality_mcp_report')
                    ->label('Relatório de Qualidade MCP')
                    ->icon('heroicon-o-document-text')
                    ->visible(fn ($record) => $record->key === 'mercadopago')
                    ->action(function () {
                        $mp = \App\Models\Setting::where('key', 'mercadopago')->first();
                        $vals = $mp?->value ?? [];
                        $report = [
                            'mode' => $vals['mode'] ?? null,
                            'has_access_token' => ! empty($vals['access_token']),
                            'has_public_key' => ! empty($vals['public_key']),
                            'webhook_url' => $vals['webhook_url'] ?? null,
                            'webhook_ping' => null,
                            'env' => env('APP_ENV'),
                            'timestamp' => now()->toIso8601String(),
                        ];
                        try {
                            if (! empty($report['webhook_url'])) {
                                $resp = \Http::timeout(5)->post($report['webhook_url'], [
                                    'type' => 'payment', 'data' => ['id' => 'test_ping', 'status' => 'pending'], 'action' => 'payment.updated',
                                ]);
                                $report['webhook_ping'] = ['status' => $resp->status()];
                            }
                        } catch (\Throwable $e) {
                            $report['webhook_ping'] = ['error' => $e->getMessage()];
                        }
                        $json = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        return response($json, 200, [
                            'Content-Type' => 'application/json',
                            'Content-Disposition' => 'attachment; filename="mcp-quality-report.json"',
                        ]);
                    }),
                Tables\Actions\Action::make('save_webhook')
                    ->label('Salvar/Pingar Webhook')
                    ->icon('heroicon-o-link')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->key === 'mercadopago')
                    ->action(function () {
                        $mp = \App\Models\Setting::where('key', 'mercadopago')->first();
                        $url = $mp?->value['webhook_url'] ?? null;
                        if (! $url) {
                            \Filament\Notifications\Notification::make()->title('Defina o Webhook URL em Mercado Pago').warning()->send();

                            return;
                        }
                        try {
                            $resp = \Http::timeout(5)->post($url, ['type' => 'payment', 'data' => ['id' => 'test_webhook', 'status' => 'approved'], 'action' => 'payment.updated']);
                            \Filament\Notifications\Notification::make()->title('Webhook ping').body('Status: '.$resp->status())->success()->send();
                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()->title('Falha ao pingar webhook').body($e->getMessage())->danger()->send();
                        }
                    }),
                Tables\Actions\Action::make('simulate_webhook')
                    ->label('Simular Webhook')
                    ->icon('heroicon-o-bolt')
                    ->visible(fn ($record) => $record->key === 'mercadopago')
                    ->action(function () {
                        $req = request()->create('/webhooks/mercadopago', 'POST', [
                            'type' => 'payment',
                            'data' => ['id' => 'sim_'.uniqid(), 'status' => 'approved'],
                            'action' => 'payment.updated',
                        ]);
                        app()->handle($req);
                        \Filament\Notifications\Notification::make()->title('Webhook simulado').success()->send();
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

    public static function canViewAny(): bool
    {
        $u = auth()->user();

        return (bool) ($u?->is_master_admin);
    }

    public static function canCreate(): bool
    {
        $u = auth()->user();

        return (bool) ($u?->is_master_admin);
    }

    public static function canEdit($record): bool
    {
        $u = auth()->user();

        return (bool) ($u?->is_master_admin);
    }

    public static function canDelete($record): bool
    {
        $u = auth()->user();

        return (bool) ($u?->is_master_admin);
    }
}
