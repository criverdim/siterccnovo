<?php ($title = $event->name . ' - RCC System'); ?>
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
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-emerald-600 to-teal-700 text-white">
            <div class="absolute inset-0 bg-black/30"></div>
            
            <!-- Carrossel de Fundo -->
            <?php ($gallery = is_array($event->gallery_images ?? null) ? array_values(array_filter($event->gallery_images)) : []); ?>
            <?php ($heroImages = count($gallery) ? $gallery : ([$event->featured_image] ?? [])); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count(array_filter($heroImages))): ?>
                <img id="hero-image" src="<?php echo e(Storage::disk('public')->url($heroImages[0])); ?>" 
                     alt="<?php echo e($event->name); ?>" 
                     class="absolute inset-0 w-full h-full object-cover opacity-40 transition-opacity duration-700">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                <div class="max-w-4xl">
                    <div class="mb-6">
                        <a href="<?php echo e(route('events.index')); ?>" 
                           class="inline-flex items-center text-emerald-200 hover:text-white mb-4 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Voltar para Eventos
                        </a>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        <?php echo e($event->name); ?>

                    </h1>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->short_description): ?>
                        <p class="text-xl md:text-2xl text-emerald-100 mb-8 max-w-3xl">
                            <?php echo e($event->short_description); ?>

                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <!-- Informações Principais -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-semibold">Data & Horário</span>
                            </div>
                            <p class="text-emerald-100">
                                <?php echo e($event->start_date->format('d/m/Y')); ?> às <?php echo e($event->start_date->format('H:i')); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->end_date && $event->end_date->format('Y-m-d') !== $event->start_date->format('Y-m-d')): ?>
                                    <br>até <?php echo e($event->end_date->format('d/m/Y')); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-semibold">Local</span>
                            </div>
                            <p class="text-emerald-100"><?php echo e($event->location); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->address): ?>
                                <p class="text-emerald-200 text-sm"><?php echo e($event->address); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <span class="font-semibold">Vagas</span>
                            </div>
                            <p class="text-emerald-100">
                                <?php echo e($event->availableTickets()); ?> disponíveis
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->capacity): ?>
                                    <span class="text-emerald-200 text-sm">de <?php echo e($event->capacity); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Botão de Ação -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasTicket): ?>
                            <a href="<?php echo e(route('events.my-tickets')); ?>" 
                               class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Meus Ingressos
                            </a>
                        <?php elseif($event->isSoldOut()): ?>
                            <button disabled 
                                    class="bg-gray-400 text-white px-8 py-4 rounded-xl font-semibold text-lg cursor-not-allowed">
                                Ingressos Esgotados
                            </button>
                        <?php elseif(!$event->isActive()): ?>
                            <button disabled 
                                    class="bg-gray-400 text-white px-8 py-4 rounded-xl font-semibold text-lg cursor-not-allowed">
                                Evento Indisponível
                            </button>
                        <?php else: ?>
                            <a href="<?php echo e(route('events.purchase', $event)); ?>" 
                               class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                Participar do Evento
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->price > 0): ?>
                            <div class="flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4">
                                <span class="text-2xl font-bold text-white">
                                    R$ <?php echo e(number_format($event->price, 2, ',', '.')); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->is_paid): ?>
                                    <span class="text-emerald-200 ml-2">por pessoa</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4">
                                <span class="text-xl font-bold text-white">
                                    Entrada Gratuita
                                </span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-8 space-y-12">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden md:h-[420px]">
                        <div class="flex flex-col md:flex-row md:items-stretch">
                            <div class="relative flex-1 h-72 md:h-full md:self-stretch overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->featured_image): ?>
                                    <img src="<?php echo e(Storage::disk('public')->url($event->folder_image ?? $event->featured_image)); ?>" alt="<?php echo e($event->name); ?>" class="absolute inset-0 w-full h-full object-cover">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="absolute top-4 left-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->isSoldOut()): ?>
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">Esgotado</span>
                                    <?php elseif($event->status === 'active'): ?>
                                        <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-sm font-medium">Disponível</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <div class="p-4 md:p-6 bg-gradient-to-b from-white to-emerald-50 md:flex-1 flex flex-col">
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3"><?php echo e($event->name); ?></h2>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->short_description): ?>
                                    <p class="text-gray-700 mb-4"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($event->short_description), 250)); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                    <div class="flex items-center bg-white rounded-xl shadow-sm p-3 h-full">
                                        <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo e($event->start_date->format('d/m/Y')); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($event->start_date->format('H:i')); ?>h</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center bg-white rounded-xl shadow-sm p-3 h-full">
                                        <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo e($event->location); ?></p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->address): ?>
                                                <p class="text-sm text-gray-500"><?php echo e($event->address); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex items-center bg-white rounded-xl shadow-sm p-3 h-full">
                                        <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900 whitespace-nowrap"><?php echo e($event->availableTickets()); ?> disponíveis</p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->capacity): ?>
                                                <p class="text-sm text-gray-500">de <?php echo e($event->capacity); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between bg-white rounded-xl shadow-sm p-3 h-full gap-4">
                                        <div class="text-2xl font-bold text-emerald-700 leading-none">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->price > 0): ?>
                                                R$ <?php echo e(number_format($event->price, 2, ',', '.')); ?>

                                            <?php else: ?>
                                                <span class="text-green-600">Gratuito</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sobre o Evento -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->description): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Sobre o Evento</h2>
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed md:columns-2 lg:columns-2 gap-8 prose-ul:ml-4 prose-ol:ml-4">
                                <?php ($allowedTags = '<p><br><strong><em><ul><ol><li>'); ?>
                                <?php echo strip_tags((string) $event->description, $allowedTags); ?>

                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <!-- Programação -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->schedule && count($event->schedule) > 0): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Programação</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $event->schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-start bg-gradient-to-br from-gray-50 to-emerald-50 rounded-xl p-6 shadow-sm">
                                        <div class="bg-emerald-600 text-white rounded-lg px-4 py-2 font-semibold mr-6 min-w-[80px] text-center">
                                            <?php echo e($item['time'] ?? ''); ?>

                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 mb-2"><?php echo e($item['title'] ?? ''); ?></h3>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($item['description'])): ?>
                                                <p class="text-gray-600"><?php echo e($item['description']); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <!-- Palestrantes/Organizadores -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->organizers && count($event->organizers) > 0): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Palestrantes & Organizadores</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $event->organizers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organizer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center">
                                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900"><?php echo e($organizer['name'] ?? ''); ?></h3>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($organizer['role'])): ?>
                                                <p class="text-emerald-600 text-sm"><?php echo e($organizer['role']); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($organizer['description'])): ?>
                                                <p class="text-gray-600 text-sm mt-1"><?php echo e($organizer['description']); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    

                    <!-- Mapa de Localização -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->map_embed_url): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Mapa de Localização</h2>
                            <div class="rounded-xl overflow-hidden bg-gray-100 border">
                                <iframe src="<?php echo e($event->map_embed_url); ?>" width="100%" style="height:420px;border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Participantes / Interessados -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($participants) && $participants->count() > 0): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Participantes</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center bg-gray-50 rounded-xl p-4">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold mr-3">
                                            <?php echo e(strtoupper(substr($p->user->name ?? 'U', 0, 1))); ?>

                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 line-clamp-1"><?php echo e($p->user->name ?? 'Usuário'); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo e($p->payment_status === 'approved' ? 'Confirmado' : 'Interessado'); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <!-- Informações Adicionais -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->additional_info && count($event->additional_info) > 0): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Informações Importantes</h2>
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $event->additional_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-emerald-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <h4 class="font-semibold text-gray-900"><?php echo e($info['label'] ?? ''); ?></h4>
                                                <p class="text-gray-600"><?php echo e($info['value'] ?? ''); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->arrival_info): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Chegada e estacionamento</h2>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                                <p class="text-gray-700 leading-relaxed"><?php echo nl2br(e($event->arrival_info)); ?></p>
                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($event->extra_services) && count($event->extra_services) > 0): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Serviços adicionais</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $event->extra_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $srv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-white rounded-xl shadow-sm border p-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="font-semibold text-gray-900"><?php echo e($srv['title'] ?? ''); ?></h3>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($srv['price'])): ?>
                                                <span class="text-emerald-700 font-medium">
                                                    <?php echo e(is_numeric($srv['price']) ? ('R$ ' . number_format((float) $srv['price'], 2, ',', '.')) : $srv['price']); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($srv['desc'])): ?>
                                            <p class="text-gray-600"><?php echo e($srv['desc']); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->terms): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Termos e condições</h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                <?php echo $event->terms; ?>

                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->rules): ?>
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Regras de participação</h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                <?php echo $event->rules; ?>

                            </div>
                        </section>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                
                <div class="lg:col-span-4">
                    <div class="sticky top-8 space-y-8">
                        
                        <!-- Card de Informações -->
                        <div class="bg-white rounded-2xl shadow-xl p-6 ring-1 ring-emerald-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Informações do Evento</h3>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium"><?php echo e($event->start_date->format('d/m/Y')); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo e($event->start_date->format('H:i')); ?>h</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium"><?php echo e($event->location); ?></p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->address): ?>
                                            <p class="text-sm text-gray-500"><?php echo e($event->address); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium whitespace-nowrap"><?php echo e($event->availableTickets()); ?> vagas</p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->capacity): ?>
                                            <p class="text-sm text-gray-500">de <?php echo e($event->capacity); ?> disponíveis</p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->price > 0): ?>
                                    <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium">R$ <?php echo e(number_format($event->price, 2, ',', '.')); ?></p>
                                            <p class="text-sm text-gray-500">por pessoa</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-green-600">Entrada Gratuita</p>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            
                            <!-- Botão de Ação -->
                            <div class="mt-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasTicket): ?>
                                    <a href="<?php echo e(route('events.my-tickets')); ?>" 
                                       class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Meus Ingressos
                                    </a>
                                <?php elseif($event->isSoldOut()): ?>
                                    <button disabled 
                                            class="w-full bg-gray-400 text-white px-6 py-3 rounded-xl font-semibold cursor-not-allowed">
                                        Ingressos Esgotados
                                    </button>
                                <?php elseif(!$event->isActive()): ?>
                                    <button disabled 
                                            class="w-full bg-gray-400 text-white px-6 py-3 rounded-xl font-semibold cursor-not-allowed">
                                        Evento Indisponível
                                    </button>
                                <?php else: ?>
                                    <a href="<?php echo e(route('events.purchase', $event)); ?>" 
                                       class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                        Garantir Vaga
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Compartilhar -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Compartilhar</h3>
                            <div class="flex space-x-3">
                                <button onclick="shareOnWhatsApp()" 
                                        class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.397.099-.099.173-.223.087-.347-.086-.124-.67-.52-.92-.622-.223-.099-.48-.016-.67.11-.173.149-1.19 1.135-1.19 2.763 0 1.627 1.19 2.4 1.355 2.564.149.149 2.395 3.646 5.81 5.095 3.414 1.45 3.414.991 4.035.94.62-.05 1.995-.806 2.277-1.585.281-.78.281-1.45.198-1.585-.087-.135-.32-.223-.617-.372zM12 2C6.477 2 2 6.477 2 12c0 1.821.487 3.53 1.338 5.016L2 22l4.983-1.338A9.973 9.973 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm5.446 13.485c-.116.275-.744 1.32-1.316 1.316-.572-.004-1.074-.275-2.04-.838-.765-.446-1.705-1.414-1.95-1.95-.244-.536-.488-1.074.028-2.04.517-.966 1.135-1.074 1.652-1.074.244 0 .488.028.698.198.116.116.275.372.116.698-.028.028-.116.116-.198.198-.116.116-.52.372-.116.698.404.326.93.838 1.074 1.042.144.204.028.372-.116.488z"/>
                                    </svg>
                                    WhatsApp
                                </button>
                                <button onclick="copyLink()" 
                                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    Copiar Link
                                </button>
                                <button onclick="toggleFavorite()" 
                                        class="flex-1 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    Favoritar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- CTA Final -->
        <div class="bg-emerald-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-4">Não Perca Este Momento!</h2>
                    <p class="text-emerald-100 mb-8 max-w-2xl mx-auto">
                        Garanta sua participação e faça parte desta experiência transformadora
                    </p>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasTicket): ?>
                        <a href="<?php echo e(route('events.my-tickets')); ?>" 
                           class="inline-flex items-center bg-white text-emerald-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-emerald-50 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Meus Ingressos
                        </a>
                    <?php elseif($event->isSoldOut()): ?>
                        <button disabled 
                                class="bg-gray-400 text-white px-8 py-4 rounded-xl font-semibold text-lg cursor-not-allowed">
                            Ingressos Esgotados
                        </button>
                    <?php elseif(!$event->isActive()): ?>
                        <button disabled 
                                class="bg-gray-400 text-white px-8 py-4 rounded-xl font-semibold text-lg cursor-not-allowed">
                            Evento Indisponível
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e(route('events.purchase', $event)); ?>" 
                           class="inline-flex items-center bg-white text-emerald-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-emerald-50 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            Garantir Minha Vaga Agora
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const imgs = <?php echo json_encode(array_map(fn($p) => Storage::disk('public')->url($p), array_filter($heroImages ?? [])), 512) ?>;
            const el = document.getElementById('hero-image');
            if (!el || !imgs || imgs.length <= 1) return;
            let idx = 0;
            setInterval(() => {
                idx = (idx + 1) % imgs.length;
                el.style.opacity = '0.2';
                setTimeout(() => {
                    el.src = imgs[idx];
                    el.style.opacity = '0.4';
                }, 300);
            }, 5000);
        })();
    </script>
        function shareOnWhatsApp() {
            const text = `Confira este evento: <?php echo e($event->name); ?> - <?php echo e(route('events.show', $event)); ?>`;
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }
        
        function copyLink() {
            const url = '<?php echo e(route('events.show', $event)); ?>';
            navigator.clipboard.writeText(url).then(function() {
                alert('Link copiado para a área de transferência!');
            }, function(err) {
                console.error('Erro ao copiar link: ', err);
            });
        }

        function toggleFavorite(){
            const key = `fav_event_<?php echo e($event->id); ?>`;
            const current = localStorage.getItem(key);
            if(current){
                localStorage.removeItem(key);
                alert('Evento removido dos favoritos');
            } else {
                localStorage.setItem(key, '1');
                alert('Evento adicionado aos favoritos');
            }
        }
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