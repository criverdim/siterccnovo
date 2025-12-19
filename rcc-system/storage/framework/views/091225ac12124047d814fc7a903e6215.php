<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="max-w-md mx-auto p-6">
        <h1 class="text-2xl font-bold text-emerald-700 mb-4">Redefinir senha</h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?><div class="p-3 rounded bg-red-50 text-red-700 mb-3"><?php echo e($errors->first()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <form method="post" action="/password/reset" class="grid gap-3">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="token" value="<?php echo e($token); ?>" />
            <input type="hidden" name="email" value="<?php echo e($email); ?>" />
            <label class="grid gap-1">
                <span class="text-sm">Nova senha</span>
                <input type="password" name="password" class="input" required />
            </label>
            <label class="grid gap-1">
                <span class="text-sm">Confirmar senha</span>
                <input type="password" name="password_confirmation" class="input" required />
            </label>
            <div class="text-xs text-gray-600">A senha deve ter no mínimo 8 caracteres, incluir maiúscula, minúscula, número e símbolo.</div>
            <button class="btn btn-primary">Redefinir</button>
        </form>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>

<?php /**PATH /var/www/html/rcc-system/resources/views/auth/passwords/reset.blade.php ENDPATH**/ ?>