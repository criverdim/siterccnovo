<div class="grid md:grid-cols-2 gap-6">
    <div>
        <div class="text-emerald-700 font-semibold mb-2">Contato</div>
        <div class="text-sm text-gray-700">Endereço: <?php echo e(data_get($siteSettings, 'site.address')); ?></div>
        <div class="text-sm text-gray-700">Telefone: <?php echo e(data_get($siteSettings, 'site.phone')); ?></div>
        <div class="text-sm text-gray-700">WhatsApp: <?php echo e(data_get($siteSettings, 'site.whatsapp')); ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($siteSettings,'site.email')): ?>
            <div class="text-sm text-gray-700">E-mail: <?php echo e(data_get($siteSettings, 'site.email')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div>
        <div class="text-emerald-700 font-semibold mb-2">Redes</div>
        <div class="flex items-center gap-4 text-2xl">
            <a href="<?php echo e(data_get($siteSettings, 'social.instagram', '#')); ?>" class="text-emerald-700 hover:gold" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="<?php echo e(data_get($siteSettings, 'social.facebook', '#')); ?>" class="text-emerald-700 hover:gold" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="<?php echo e(data_get($siteSettings, 'social.youtube', '#')); ?>" class="text-emerald-700 hover:gold" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</div>

<?php /**PATH /var/www/html/rcc-system/resources/views/components/site/info.blade.php ENDPATH**/ ?>