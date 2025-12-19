<?php ($title = 'Painel Administrativo'); ?>
<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title)]); ?>
    <div class="max-w-7xl mx-auto p-6 md:p-10">
        <h1 class="text-3xl font-bold text-emerald-700 mb-4">Painel Administrativo</h1>
        <p class="text-gray-700 mb-6">Bem-vindo ao painel clássico de administração.</p>
        <div class="grid gap-4 md:grid-cols-2">
            <a href="/admin" class="block p-4 rounded-lg border hover:border-emerald-600 hover:bg-emerald-50">
                <div class="text-lg font-semibold text-emerald-700">Abrir painel completo</div>
                <div class="text-sm text-gray-600">Acesse o painel do sistema (Filament).</div>
            </a>
            <a href="/admin/users/1/profile" class="block p-4 rounded-lg border hover:border-emerald-600 hover:bg-emerald-50">
                <div class="text-lg font-semibold text-emerald-700">Ver ficha de usuário</div>
                <div class="text-sm text-gray-600">Acesse uma ficha de usuário como exemplo.</div>
            </a>
        </div>
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
<?php /**PATH /var/www/html/rcc-system/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>