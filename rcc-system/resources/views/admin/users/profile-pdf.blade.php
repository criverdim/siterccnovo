@php $pdfMode = true; @endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4; margin: 0 }
        :root{--cv-primary:#006036;--cv-accent:#fdc800;--cv-secondary:#004058;--cv-soft:#b8d0dc;--cv-green:#006036;--cv-green2:#009049;--cv-yellow:#fdc800;--cv-blue:#004058;--cv-gray:#64748b;--cv-dark:#0f172a;--cv-muted:#475569;--cv-line:#e5e7eb}
        html, body { margin:0; padding:0; background:#fff }
        *{box-sizing:border-box}
        body{font-family:'Segoe UI',system-ui,-apple-system,'SF Pro Text',Roboto,Helvetica,Arial,sans-serif;font-size:10.8px;line-height:1.3;color:var(--cv-dark)}
        .cv-page-border{position:fixed;top:0;left:0;right:0;bottom:0;border:3mm solid var(--cv-primary)}
        .cv-frame{position:relative;width:188mm;margin:0 auto;padding:10mm;box-sizing:border-box;overflow:hidden}
        .cv-page{display:block;width:100%;margin:0 auto;text-align:left}
        .cv-topbar{display:table;width:100%;table-layout:fixed;margin:0 auto 0;padding:0 3mm;box-sizing:border-box;overflow:hidden}
        .cv-top-left{display:table-cell;vertical-align:middle;text-align:left}
        .cv-top-right{display:table-cell;vertical-align:middle;text-align:right}
        .cv-logo{width:47.5mm;max-width:47.5mm;height:auto;object-fit:contain;transform:translate(-15%,-12%);transform-origin:center}
        .cv-doc-title{font-size:17px;font-weight:900;color:var(--cv-dark)}
        .cv-header{display:flex;align-items:center;gap:1mm;border-bottom:1px solid var(--cv-line);padding-bottom:6px;margin:0 0 2mm 0}
        .cv-header-left{flex:0 0 auto;text-align:left}
        .cv-header-right{flex:1 1 auto;text-align:left;padding-left:1mm}
        .cv-photo{width:32mm;height:32mm;border-radius:9999px;object-fit:cover;border:1mm solid var(--cv-primary)}
        .cv-ident{display:flex;flex-direction:column;gap:4mm}
        .cv-name{font-size:16px;font-weight:900}
        .cv-email{color:var(--cv-muted);font-size:10.8px}
        .cv-badges{display:flex;flex-wrap:wrap;gap:4px;justify-content:flex-start}
        .cv-badge{display:inline-flex;align-items:center;gap:6px;padding:3px 8px;border-radius:9999px;border:1px solid var(--cv-line);background:#f8fafc;color:var(--cv-dark);font-weight:700;font-size:9.5px}
        .cv-content{width:100%;margin:5mm 0 0;border-collapse:collapse;table-layout:fixed;overflow:hidden}
        .cv-main{position:relative;top:-44mm}
        .cv-cell{vertical-align:top;width:50%;padding:0 2mm}
        .cv-cell:first-child{padding-left:0}
        .cv-cell:last-child{padding-right:2mm}
        .cv-block{background:#fbfcfd;border:1px solid #e6edf5;border-radius:4mm;padding:3mm}
        .cv-block.personal{border-left:2mm solid var(--cv-green)}
        .cv-block.address{border-left:2mm solid var(--cv-yellow)}
        .cv-block.groups{border-left:2mm solid var(--cv-green2)}
        .cv-block.ministries{border-left:2mm solid var(--cv-blue)}
        .cv-block.activities{border-left:2mm solid var(--cv-soft)}
        .cv-block.messages{border-left:2mm solid #6c6d70}
        .cv-title{display:flex;align-items:center;gap:3mm;font-size:13px;font-weight:800;margin:0 0 2mm 0;color:#0f172a;padding:1mm 2mm;border-radius:2mm;background:#f9fafb}
        .cv-block.personal .cv-title{background:#eaf4ee;color:var(--cv-green)}
        .cv-block.address .cv-title{background:#fff6cc;color:var(--cv-yellow)}
        .cv-block.groups .cv-title{background:#eaf7f0;color:var(--cv-green2)}
        .cv-block.ministries .cv-title{background:#e7eef5;color:var(--cv-blue)}
        .cv-block.activities .cv-title{background:#eff6f9;color:#0f172a}
        .cv-block.messages .cv-title{background:#f5f5f5;color:#334155}
        .cv-icon{width:12px;height:12px;fill:var(--cv-primary)}
        .cv-data{display:flex;flex-direction:column;gap:1mm}
        .cv-rowstack{display:flex;flex-direction:column}
        .cv-row{display:grid;grid-template-columns:35% 65%;align-items:flex-start;padding:3px 0;border-bottom:1px dashed #e3e8ef}
        .cv-row2{display:grid;grid-template-columns:1fr 1fr;gap:3mm;padding:3px 0;border-bottom:1px dashed #e3e8ef}
        .cv-label{color:#334155;font-weight:700}
        .cv-value{color:var(--cv-dark);font-size:11px;word-break:break-word;overflow-wrap:anywhere}
        .cv-tags{display:flex;flex-wrap:wrap;gap:4px}
        .cv-tags .cv-badge::before{content:'•';display:inline-block;margin-right:4px;color:#64748b}
        .cv-divider{height:1px;background:#e0e0e0;margin:2mm 0}
        .cv-list{display:flex;flex-direction:column;gap:1.5mm}
        .cv-note{display:grid;grid-template-columns:30% 70%;gap:3mm;padding:1mm;border:1px solid #e6edf5;border-radius:3mm;background:#fff}
        .cv-foot{margin-top:2mm;border-top:1px solid var(--cv-line);padding-top:2mm;color:var(--cv-gray);font-size:9.8px;text-align:center}
        .cv-topbar, .cv-header, .cv-foot{page-break-inside:avoid}
    </style>
</head>
<body>
    <div class="cv-page-border"></div>
    <div class="cv-frame">
    <div class="cv-page">
        <table class="cv-topbar" role="presentation">
            <tr>
                <td class="cv-top-right">
                    @if(!empty($logoDataUri))
                        <img class="cv-logo" src="{{ $logoDataUri }}" alt="Logo">
                    @endif
                </td>
            </tr>
        </table>
        <div class="cv-main">
        <div class="cv-header">
            <div class="cv-header-left">
                <img class="cv-photo" src="{{ $photoDataUri ?? $user->profile_photo_url }}" alt="{{ $user->name }}">
            </div>
            <div class="cv-header-right">
                <div class="cv-ident">
                    <div class="cv-name">{{ $user->name }}</div>
                    <div class="cv-email">{{ $user->email }}</div>
                    <div class="cv-badges">
                        @php
                            $statusColors = [
                                'active' => ['#dcfce7', '#166534', '#bbf7d0'],
                                'inactive' => ['#fee2e2', '#991b1b', '#fecaca'],
                                'pending' => ['#fef3c7', '#92400e', '#fde68a'],
                            ];
                            $statusLabels = [
                                'active' => 'Ativo',
                                'inactive' => 'Inativo',
                                'pending' => 'Pendente',
                            ];
                            $colors = $statusColors[$user->status] ?? ['#e5e7eb','#374151','#e5e7eb'];
                            $bg = $colors[0] ?? '#e5e7eb';
                            $fg = $colors[1] ?? '#374151';
                            $bd = $colors[2] ?? '#e5e7eb';
                            $statusLabel = $statusLabels[$user->status] ?? (is_string($user->status) ? $user->status : 'Desconhecido');
                        @endphp
                        <span class="cv-badge" style="background:{{ $bg }};color:{{ $fg }};border-color:{{ $bd }}">Status: {{ $statusLabel }}</span>
                    </div>
                </div>
            </div>
        </div>
        <table class="cv-content">
            <tr>
                <td class="cv-cell">
            <div class="cv-block personal">
                <div class="cv-title">
                    <svg class="cv-icon" viewBox="0 0 24 24"><path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z"/></svg>
                    Informações Pessoais
                </div>
                <div class="cv-data">
                    <div class="cv-row"><div class="cv-label">Nome</div><div class="cv-value">{{ $user->name }}</div></div>
                    <div class="cv-row"><div class="cv-label">Email</div><div class="cv-value">{{ $user->email }}</div></div>
                    <div class="cv-row"><div class="cv-label">Telefone</div><div class="cv-value">{{ $user->phone ?? 'Não informado' }}</div></div>
                    <div class="cv-row"><div class="cv-label">WhatsApp</div><div class="cv-value">{{ $user->whatsapp ?? 'Não informado' }}</div></div>
                    <div class="cv-row"><div class="cv-label">Nascimento</div><div class="cv-value">{{ optional($user->birth_date)->format('d/m/Y') ?? 'Não informado' }}</div></div>
                    <div class="cv-row"><div class="cv-label">CPF</div><div class="cv-value">{{ $user->cpf ?? 'Não informado' }}</div></div>
                    <div class="cv-row2">
                        <div>
                            <div class="cv-label">Função</div>
                            <div class="cv-value">{{ $user->role ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="cv-label">Servo</div>
                            <div class="cv-value">{{ $user->is_servo ? 'Sim' : 'Não' }}</div>
                        </div>
                    </div>
                </div>

                <div class="cv-divider"></div>

                <div class="cv-title">
                    <svg class="cv-icon" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
                    Endereço
                </div>
                <div class="cv-data">
                    <div class="cv-row"><div class="cv-label">CEP / Bairro</div><div class="cv-value">{{ $user->cep ?? 'Não informado' }} / {{ $user->district ?? 'Não informado' }}</div></div>
                    <div class="cv-row"><div class="cv-label">Endereço</div><div class="cv-value">{{ $user->address ?? 'Não informado' }} {{ $user->number ? ' - '.$user->number : '' }} {{ $user->complement ? ' - '.$user->complement : '' }}</div></div>
                    <div class="cv-row"><div class="cv-label">Cidade / Estado</div><div class="cv-value">{{ $user->city ?? 'Não informado' }} / {{ $user->state ?? 'Não informado' }}</div></div>
                </div>
            </div>
                </td>
                <td class="cv-cell">
            <div class="cv-block groups">
                <div class="cv-title">
                    <svg class="cv-icon" viewBox="0 0 24 24"><path d="M16 11c1.654 0 3-1.346 3-3S17.654 5 16 5s-3 1.346-3 3 1.346 3 3 3zm-8 0c1.654 0 3-1.346 3-3S9.654 5 8 5 5 6.346 5 8s1.346 3 3 3zm0 2c-2.67 0-8 1.336-8 4v3h12v-3c0-2.664-5.33-4-8-4zm8 0c-.29 0-.62.02-.97.05 1.21.9 1.97 2.07 1.97 3.95v3h8v-3c0-2.664-5.33-4-8-4z"/></svg>
                    Grupo que participa
                </div>
                <div class="cv-tags">
                    @php $groupsCount = ($user->groups && $user->groups->count()) ? $user->groups->count() : ($user->group ? 1 : 0); @endphp
                    @if($user->groups && $user->groups->count())
                        @foreach($user->groups->take(5) as $g)
                            <span class="cv-badge" style="background:{{ $g->color_hex ?? '#10B981' }};color:#0f172a;border-color:#e5e7eb">{{ $g->name }}</span>
                        @endforeach
                        @if($user->groups->count() > 5)
                            <span class="cv-badge" style="background:#f1f5f9;color:#0f172a">+{{ $user->groups->count() - 5 }}</span>
                        @endif
                    @elseif($user->group)
                        <span class="cv-badge" style="background:{{ $user->group->color_hex ?? '#10B981' }};color:#0f172a;border-color:#e5e7eb">{{ $user->group->name }}</span>
                    @else
                        <span class="cv-badge" style="background:#f1f5f9;color:#0f172a">Nenhum grupo</span>
                    @endif
                </div>

                <div class="cv-divider"></div>

                <div class="cv-title">
                    <svg class="cv-icon" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 8l-10-5v12l10 5 10-5V5l-10 5z"/></svg>
                    Ministérios de serviço
                </div>
                <div class="cv-tags">
                    @if($user->ministries && $user->ministries->count())
                        @foreach($user->ministries->take(5) as $m)
                            <span class="cv-badge" style="background:#f1f5f9;color:#0f172a">{{ $m->name }}</span>
                        @endforeach
                        @if($user->ministries->count() > 5)
                            <span class="cv-badge" style="background:#f1f5f9;color:#0f172a">+{{ $user->ministries->count() - 5 }}</span>
                        @endif
                    @else
                        <span class="cv-badge" style="background:#f1f5f9;color:#0f172a">Nenhum ministério</span>
                    @endif
                </div>

                <div class="cv-divider"></div>

                <div class="cv-title">
                    <svg class="cv-icon" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm6 16H6V6h12v13z"/></svg>
                    Atividades Recentes
                </div>
                @if($user->activities->count())
                    <div class="cv-list">
                        @foreach($user->activities->sortByDesc('created_at')->take(1) as $a)
                            <div class="cv-note">
                                <div class="cv-label">{{ optional($a->created_at)->format('d/m/Y H:i') }}</div>
                                <div class="cv-value">{{ $a->activity_description }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="cv-value" style="color:#64748b">Nenhuma atividade recente</div>
                @endif

                <div class="cv-divider"></div>

                <div class="cv-title">
                    <svg class="cv-icon" viewBox="0 0 24 24"><path d="M20 2H4C2.9 2 2 2.9 2 4v14l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                    Mensagens
                </div>
                @if($user->messages->count())
                    <div class="cv-list">
                        @foreach($user->messages->sortByDesc('created_at')->take(1) as $m)
                            <div class="cv-note">
                                <div class="cv-label">{{ $m->subject ?? 'Sem assunto' }}</div>
                                <div class="cv-value">{{ $m->content }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="cv-value" style="color:#64748b">Nenhuma mensagem</div>
                @endif
            </div>
            </div>
                </td>
            </tr>
        </table>
        <div class="cv-foot">
            <div>
                Cadastrado: {{ optional($user->created_at)->format('d/m/Y') }}
                • Perfil: {{ $user->profile_completed_at ? optional($user->profile_completed_at)->format('d/m/Y') : 'Não completo' }}
                • Consentimento: {{ $user->consent_at ? optional($user->consent_at)->format('d/m/Y') : 'Não' }}
            </div>
        </div>
        </div>
    </div>
    </div>
</body>
</html>
