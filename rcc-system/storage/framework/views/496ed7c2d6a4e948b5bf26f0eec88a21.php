<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ingresso</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111; }
        .card { border: 2px solid #c9a043; border-radius: 12px; padding: 16px; }
        .title { font-size: 20px; color: #0b7a48; font-weight: bold; }
        .row { display: flex; gap: 16px; }
        .col { flex: 1; }
        .badge { display:inline-block; padding:4px 8px; border-radius:8px; background:#eee; }
        img { max-width: 240px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">Ingresso - <?php echo e($participation->event?->name); ?></div>
        <div class="row" style="margin-top: 10px;">
            <div class="col">
                <div><strong>Nome:</strong> <?php echo e($participation->user?->name); ?></div>
                <div><strong>Evento:</strong> <?php echo e($participation->event?->name); ?></div>
                <div><strong>Data:</strong> <?php echo e(optional($participation->event)->start_date?->format('d/m/Y')); ?></div>
                <div><strong>Horário:</strong> <?php echo e(optional($participation->event)->start_time); ?></div>
                <div><strong>UUID:</strong> <?php echo e($participation->ticket_uuid); ?></div>
                <div class="badge">Status: <?php echo e($participation->payment_status); ?></div>
            </div>
            <div class="col" style="text-align: right;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($qrPath) && file_exists($qrPath)): ?>
                    <img src="<?php echo e($qrPath); ?>" alt="QR Code" />
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/rcc-system/resources/views/tickets/pdf.blade.php ENDPATH**/ ?>