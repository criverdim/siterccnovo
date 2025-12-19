<?php
    $pdfMode = (bool)($isPdf ?? false);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$pdfMode): ?>
        <?php echo app('Illuminate\Foundation\Vite')('resources/css/filament/admin.css'); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <style>
        :root{--cv-primary:#10b981;--cv-gray:#64748b;--cv-primary-rgb:16,185,129;--cv-accent-address-rgb:250,204,21}
        body{font-family:'Segoe UI',system-ui,-apple-system,'SF Pro Text',Roboto,Helvetica,Arial,sans-serif;background:#fff;margin:0}
        .cv-page{max-width:980px;margin:24px auto;padding:24px}
        .cv-header{display:flex;align-items:center;gap:20px;border-bottom:2px solid #e5e7eb;padding-bottom:16px}
        .cv-photo{width:120px;height:120px;border-radius:9999px;object-fit:cover;box-shadow:0 8px 22px rgba(0,0,0,.14);border:6px solid transparent;background:linear-gradient(#fff,#fff) padding-box,linear-gradient(90deg,var(--cv-primary),#059669) border-box}
        .cv-title{display:flex;flex-direction:column;gap:6px}
        .cv-name{font-size:1.6rem;font-weight:800;color:#0f172a}
        .cv-email{color:#475569}
        .cv-actions{margin-left:auto;display:flex;gap:10px;align-items:center}
        .cv-btn{display:inline-flex;align-items:center;justify-content:center;height:36px;padding:0 16px;border-radius:9999px;font-weight:700;border:1px solid #e5e7eb;background:linear-gradient(90deg,var(--cv-primary),#059669);color:#fff;text-decoration:none}
        .cv-btn.secondary{background:#fff;color:#0f172a}
        .cv-btn.warning{background:linear-gradient(90deg,#fde047,#facc15);color:#0f172a;border-color:#facc15}
        .cv-section{margin-top:24px;padding:16px;border-radius:5px}
        .cv-section h3{font-size:1.05rem;color:#0f172a;margin:0 0 12px 0}
        .cv-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .cv-item{display:flex;gap:8px;align-items:flex-start;padding:15px 0;border-bottom:1px solid #e0e0e0}
        .cv-item:first-child{border-top:1px solid #e0e0e0}
        .cv-section--personal .cv-item{background:#fff;border:1px solid #eef2f7;border-left:4px solid rgba(var(--cv-primary-rgb),.35);box-shadow:2px 2px 4px rgba(0,0,0,.1);border-radius:8px;padding:12px}
        .cv-section--address .cv-item{background:#fff;border:1px solid #eef2f7;border-left:4px solid rgba(var(--cv-accent-address-rgb),.4);box-shadow:2px 2px 4px rgba(0,0,0,.1);border-radius:8px;padding:12px}
        .cv-label{color:#334155;font-weight:600;min-width:160px}
        .cv-value{color:#0f172a}
        .cv-badges{display:flex;flex-wrap:wrap;gap:6px}
        .cv-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:9999px;border:1px solid rgba(0,0,0,.08);font-weight:600}
        .cv-foot{margin-top:24px;border-top:1px solid #e5e7eb;padding-top:12px;color:#64748b}
        .cv-divider{height:1px;background:#e0e0e0;margin:20px 0}
        @media print{
            .cv-actions{display:none}
            .cv-page{padding:0}
        }
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pdfMode): ?>
        @page { size: A4; margin: 10mm }
        html, body { width:100% }
        .cv-page{max-width:750px;margin:0 auto;padding:0}
        .cv-header, .cv-section, .cv-foot{page-break-inside:avoid}
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        @media (max-width:768px){
            .cv-grid{grid-template-columns:1fr}
            .cv-section{padding:14px}
            .cv-label{min-width:120px}
        }
        @media print{
            .cv-actions{display:none}
            .cv-page{padding:0}
        }
    </style>
</head>
<body>
    <div class="cv-page">
        <div class="cv-header">
            <img class="cv-photo" src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>">
            <div class="cv-title">
                <div class="cv-name"><?php echo e($user->name); ?></div>
                <div class="cv-email"><?php echo e($user->email); ?></div>
                <div class="cv-badges">
                    <?php
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
                    ?>
                    <span class="cv-badge" style="background:<?php echo e($bg ?? '#e5e7eb'); ?>;color:<?php echo e($fg ?? '#374151'); ?>;border-color:<?php echo e($bd ?? '#e5e7eb'); ?>">Status: <?php echo e($statusLabel); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->group): ?>
                        <span class="cv-badge" style="background:<?php echo e($user->group->color_hex ?? '#10B981'); ?>;color:#ffffff;border-color:rgba(0,0,0,.08)">
                            Grupo: <?php echo e($user->group->name); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="cv-actions">
                <a class="cv-btn warning" href="<?php echo e(url()->previous()); ?>">Voltar</a>
                <button class="cv-btn" onclick="window.print()">Imprimir</button>
                <a class="cv-btn" target="_blank" rel="noopener" href="<?php echo e(route('admin.users.profile.pdf', ['user' => $user->id, 'fresh' => 1])); ?>">Baixar PDF</a>
            </div>
        </div>

        <div class="cv-section cv-section--personal">
            <h3>Informações Pessoais</h3>
            <div class="cv-grid">
                <div class="cv-item"><div class="cv-label">Nome</div><div class="cv-value"><?php echo e($user->name); ?></div></div>
                <div class="cv-item"><div class="cv-label">Email</div><div class="cv-value"><?php echo e($user->email); ?></div></div>
                <div class="cv-item"><div class="cv-label">Telefone</div><div class="cv-value"><?php echo e($user->phone ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">WhatsApp</div><div class="cv-value"><?php echo e($user->whatsapp ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">Nascimento</div><div class="cv-value"><?php echo e(optional($user->birth_date)->format('d/m/Y') ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">CPF</div><div class="cv-value"><?php echo e($user->cpf ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">Função</div><div class="cv-value"><?php echo e($user->role ?? '—'); ?></div></div>
                <div class="cv-item"><div class="cv-label">É Servo</div><div class="cv-value"><?php echo e($user->is_servo ? 'Sim' : 'Não'); ?></div></div>
            </div>
        </div>

        <div class="cv-section cv-section--address">
            <h3>Endereço</h3>
            <div class="cv-grid">
                <div class="cv-item"><div class="cv-label">CEP</div><div class="cv-value"><?php echo e($user->cep ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">Endereço</div><div class="cv-value"><?php echo e($user->address ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">Número</div><div class="cv-value"><?php echo e($user->number ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">Complemento</div><div class="cv-value"><?php echo e($user->complement ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">Bairro</div><div class="cv-value"><?php echo e($user->district ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">Cidade</div><div class="cv-value"><?php echo e($user->city ?? 'Não informado'); ?></div></div>
                <div class="cv-item"><div class="cv-label">Estado</div><div class="cv-value"><?php echo e($user->state ?? 'Não informado'); ?></div></div>
            </div>
        </div>

        <div class="cv-divider"></div>

        <div class="cv-section">
            <h3>Grupo que participa</h3>
            <div class="cv-badges">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->groups && $user->groups->count()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $user->groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="cv-badge" style="background:<?php echo e($g->color_hex ?? '#10B981'); ?>;color:#ffffff;border-color:rgba(0,0,0,.08)"><?php echo e($g->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php elseif($user->group): ?>
                    <span class="cv-badge" style="background:<?php echo e($user->group->color_hex ?? '#10B981'); ?>;color:#ffffff;border-color:rgba(0,0,0,.08)"><?php echo e($user->group->name); ?></span>
                <?php else: ?>
                    <span class="cv-badge" style="background:#eef2f7;color:#0f172a">Nenhum grupo</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="cv-divider"></div>

        <div class="cv-section">
            <h3>Ministérios de serviço</h3>
            <div class="cv-badges">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->ministries && $user->ministries->count()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $user->ministries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="cv-badge" style="background:#eef2f7;color:#0f172a"><?php echo e($m->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <span class="cv-badge" style="background:#eef2f7;color:#0f172a">Nenhum ministério</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="cv-section">
            <h3>Atividades Recentes</h3>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->activities->count()): ?>
                <div class="cv-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $user->activities->sortByDesc('created_at')->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="cv-item">
                            <div class="cv-label"><?php echo e(optional($a->created_at)->format('d/m/Y H:i')); ?></div>
                            <div class="cv-value"><?php echo e($a->activity_description); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="cv-value" style="color:#64748b">Nenhuma atividade recente</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="cv-section">
            <h3>Mensagens</h3>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->messages->count()): ?>
                <div class="cv-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $user->messages->sortByDesc('created_at')->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="cv-item">
                            <div class="cv-label"><?php echo e($m->subject ?? 'Sem assunto'); ?></div>
                            <div class="cv-value"><?php echo e($m->content); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="cv-value" style="color:#64748b">Nenhuma mensagem</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="cv-foot">
            <div>Cadastrado em <?php echo e(optional($user->created_at)->format('d/m/Y')); ?></div>
            <div>Perfil completo: <?php echo e($user->profile_completed_at ? optional($user->profile_completed_at)->format('d/m/Y') : 'Não completo'); ?></div>
            <div>Consentimento: <?php echo e($user->consent_at ? optional($user->consent_at)->format('d/m/Y') : 'Não consentiu'); ?></div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/rcc-system/resources/views/admin/users/profile.blade.php ENDPATH**/ ?>