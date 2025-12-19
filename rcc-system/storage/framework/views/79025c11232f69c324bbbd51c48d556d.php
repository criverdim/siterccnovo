<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Pastoreio']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Pastoreio')]); ?>
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-emerald-700 mb-4">Pastoreio</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Presenças Totais</div>
            <div class="text-2xl font-bold"><?php echo e($totalAttendance ?? 0); ?></div>
        </div>
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Últimos 30 dias</div>
            <div class="text-2xl font-bold"><?php echo e($last30 ?? 0); ?></div>
        </div>
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Últimos 60 / 90 dias</div>
            <div class="text-lg font-semibold">60: <?php echo e($last60 ?? 0); ?> • 90: <?php echo e($last90 ?? 0); ?></div>
        </div>
    </div>
    <div class="mb-6">
        <h2 class="font-semibold">Ranking dos mais presentes</h2>
        <ul class="list-disc pl-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ($ranking ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($item->user->name ?? '—'); ?> — <?php echo e($item->total); ?> presenças</li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Novos participantes (30d)</div>
            <div class="text-2xl font-bold"><?php echo e($newParticipantsCount ?? 0); ?></div>
        </div>
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Fieis em risco</div>
            <div class="text-2xl font-bold"><?php echo e($atRiskCount ?? 0); ?></div>
        </div>
    </div>
    <div id="react-pastoreio-app"></div>
</div>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <span class="hidden" data-group-option data-id="<?php echo e($group->id); ?>" data-name="<?php echo e($group->name); ?>"></span>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH /var/www/html/rcc-system/resources/views/pastoreio/index.blade.php ENDPATH**/ ?>