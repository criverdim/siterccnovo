<?php ($title = 'Evento - '.$event->name); ?>
<?php ($og = [
    'title' => $event->name,
    'description' => strip_tags($event->description ?? ''),
    'image' => (is_array($event->photos) && count($event->photos)) ? asset('storage/'.($event->photos[0])) : asset('favicon.ico'),
    'url' => url('/events/'.$event->id),
]); ?>
<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $title,'og' => $og]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'og' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($og)]); ?>
    <div class="max-w-5xl mx-auto p-6 md:p-10">
        <section class="relative rounded-3xl overflow-hidden border shadow mb-6">
            <?php ($heroPhoto = (is_array($event->photos) && count($event->photos)) ? \Illuminate\Support\Str::of($event->photos[0])->replace('/original/','/thumbs/') : null); ?>
            <img src="<?php echo e($heroPhoto ? asset('storage/'.$heroPhoto) : asset('favicon.ico')); ?>" alt="<?php echo e($event->name); ?>" class="w-full h-72 object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <div class="text-sm opacity-90">Evento</div>
                <div class="text-3xl font-bold"><?php echo e($event->name); ?></div>
            </div>
        </section>
        <h1 class="text-4xl font-bold text-emerald-700 mb-2"><?php echo e($event->name); ?></h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->category): ?>
            <div class="pill pill-green mb-4"><i class="fa fa-tag mr-2"></i><?php echo e(ucfirst($event->category)); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="text-sm text-gray-700 mb-2">Data: <?php echo e(optional($event->start_date)->format('d/m/Y')); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->end_date): ?> – <?php echo e($event->end_date->format('d/m/Y')); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> • Horário: <?php echo e($event->start_time); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->end_time): ?> – <?php echo e($event->end_time); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></div>
        <div class="text-sm text-gray-700 mb-4">Local: <?php echo e($event->location); ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->arrival_info): ?>
            <div class="p-4 rounded-xl border bg-white mb-6">
                <div class="card-section-title"><i class="fa fa-car mr-2"></i>Chegada e estacionamento</div>
                <div class="text-sm text-gray-700"><?php echo nl2br(e($event->arrival_info)); ?></div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="prose max-w-none mb-6"><?php echo $event->description; ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->map_embed_url): ?>
            <div class="rounded-xl overflow-hidden border mb-6">
                <iframe src="<?php echo e($event->map_embed_url); ?>" width="100%" height="300" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa do evento"></iframe>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="p-4 rounded-xl border bg-white">
                <div class="flex items-end justify-between mb-3">
                    <div class="font-semibold">Participar do evento</div>
                    <form id="participateForm" method="post" action="<?php echo e(route('events.participate', $event)); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="user_id" value="<?php echo e(auth()->id()); ?>" />
                        <button type="submit" class="px-4 py-2 rounded-md bg-emerald-600 text-white">Participar</button>
                    </form>
                </div>
                <div class="flex items-center gap-2">
                    <form id="payForm" method="get" action="<?php echo e(route('checkout')); ?>">
                        <select name="method" class="rounded-md border px-3 py-2">
                            <option value="pix">PIX</option>
                            <option value="card">Cartão</option>
                            <option value="boleto">Boleto</option>
                        </select>
                        <input type="hidden" name="event" value="<?php echo e($event->id); ?>" />
                        <button type="submit" class="px-4 py-2 rounded-md bg-emerald-600 text-white">Pagar</button>
                    </form>
                </div>
            </div>
            <div>
            <div class="mt-6">
                <div class="relative overflow-hidden rounded-2xl border" role="region" aria-roledescription="carousel" aria-label="Galeria de fotos do evento">
                        <div class="flex gap-6 snap-x snap-mandatory overflow-x-auto p-4" id="eventCarousel" tabindex="0">
                            <?php ($photos = is_array($event->photos) ? $event->photos : []); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php ($thumb = \Illuminate\Support\Str::of($photo)->replace('/original/','/thumbs/')); ?>
                                <img src="<?php echo e(asset('storage/'.$thumb)); ?>" data-full="<?php echo e(asset('storage/'.$photo)); ?>" alt="Foto do evento <?php echo e($event->name); ?>" loading="lazy" decoding="async" fetchpriority="low" width="280" height="160" class="min-w-[280px] h-40 object-cover rounded-xl border snap-start card-hover pulse-soft" />
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="absolute inset-y-0 left-2 flex items-center">
                            <button id="eventPrev" class="p-2 rounded-full bg-white border shadow" aria-controls="eventCarousel" aria-label="Anterior"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                        </div>
                        <div class="absolute inset-y-0 right-2 flex items-center">
                            <button id="eventNext" class="p-2 rounded-full bg-white border shadow" aria-controls="eventCarousel" aria-label="Próxima"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($event->extra_services) && count($event->extra_services)): ?>
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="card-section-title">Serviços adicionais</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $event->extra_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-3 rounded-xl border bg-white">
                                    <div class="font-medium"><?php echo e($s['title'] ?? 'Serviço'); ?></div>
                                    <div class="text-sm text-gray-700"><?php echo e($s['desc'] ?? ''); ?></div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($s['price'])): ?>
                                        <div class="mt-1 text-sm text-emerald-700">Preço adicional: R$ <?php echo e(number_format((float)$s['price'],2,',','.')); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="mt-6 p-4 border rounded">
                    <h2 class="card-section-title">Ingressos</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="p-3 rounded-xl border bg-white">
                            <div class="font-medium">Ingresso padrão</div>
                            <div class="text-sm text-gray-700">Acesso a todas as atividades</div>
                            <div class="mt-1 text-lg text-emerald-700 font-semibold">R$ <?php echo e(number_format((float)($event->price ?? 0),2,',','.')); ?></div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->parceling_enabled && $event->parceling_max): ?>
                                <div class="text-xs text-gray-600">Parcelamento em até <?php echo e($event->parceling_max); ?>x sem juros</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->coupons_enabled): ?>
                                <div class="text-xs text-gray-600">Cupons promocionais disponíveis</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($event->min_age ?? null)): ?>
                        <div class="p-3 rounded-xl border bg-white">
                            <div class="font-medium">Requisitos</div>
                            <div class="text-sm text-gray-700">Idade mínima: <?php echo e($event->min_age); ?> anos</div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->capacity): ?>
                                <div class="text-sm text-gray-700">Capacidade: <?php echo e($event->capacity); ?> pessoas</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($event->schedule) && count($event->schedule)): ?>
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="card-section-title">Programação</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $event->schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-3 rounded-xl border bg-white flex items-start gap-3">
                                    <i class="fa fa-calendar text-emerald-700 mt-1"></i>
                                    <div>
                                        <div class="font-medium"><?php echo e($item['title'] ?? 'Atividade'); ?></div>
                                        <div class="text-sm text-gray-700"><?php echo e($item['date'] ?? ''); ?> <?php echo e($item['time'] ?? ''); ?></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['desc'])): ?>
                                            <div class="text-sm text-gray-600"><?php echo e($item['desc']); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->terms): ?>
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="card-section-title">Termos e condições</h2>
                        <div class="prose max-w-none"><?php echo $event->terms; ?></div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->rules): ?>
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="card-section-title">Regras de participação</h2>
                        <div class="prose max-w-none"><?php echo $event->rules; ?></div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <span class="hidden" data-event-id="<?php echo e($event->id); ?>" data-user-email="<?php echo e(auth()->user()->email ?? ''); ?>"></span>
        </div>
    </div>
    <script>
        const participateForm = document.getElementById('participateForm');
        if (participateForm) {
            participateForm.addEventListener('submit', function (e) {
                const loggedIn = !!document.querySelector('[data-user-email]')?.getAttribute('data-user-email');
                if (!loggedIn) {
                    e.preventDefault();
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                }
            });
        }
        const eventCarousel = document.getElementById('eventCarousel');
        const eventPrev = document.getElementById('eventPrev');
        const eventNext = document.getElementById('eventNext');
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        function scrollEventBy(delta){ if(eventCarousel) eventCarousel.scrollBy({left: delta, behavior: prefersReduced ? 'auto' : 'smooth'}); }
        if(eventPrev) eventPrev.addEventListener('click', () => scrollEventBy(-300));
        if(eventNext) eventNext.addEventListener('click', () => scrollEventBy(300));
        if(eventCarousel) eventCarousel.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') scrollEventBy(-300);
            if (e.key === 'ArrowRight') scrollEventBy(300);
        });
        // expand thumbnail to full in a modal in future; for now click opens new tab
        document.querySelectorAll('#eventCarousel img').forEach(img => {
            img.addEventListener('click', () => {
                const full = img.getAttribute('data-full');
                if (full) window.open(full, '_blank');
            });
        });
    </script>
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
<?php /**PATH /var/www/html/rcc-system/resources/views/events/show.blade.php ENDPATH**/ ?>