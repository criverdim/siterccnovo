<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo e(config('app.name', 'RCC System')); ?> - <?php echo $__env->yieldContent('title', 'Sistema de Gestão RCC'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Sistema completo de gestão para RCC - Moderno, eficiente e profissional'); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.jsx']); ?>
    
    <!-- Livewire -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    
    <!-- Custom Styles -->
    <style>
        .font-inter { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .text-brand-green { color: #006036; }
        .text-brand-yellow { color: #fdc800; }
        .text-subtitle { font-size: 0.975rem; }
        @media (max-width: 768px) {
            .brand-title { font-size: 0.675rem; }
            .brand-subtitle { font-size: 0.525rem; }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-inter antialiased bg-gray-50 text-gray-900">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 md:h-[4.18rem] lg:h-[5.28rem] overflow-hidden">
                <!-- Logo -->
                <div class="flex items-center">
                    <?php ($logoUrl = data_get($siteSettings ?? [], 'brand_logo')); ?>
                    <a href="<?php echo e(url('/')); ?>" class="flex items-center space-x-2 md:space-x-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                            <img src="<?php echo e($logoUrl); ?>" alt="Logo" class="site-logo shrink-0 h-[1.7rem] md:h-[2.4rem] w-auto max-w-[110px] md:max-w-[100px] object-contain" />
                        <?php else: ?>
                            <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">RCC</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="block max-w-[38vw] md:max-w-[32vw] truncate whitespace-nowrap leading-tight md:leading-[1.0] overflow-hidden">
                            <span class="brand-title font-bold text-brand-green md:text-sm">Renovação Carismática Católica</span>
                            <p class="brand-subtitle text-brand-yellow md:text-xs -mt-0.5">Miguelópolis-SP</p>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="<?php echo e(url('/calendar')); ?>" class="text-gray-700 hover:text-blue-600 font-medium transition-colors" <?php if(request()->is('calendar*')): ?> aria-current="page" <?php endif; ?>>Calendário</a>
                    <a href="<?php echo e(route('events.index')); ?>" class="text-gray-700 hover:text-blue-600 font-medium transition-colors" <?php if(request()->is('events*')): ?> aria-current="page" <?php endif; ?>>Eventos</a>
                    <a href="<?php echo e(url('/groups')); ?>" class="text-gray-700 hover:text-blue-600 font-medium transition-colors" <?php if(request()->is('groups*')): ?> aria-current="page" <?php endif; ?>>Grupos</a>
                    <a href="<?php echo e(route('home')); ?>" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Início</a>
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600 font-medium">Mais</button>
                        <div class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="<?php echo e(route('sobre')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Sobre</a>
                            <a href="<?php echo e(route('servicos')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Serviços</a>
                            <a href="<?php echo e(route('contato')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Contato</a>
                        </div>
                    </div>
                    <a href="<?php echo e(url('/pastoreio')); ?>" class="text-gray-700 hover:text-blue-600 font-medium transition-colors" <?php if(request()->is('pastoreio*')): ?> aria-current="page" <?php endif; ?>>Pastoreio</a>
                    <a href="<?php echo e(route('register')); ?>" class="bg-emerald-600 text-white px-3.5 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-semibold shadow-sm" <?php if(request()->is('register')): ?> aria-current="page" <?php endif; ?>>Cadastro</a>
                    <a href="<?php echo e(route('login')); ?>" class="bg-yellow-400 text-gray-900 px-3.5 py-2 rounded-lg hover:bg-yellow-500 transition-colors font-semibold shadow-sm" <?php if(request()->is('login')): ?> aria-current="page" <?php endif; ?>>Login</a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-3">
                    <a href="<?php echo e(url('/calendar')); ?>" class="text-gray-700 hover:text-blue-600 font-medium py-2">Calendário</a>
                    <a href="<?php echo e(route('events.index')); ?>" class="text-gray-700 hover:text-blue-600 font-medium py-2">Eventos</a>
                    <a href="<?php echo e(url('/groups')); ?>" class="text-gray-700 hover:text-blue-600 font-medium py-2">Grupos</a>
                    <a href="<?php echo e(route('home')); ?>" class="text-gray-700 hover:text-blue-600 font-medium py-2">Início</a>
                    <div class="pt-2 border-t border-gray-200">
                        <div class="text-xs text-gray-500 mb-1 px-1">Mais</div>
                        <a href="<?php echo e(route('sobre')); ?>" class="text-gray-700 hover:text-blue-600 font-medium py-2">Sobre</a>
                        <a href="<?php echo e(route('servicos')); ?>" class="text-gray-700 hover:text-blue-600 font-medium py-2">Serviços</a>
                        <a href="<?php echo e(route('contato')); ?>" class="text-gray-700 hover:text-blue-600 font-medium py-2">Contato</a>
                    </div>
                    <a href="<?php echo e(route('register')); ?>" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-semibold text-center">Cadastro</a>
                    <a href="<?php echo e(route('login')); ?>" class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors font-semibold text-center">Login</a>
                    <a href="<?php echo e(url('/pastoreio')); ?>" class="text-gray-700 hover:text-blue-600 font-medium py-2 text-center">Pastoreio</a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <?php ($logoUrl = $logoUrl ?? data_get($siteSettings ?? [], 'brand_logo')); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                            <img src="<?php echo e($logoUrl); ?>" alt="Logo" class="site-logo shrink-0 h-10 md:h-12 w-auto max-w-[180px] object-contain" />
                        <?php else: ?>
                            <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">RCC</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div>
                            <span class="text-xl font-bold text-brand-green">Renovação Carismática Católica</span>
                            <p class="text-subtitle text-brand-yellow">Miguelópolis-SP</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4 max-w-md">
                        Sistema completo e moderno para gestão empresarial. 
                        Tecnologia de ponta para otimizar seus processos e melhorar a eficiência.
                    </p>
                    <div class="flex space-x-4">
                        <a href="<?php echo e(data_get($siteSettings, 'social.instagram', '#')); ?>" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm6.5-.75a1 1 0 110 2 1 1 0 010-2z"/></svg>
                        </a>
                        <a href="<?php echo e(data_get($siteSettings, 'social.facebook', '#')); ?>" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 10-11.5 9.95v-7.04H7.9V12h2.6V9.8c0-2.56 1.52-3.98 3.85-3.98 1.12 0 2.29.2 2.29.2v2.52h-1.29c-1.27 0-1.66.79-1.66 1.6V12h2.83l-.45 2.91h-2.38v7.04A10 10 0 0022 12z"/></svg>
                        </a>
                        <a href="<?php echo e(data_get($siteSettings, 'social.youtube', '#')); ?>" class="text-gray-400 hover:text-white transition-colors" aria-label="YouTube">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.18a3 3 0 00-2.1-2.12C19.4 3.5 12 3.5 12 3.5s-7.4 0-9.4.56A3 3 0 00.5 6.18 31.9 31.9 0 000 12c0 5.82.5 5.82.5 5.82a3 3 0 002.1 2.12c2 .56 9.4.56 9.4.56s7.4 0 9.4-.56a3 3 0 002.1-2.12c.5-2 .5-5.82.5-5.82s0-3.82-.5-5.82zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="<?php echo e(url('/')); ?>" class="text-gray-300 hover:text-white transition-colors">Início</a></li>
                        <li><a href="<?php echo e(url('/sobre')); ?>" class="text-gray-300 hover:text-white transition-colors">Sobre Nós</a></li>
                        <li><a href="<?php echo e(url('/servicos')); ?>" class="text-gray-300 hover:text-white transition-colors">Serviços</a></li>
                        <li><a href="<?php echo e(url('/contato')); ?>" class="text-gray-300 hover:text-white transition-colors">Contato</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contato</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                            <span><?php echo e(data_get($siteSettings, 'site.email')); ?></span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            <span><?php echo e(data_get($siteSettings, 'site.phone')); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo e(date('Y')); ?> RCC System. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    
    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/html/rcc-system/resources/views/layouts/app.blade.php ENDPATH**/ ?>