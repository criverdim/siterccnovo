<?php $__env->startSection('title', 'Sobre Nós'); ?>
<?php $__env->startSection('description', 'Conheça a história e os valores do RCC System - Sua parceria em gestão empresarial'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-6xl font-bold mb-6">Sobre a RCC Miguelópolis-SP</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">Conheça nossa história, missão e como participar dos grupos de oração</p>
    </div>
</section>

<?php ($coordinators = (\Illuminate\Support\Facades\Schema::hasTable('users') && \Illuminate\Support\Facades\Schema::hasColumn('users','is_coordinator') && \Illuminate\Support\Facades\Schema::hasColumn('users','status') && \Illuminate\Support\Facades\Schema::hasColumn('users','coordinator_ministry_id')) ? \App\Models\User::with(['coordinatorMinistry','activePhoto'])->where('status','active')->where('is_coordinator',true)->orderBy('name')->get(['id','name','status','coordinator_ministry_id']) : collect()); ?>

<!-- Company Story -->
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-center">
            <div class="animate-fade-in">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                    Nossa <span class="gradient-text">História</span>
                </h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        A Renovação Carismática Católica (RCC) é uma corrente de graça na Igreja que promove
                        a experiência do Batismo no Espírito Santo e a vivência comunitária por meio dos grupos de oração.
                    </p>
                    <p>
                        Em Miguelópolis-SP, nossa missão é evangelizar, formar servos e sustentar a vida de oração
                        com encontros semanais, ministérios de serviço e eventos diocesanos.
                    </p>
                    <p>
                        Junte-se a nós nos grupos de oração e participe dos ministérios — há espaço para todos
                        que desejam servir e crescer na fé.
                    </p>
                </div>
                
                <!-- Stats -->
                <?php ($groupsCount = \Illuminate\Support\Facades\Schema::hasTable('groups') ? \App\Models\Group::count() : 0); ?>
                <?php ($servosCount = (\Illuminate\Support\Facades\Schema::hasTable('users') && \Illuminate\Support\Facades\Schema::hasColumn('users','is_servo') && \Illuminate\Support\Facades\Schema::hasColumn('users','status')) ? \App\Models\User::where('is_servo', true)->where('status', 'active')->count() : 0); ?>
                <?php ($eventsCount = (\Illuminate\Support\Facades\Schema::hasTable('events') && \Illuminate\Support\Facades\Schema::hasColumn('events','is_active')) ? \App\Models\Event::where('is_active', true)->count() : 0); ?>
                <div class="grid grid-cols-3 gap-5 mt-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2"><?php echo e($groupsCount); ?></div>
                        <div class="text-gray-600">Grupos de Oração</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2"><?php echo e($servosCount); ?></div>
                        <div class="text-gray-600">Servos Ativos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-600 mb-2"><?php echo e($eventsCount); ?></div>
                        <div class="text-gray-600">Eventos Ativos</div>
                    </div>
                </div>
            </div>
            
            <div class="animate-fade-in lg:delay-200">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-3xl p-8 lg:p-12">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="bg-white rounded-2xl p-6 text-center shadow-lg">
                            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Evangelização</h3>
                            <p class="text-sm text-gray-600">Anunciar Jesus no poder do Espírito</p>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 text-center shadow-lg">
                            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Comunhão</h3>
                            <p class="text-sm text-gray-600">Vida fraterna nos grupos de oração</p>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 text-center shadow-lg">
                            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Serviço</h3>
                            <p class="text-sm text-gray-600">Ministérios a serviço da Igreja</p>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 text-center shadow-lg">
                            <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Formação</h3>
                            <p class="text-sm text-gray-600">Capacitação de servos e líderes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Mission -->
            <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-lg hover-lift transition-all">
                <div class="w-20 h-20 bg-blue-100 rounded-3xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">Nossa Missão</h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Promover a experiência do Batismo no Espírito Santo, fortalecer a vida de oração
                    e formar servos e líderes para servir a Igreja em unidade e comunhão.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Evangelizar por meio dos grupos de oração
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Formar servos e ministérios de serviço
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Sustentar a vida de oração e comunhão
                    </li>
                </ul>
            </div>
            
            <!-- Vision -->
            <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-lg hover-lift transition-all">
                <div class="w-20 h-20 bg-purple-100 rounded-3xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">Nossa Visão</h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Servir a Igreja com fidelidade, vivendo a unidade e a docilidade ao Espírito Santo,
                    sendo sinais de esperança por meio dos grupos de oração e ministérios.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Unidade e comunhão eclesial
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Vida de oração e formação contínua
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Serviço aos irmãos nos ministérios
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coordinators->count()): ?>
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4 mb-12">
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Nossa <span class="gradient-text">Equipe</span></h2>
            <h3 class="text-3xl lg:text-5xl font-bold text-gray-900">Coordenadores</h3>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Servos responsáveis pelos ministérios</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coordinators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center group">
                    <?php ($initials = collect(explode(' ', trim((string) $coord->name)))->filter()->map(fn ($p) => \Illuminate\Support\Str::of($p)->substr(0, 1))->take(2)->implode('')); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coord->activePhoto()->exists()): ?>
                        <img src="<?php echo e($coord->profile_photo_url); ?>" alt="<?php echo e($coord->name); ?>" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <?php else: ?>
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-2xl font-bold group-hover:scale-105 transition-transform">
                            <?php echo e($initials); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <h3 class="text-xl font-bold text-gray-900 mb-1"><?php echo e(\Illuminate\Support\Str::title($coord->name)); ?></h3>
                    <?php ($ministry = optional($coord->coordinatorMinistry)->name); ?>
                    <?php ($m = \Illuminate\Support\Str::lower($ministry ?? '')); ?>
                    <?php ($emoji = '✨'); ?>
                    <?php ($emoji = str_contains($m, 'comunica') ? '📣' : $emoji); ?>
                    <?php ($emoji = (str_contains($m, 'mús') || str_contains($m, 'mus')) ? '🎵' : $emoji); ?>
                    <?php ($emoji = str_contains($m, 'intercess') ? '🙏' : $emoji); ?>
                    <?php ($emoji = str_contains($m, 'acolh') ? '🤝' : $emoji); ?>
                    <?php ($emoji = (str_contains($m, 'danç') || str_contains($m, 'danc')) ? '💃' : $emoji); ?>
                    <div class="mb-2 text-sm">
                        <span class="mr-1"><?php echo e($emoji); ?></span>
                        <span class="gold font-medium"><?php echo e($ministry); ?></span>
                    </div>
                    <p class="text-gray-600 text-sm">Coordenador(a) de ministério</p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- CTA Section -->
<section class="py-16 lg:py-24 gradient-bg text-white">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl lg:text-5xl font-bold mb-6">
            Quer participar da RCC Miguelópolis?
        </h2>
        <p class="text-xl mb-8 text-blue-100 max-w-2xl mx-auto">
            Participe de um grupo de oração ou fale com a coordenação para servir em um ministério.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(url('/groups')); ?>" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition-all hover-lift inline-flex items-center justify-center">
                Encontrar Grupo de Oração
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
            <a href="<?php echo e(url('/contato')); ?>" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all inline-flex items-center justify-center">
                Falar com a Coordenação
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/rcc-system/resources/views/sobre.blade.php ENDPATH**/ ?>