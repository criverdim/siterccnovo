<?php ($title = 'Grupo - '.$group->name); ?>
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
    <div class="max-w-6xl mx-auto p-6 md:p-10">
        <h1 class="text-4xl md:text-5xl font-bold text-emerald-700 mb-6">
            <span aria-hidden="true" style="display:inline-block;width:0.9rem;height:0.9rem;border-radius:9999px;background:<?php echo e($group->color_hex ?? '#0b7a48'); ?>;border:1px solid #e5e7eb;box-shadow:0 0 0 1px #fff inset;margin-right:0.5rem"></span>
            <span class="sr-only">Cor do grupo <?php echo e($group->name); ?></span>
            <?php echo e($group->name); ?>

        </h1>
        <div id="react-group-show-app"></div>
        <span class="hidden"
              data-group-id="<?php echo e($group->id); ?>"
              data-group-color="<?php echo e($group->color_hex ?? ''); ?>"
              data-group-weekday="<?php echo e($group->weekday); ?>"
              data-group-time="<?php echo e(optional($group->time)->format('H:i')); ?>"
              data-group-address="<?php echo e($group->address); ?>"
              data-group-description="<?php echo e($group->description); ?>"
              data-group-responsible="<?php echo e($group->responsible); ?>"
              data-group-photo="<?php echo e($group->cover_photo ?: ((is_array($group->photos) && count($group->photos)) ? $group->photos[0] : '')); ?>"
              data-group-responsible-phone="<?php echo e($group->responsible_phone); ?>"
              data-group-responsible-whatsapp="<?php echo e($group->responsible_whatsapp); ?>"
              data-group-responsible-email="<?php echo e($group->responsible_email); ?>"
              data-group-photos='<?php echo json_encode($group->photos, 15, 512) ?>'
              data-cover-bg-color="<?php echo e($group->cover_bg_color ?? ''); ?>"
              data-cover-object-position="<?php echo e($group->cover_object_position ?? ''); ?>"
        ></span>
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
<?php /**PATH /var/www/html/rcc-system/resources/views/groups/show.blade.php ENDPATH**/ ?>