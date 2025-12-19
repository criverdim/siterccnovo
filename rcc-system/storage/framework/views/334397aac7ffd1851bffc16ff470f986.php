<?php $title = 'Ficha do Usuário - '.($user->name ?? 'Usuário'); ?>
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
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <?php $photo = $user->activePhoto?->file_path; ?>
                <img src="<?php echo e($photo ? asset('storage/'.$photo) : ($user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name))); ?>" alt="<?php echo e($user->name); ?>" class="w-20 h-20 rounded-full object-cover border shadow" loading="lazy" width="80" height="80">
                <div>
                    <div class="text-2xl md:text-3xl font-bold text-emerald-700"><?php echo e($user->name); ?></div>
                    <div class="flex items-center gap-2 mt-1 text-sm">
                        <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700"><?php echo e(ucfirst($user->role)); ?></span>
                        <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700"><?php echo e(ucfirst($user->status)); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->group): ?>
                            <span class="inline-flex items-center gap-2 px-2 py-1 rounded-full bg-gray-100 text-gray-700"><span class="inline-block w-2 h-2 rounded-full border border-gray-200"></span><?php echo e($user->group->name); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 print:hidden">
                <a href="/admin/users/<?php echo e($user->id); ?>/pdf" class="btn btn-outline inline-flex items-center gap-2"><i class="fa fa-file-pdf"></i><span>Salvar como PDF</span></a>
                <button onclick="window.print()" class="btn btn-primary inline-flex items-center gap-2"><i class="fa fa-print"></i><span>Imprimir</span></button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card p-4">
                <div class="text-emerald-700 font-semibold mb-3">Informações Pessoais</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><div class="text-gray-500">Nome</div><div class="text-gray-900"><?php echo e($user->name); ?></div></div>
                    <div><div class="text-gray-500">Email</div><div class="text-gray-900"><?php echo e($user->email); ?></div></div>
                    <div><div class="text-gray-500">CPF</div><div class="text-gray-900"><?php echo e($user->cpf ?? 'Não informado'); ?></div></div>
                    <div><div class="text-gray-500">Gênero</div><div class="text-gray-900"><?php echo e($user->gender ? ucfirst($user->gender) : 'Não informado'); ?></div></div>
                    <div><div class="text-gray-500">Nascimento</div><div class="text-gray-900"><?php echo e($user->birth_date ? \Illuminate\Support\Carbon::parse($user->birth_date)->format('d/m/Y') : 'Não informado'); ?></div></div>
                </div>
            </div>

            <div class="card p-4">
                <div class="text-emerald-700 font-semibold mb-3">Contato</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><div class="text-gray-500">Telefone</div><div class="text-gray-900"><?php echo e($user->phone ?? 'Não informado'); ?></div></div>
                    <div><div class="text-gray-500">WhatsApp</div><div class="text-gray-900"><?php echo e($user->whatsapp ?? 'Não informado'); ?></div></div>
                    <div><div class="text-gray-500">Cadastro</div><div class="text-gray-900"><?php echo e(optional($user->created_at)->format('d/m/Y H:i')); ?></div></div>
                    <div><div class="text-gray-500">Atualização</div><div class="text-gray-900"><?php echo e(optional($user->updated_at)->format('d/m/Y H:i')); ?></div></div>
                </div>
            </div>

            <div class="card p-4 md:col-span-2">
                <div class="text-emerald-700 font-semibold mb-3">Endereço</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div><div class="text-gray-500">CEP</div><div class="text-gray-900"><?php echo e($user->cep ?? 'Não informado'); ?></div></div>
                    <div class="md:col-span-2"><div class="text-gray-500">Endereço</div><div class="text-gray-900"><?php echo e($user->address ?? 'Não informado'); ?></div></div>
                    <div><div class="text-gray-500">Número</div><div class="text-gray-900"><?php echo e($user->number ?? '—'); ?></div></div>
                    <div><div class="text-gray-500">Complemento</div><div class="text-gray-900"><?php echo e($user->complement ?? '—'); ?></div></div>
                    <div><div class="text-gray-500">Bairro</div><div class="text-gray-900"><?php echo e($user->district ?? '—'); ?></div></div>
                    <div><div class="text-gray-500">Cidade</div><div class="text-gray-900"><?php echo e($user->city ?? '—'); ?></div></div>
                    <div><div class="text-gray-500">Estado</div><div class="text-gray-900"><?php echo e($user->state ?? '—'); ?></div></div>
                </div>
            </div>

            <div class="card p-4">
                <div class="text-emerald-700 font-semibold mb-3">Grupos</div>
                <div class="flex flex-wrap gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->group): ?>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border bg-emerald-50 border-emerald-100"><span class="inline-block w-2 h-2 rounded-full border border-gray-200"></span><?php echo e($user->group->name); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ($user->groups ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border bg-gray-50 border-gray-200"><span class="inline-block w-2 h-2 rounded-full border border-gray-200"></span><?php echo e($g->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="card p-4">
                <div class="text-emerald-700 font-semibold mb-3">Ministérios</div>
                <div class="flex flex-wrap gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($user->ministries ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="px-3 py-1 rounded-full border bg-gray-100 border-gray-200 text-gray-700"><?php echo e($m->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <span class="text-gray-500">Sem ministérios</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="card p-4 md:col-span-2">
                <div class="text-emerald-700 font-semibold mb-3">Atividades Recentes</div>
                <div class="grid md:grid-cols-2 gap-3">
                    <?php $acts = ($user->activities ?? collect())->take(10); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($acts->count()): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $acts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-3 rounded border bg-white">
                                <div class="text-sm font-medium text-gray-900"><?php echo e(ucfirst($act->activity_type)); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e(optional($act->created_at)->format('d/m/Y H:i')); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <div class="text-gray-500">Sem atividades recentes</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="card p-4 md:col-span-2">
                <div class="text-emerald-700 font-semibold mb-3">Mensagens Recentes</div>
                <div class="grid md:grid-cols-2 gap-3">
                    <?php $msgs = ($user->messages ?? collect())->take(10); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($msgs->count()): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $msgs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-3 rounded border bg-white">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($msg->subject ?? 'Mensagem'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e(optional($msg->created_at)->format('d/m/Y H:i')); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <div class="text-gray-500">Sem mensagens</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->photos && $user->photos->count()): ?>
            <div class="card p-4 md:col-span-2">
                <div class="text-emerald-700 font-semibold mb-3">Galeria de Fotos</div>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $user->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/'.$p->file_path)); ?>" alt="Foto" class="w-full h-28 object-cover rounded border" loading="lazy">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH /var/www/html/rcc-system/resources/views/admin/user-profile.blade.php ENDPATH**/ ?>