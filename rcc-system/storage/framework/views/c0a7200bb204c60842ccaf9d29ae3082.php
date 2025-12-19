<?php if (isset($component)) { $__componentOriginalbe23554f7bded3778895289146189db7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbe23554f7bded3778895289146189db7 = $attributes; } ?>
<?php $component = Filament\View\LegacyComponents\Page::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Filament\View\LegacyComponents\Page::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="space-y-6">
        <form method="post" class="flex gap-3">
            <?php echo csrf_field(); ?>
            <input type="text" name="query" placeholder="email/cpf/phone/whatsapp" class="p-2 rounded border w-full" />
            <button class="px-4 py-2 rounded bg-emerald-600 text-white">Buscar</button>
        </form>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($results)): ?>
            <div class="grid md:grid-cols-2 gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 border rounded">
                        <div class="font-semibold"><?php echo e($user['name']); ?></div>
                        <div class="text-sm text-gray-600"><?php echo e($user['email']); ?> • <?php echo e($user['phone']); ?></div>
                        <form method="post" class="mt-2 flex gap-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="sourceId" value="<?php echo e($user['id']); ?>" />
                            <input type="number" name="targetId" placeholder="ID destino" class="p-2 rounded border" />
                            <button class="px-3 py-2 rounded bg-gold text-white">Unir</button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbe23554f7bded3778895289146189db7)): ?>
<?php $attributes = $__attributesOriginalbe23554f7bded3778895289146189db7; ?>
<?php unset($__attributesOriginalbe23554f7bded3778895289146189db7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbe23554f7bded3778895289146189db7)): ?>
<?php $component = $__componentOriginalbe23554f7bded3778895289146189db7; ?>
<?php unset($__componentOriginalbe23554f7bded3778895289146189db7); ?>
<?php endif; ?>

<?php /**PATH /var/www/html/rcc-system/resources/views/filament/pages/duplicates-tool.blade.php ENDPATH**/ ?>