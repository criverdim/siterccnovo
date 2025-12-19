<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-b from-emerald-50 to-white">
    <section class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-emerald-700">Eventos RCC</h1>
                    <p class="mt-3 text-lg text-gray-700">Experiências transformadoras, retiros, encontros e celebrações. Participe!</p>
                    <form method="get" class="mt-6 grid grid-cols-1 md:grid-cols-6 gap-3" role="search" aria-label="Filtrar eventos">
                        <div class="relative md:col-span-3">
                            <input type="text" name="q" value="<?php echo e($q ?? request('q')); ?>" placeholder="Buscar por nome, local..." class="rounded-xl border pl-10 pr-4 py-3 w-full shadow-sm focus:ring-2 focus:ring-emerald-600" aria-label="Buscar eventos">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                        <select name="paid" class="rounded-xl border px-4 py-3 shadow-sm" aria-label="Tipo">
                            <option value="">Todos os tipos</option>
                            <option value="free" <?php if(($paid ?? request('paid'))==='free'): echo 'selected'; endif; ?>>Gratuitos</option>
                            <option value="paid" <?php if(($paid ?? request('paid'))==='paid'): echo 'selected'; endif; ?>>Pagos</option>
                        </select>
                        <select name="month" class="rounded-xl border px-4 py-3 shadow-sm" aria-label="Mês">
                            <option value="">Qualquer mês</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($m=1;$m<=12;$m++): ?>
                                <option value="<?php echo e($m); ?>" <?php if((int)($month ?? request('month'))===$m): echo 'selected'; endif; ?>><?php echo e(str_pad($m,2,'0',STR_PAD_LEFT)); ?></option>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <button class="px-5 py-3 rounded-xl bg-emerald-600 text-white shadow hover:bg-emerald-700" type="submit">Filtrar</button>
                        <a href="/events" class="px-5 py-3 rounded-xl border shadow-sm text-emerald-700 hover:bg-emerald-50">Limpar</a>
                    </form>
                </div>
                <div class="relative">
                    <div class="card-hero">
                        <img src="<?php echo e(asset('favicon.ico')); ?>" alt="RCC Eventos" class="w-full h-64 object-cover" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid md:grid-cols-3 gap-6" aria-live="polite">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($events ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="rounded-2xl border bg-white shadow-sm overflow-hidden">
                    <?php ($firstPhoto = (is_array($ev->photos) && count($ev->photos)) ? $ev->photos[0] : null); ?>
                    <?php ($thumb = $firstPhoto ? \Illuminate\Support\Str::of($firstPhoto)->replace('/original/','/thumbs/') : null); ?>
                    <img src="<?php echo e($thumb ? asset('storage/'.$thumb) : asset('favicon.ico')); ?>" alt="<?php echo e($ev->name); ?>" class="w-full h-40 object-cover" />
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-xl font-bold text-emerald-700"><?php echo e($ev->name); ?></h2>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ev->is_paid): ?>
                                <span class="pill pill-green"><i class="fa fa-ticket mr-1"></i>Pago</span>
                            <?php else: ?>
                                <span class="pill pill-green"><i class="fa fa-heart mr-1"></i>Gratuito</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="text-sm text-gray-600 mb-3">
                            <?php echo e(optional($ev->start_date)->format('d/m/Y')); ?> • <?php echo e($ev->location); ?>

                        </div>
                        <div class="text-sm text-gray-700 line-clamp-3"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($ev->description ?? ''), 180)); ?></div>
                        <div class="mt-4 flex items-center justify-between">
                            <a href="<?php echo e(route('events.show', $ev)); ?>" class="btn btn-outline btn-sm">Detalhes</a>
                            <a href="/events/<?php echo e($ev->id); ?>/participate" class="btn btn-primary btn-sm">Participar</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-gray-600">Nenhum evento encontrado.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(method_exists(($events ?? null),'links')): ?>
            <div class="mt-6"><?php echo e($events->links()); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/rcc-system/resources/views/events/index.blade.php ENDPATH**/ ?>