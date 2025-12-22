<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="RCC • Evangelizar com beleza, simplicidade e organização">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($og)): ?>
        <meta property="og:title" content="<?php echo e($og['title']); ?>" />
        <meta property="og:description" content="<?php echo e($og['description']); ?>" />
        <meta property="og:image" content="<?php echo e($og['image']); ?>" />
        <meta property="og:url" content="<?php echo e($og['url']); ?>" />
        <meta property="og:type" content="article" />
        <meta name="twitter:card" content="summary_large_image" />
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <meta name="theme-color" content="#0b7a48">
    <title><?php echo e($title ?? 'RCC'); ?></title>
    <?php ($viteManifest = public_path('build/manifest.json')); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists($viteManifest)): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.jsx']); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <style>
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
        .sr-only:focus, .sr-only:focus-visible { position: static; width: auto; height: auto; margin: 0; overflow: visible; clip: auto; white-space: normal; padding: .5rem .75rem; display: inline-block; background: #eef2ff; border-radius: .375rem; }
        .gold { color: #c9a043; }
        .bg-gold { background-color: #c9a043; }
        .text-brand-green { color: #006036; }
        .text-brand-yellow { color: #fdc800; }
        .brand-title { font-weight: 700; }
        .brand-subtitle { font-size: 0.975rem; }
        @media (max-width: 768px) {
            .brand-title { font-size: 0.675rem; }
            .brand-subtitle { font-size: 0.525rem; }
        }
        .nav-link { position: relative; display:inline-flex; align-items:center; gap:.5rem; padding:.5rem .75rem; border-radius:.625rem; font-weight:600; color:#374151; transition: all .2s ease-in-out; white-space:nowrap; font-size:.875rem; }
        .nav-link:hover { color:#0b7a48; }
        .nav-link:focus-visible { outline:none; box-shadow:0 0 0 3px rgba(16,185,129,.45); }
        .nav-link-active { color:#006036; }
        .nav-link-active::after { content:""; position:absolute; left:.5rem; right:.5rem; bottom:-.2rem; height:2px; background:#0b7a48; border-radius:2px; }
        .nav-group { display:flex; align-items:center; justify-content:center; gap:1.5rem; }
        .nav-chip { display:inline-flex; align-items:center; gap:.5rem; padding:.5rem .75rem; border-radius:.625rem; font-weight:600; color:#374151; background:#f8fafc; border:1px solid #e5e7eb; transition: all .2s ease-in-out; white-space:nowrap; font-size:.875rem; }
        .nav-chip:hover { background:#eef2ff; color:#0b7a48; border-color:#d1d5db; }
        .nav-caret { margin-left:.5rem; font-size:10px; color:#6b7280; }
        .nav-icon { color:#0b7a48; }
        .nav-chip:focus-visible { outline:none; box-shadow:0 0 0 3px rgba(16,185,129,.45); }
        .menu-panel { transition: opacity .2s ease-in-out, transform .2s ease-in-out; }
        .menu-panel[data-open="true"] { opacity:1; visibility:visible; transform: translateY(0); }
        .menu-panel[data-open="false"] { opacity:0; visibility:hidden; transform: translateY(-4px); }
        @media (max-width: 768px) {
            .nav-link { padding:.625rem .75rem; }
            .nav-group { gap:1rem; }
        }
    </style>
</head>
<body class="bg-white text-gray-900">
    <?php ($siteSettings = $siteSettings ?? (function(){ try { return app(\App\Services\SiteSettings::class)->all(); } catch (\Throwable $e) { return []; } })()); ?>
    <a href="#main" class="sr-only focus-ring">Ir para conteúdo principal</a>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($minimal) || !$minimal): ?>
    <header class="bg-white/95 backdrop-blur-sm shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 md:h-[4.18rem] lg:h-[5.28rem] overflow-visible">
                <div class="flex items-center">
                    <?php ($logoUrl = data_get($siteSettings ?? [], 'brand_logo')); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $logoUrl): ?>
                        <?php ($hasSettings = \Illuminate\Support\Facades\Schema::hasTable('settings')); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSettings): ?>
                            <?php ($brand = \App\Models\Setting::where('key','brand')->first()); ?>
                            <?php ($path = data_get($brand?->value ?? [], 'logo')); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($path): ?>
                                <?php ($base = (string) config('filesystems.disks.public.url')); ?>
                                <?php ($logoUrl = $base ? rtrim($base,'/').'/'.ltrim($path,'/') : '/storage/'.ltrim($path,'/')); ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <a href="<?php echo e(url('/')); ?>" class="flex items-center space-x-2 md:space-x-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                            <img src="<?php echo e($logoUrl); ?>" alt="Logo" class="site-logo shrink-0 h-[1.7rem] md:h-[2.4rem] w-auto max-w-[110px] md:max-w-[100px] object-contain" />
                        <?php else: ?>
                            <div class="w-10 h-10 bg-gold rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">RCC</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="block max-w-[38vw] md:max-w-[32vw] truncate whitespace-nowrap leading-tight md:leading-[1.0] overflow-hidden">
                            <span class="brand-title text-brand-green md:text-sm">Renovação Carismática Católica</span>
                            <p class="brand-subtitle text-brand-yellow -mt-0.5">Miguelópolis-SP</p>
                        </div>
                    </a>
                </div>
                <nav class="hidden md:flex flex-1 justify-center" role="navigation" aria-label="Navegação principal">
                    <div class="nav-group">
                        <a href="<?php echo e(route('home')); ?>" class="nav-link <?php if(request()->routeIs('home')): ?> nav-link-active <?php endif; ?>" <?php if(request()->routeIs('home')): ?> aria-current="page" <?php endif; ?>><i class="fa fa-house"></i>Início</a>
                        <a href="<?php echo e(route('events.index')); ?>" class="nav-link <?php if(request()->is('events*')): ?> nav-link-active <?php endif; ?>" <?php if(request()->is('events*')): ?> aria-current="page" <?php endif; ?>><i class="fa fa-calendar-days"></i>Eventos</a>
                        <a href="<?php echo e(url('/groups')); ?>" class="nav-link <?php if(request()->is('groups*')): ?> nav-link-active <?php endif; ?>" <?php if(request()->is('groups*')): ?> aria-current="page" <?php endif; ?>><i class="fa fa-people-group"></i>Grupos</a>
                        <a href="<?php echo e(url('/calendar')); ?>" class="nav-link <?php if(request()->is('calendar*')): ?> nav-link-active <?php endif; ?>" <?php if(request()->is('calendar*')): ?> aria-current="page" <?php endif; ?>><i class="fa fa-calendar"></i>Calendário</a>
                    </div>
                    <div class="relative">
                        <button id="btn-mais" class="nav-link" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="menu-mais"><i class="fa fa-ellipsis nav-icon"></i><span>Mais</span><i class="fas fa-chevron-down nav-caret"></i></button>
                        <div id="menu-mais" class="absolute top-full left-0 mt-2 w-56 bg-white rounded-xl shadow-lg border menu-panel z-50" role="menu" aria-labelledby="btn-mais" data-open="false">
                            <a href="<?php echo e(route('sobre')); ?>" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Sobre</a>
                            <a href="<?php echo e(route('servicos')); ?>" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Serviços</a>
                            <a href="<?php echo e(route('contato')); ?>" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Contato</a>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!auth()->check()): ?>
                        <span class="inline-flex items-center text-emerald-700">
                            <i class="fa fa-user mr-2"></i>
                            Visitante
                        </span>
                        <a href="<?php echo e(route('register')); ?>" class="bg-emerald-600 text-white px-3.5 py-2 rounded-lg hover:bg-emerald-700 transition-all duration-200 font-semibold shadow-sm" <?php if(request()->is('register')): ?> aria-current="page" <?php endif; ?>>Cadastro</a>
                        <a href="<?php echo e(route('login')); ?>" class="bg-yellow-400 text-gray-900 px-3.5 py-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 font-semibold shadow-sm" <?php if(request()->is('login')): ?> aria-current="page" <?php endif; ?>>Login</a>
                    <?php else: ?>
                        <?php ($firstName = explode(' ', auth()->user()->name)[0]); ?>
                        <div class="relative group">
                            <button id="btn-user" class="nav-chip" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="menu-user"><i class="fa fa-user-circle nav-icon"></i><span class="truncate max-w-[160px]"><?php echo e($firstName); ?></span><i class="fas fa-chevron-down nav-caret"></i></button>
                            <div id="menu-user" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-lg border menu-panel z-50" role="menu" aria-labelledby="btn-user" data-open="false">
                                <a href="<?php echo e(route('events.my-tickets')); ?>" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Meus Ingressos</a>
                                <form method="POST" action="<?php echo e(url('/logout')); ?>" class="border-t">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-emerald-700 hover:bg-emerald-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <?php ($canPastoreio = auth()->user()?->canAccessPage('/pastoreio')); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canPastoreio): ?>
                        <div class="relative">
                            <button id="btn-pastoreio" class="nav-link" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="menu-pastoreio"><i class="fa fa-heart nav-icon"></i><span>Pastoreio</span><i class="fas fa-chevron-down nav-caret"></i></button>
                            <div id="menu-pastoreio" class="absolute top-full left-0 mt-2 w-56 bg-white rounded-xl shadow-lg border menu-panel z-50" role="menu" aria-labelledby="btn-pastoreio" data-open="false">
                                <a href="/pastoreio" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-t-xl">Painel de Controle</a>
                                <a href="/pastoreio" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Controle de Frequência</a>
                                <a href="/pastoreio" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-b-xl">Sorteio de Grupos</a>
                            </div>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </nav>
                <button id="menuBtn" class="md:hidden p-2 rounded border" aria-controls="mobileMenu" aria-expanded="false"><i class="fa fa-bars"></i><span class="sr-only">Abrir menu</span></button>
            </div>
            <div id="mobileMenu" class="md:hidden hidden mt-3" role="navigation" aria-label="Navegação principal" aria-hidden="true">
                <div class="grid gap-2">
                    <a href="<?php echo e(route('home')); ?>" class="block p-3 rounded-lg hover:bg-emerald-50 font-semibold" <?php if(request()->routeIs('home')): ?> aria-current="page" <?php endif; ?>>Início</a>
                    <a href="<?php echo e(route('events.index')); ?>" class="block p-3 rounded-lg hover:bg-emerald-50 font-semibold" <?php if(request()->is('events*')): ?> aria-current="page" <?php endif; ?>>Eventos</a>
                    <a href="<?php echo e(url('/groups')); ?>" class="block p-3 rounded-lg hover:bg-emerald-50 font-semibold" <?php if(request()->is('groups*')): ?> aria-current="page" <?php endif; ?>>Grupos</a>
                    <a href="<?php echo e(url('/calendar')); ?>" class="block p-3 rounded-lg hover:bg-emerald-50 font-semibold" <?php if(request()->is('calendar*')): ?> aria-current="page" <?php endif; ?>>Calendário</a>
                    <div class="border-t border-gray-200 pt-2">
                        <p class="text-sm text-gray-500 px-2 mb-1">Mais</p>
                        <a href="<?php echo e(route('sobre')); ?>" class="block p-2 rounded-lg hover:bg-emerald-50 text-sm ml-2">Sobre</a>
                        <a href="<?php echo e(route('servicos')); ?>" class="block p-2 rounded-lg hover:bg-emerald-50 text-sm ml-2">Serviços</a>
                        <a href="<?php echo e(route('contato')); ?>" class="block p-2 rounded-lg hover:bg-emerald-50 text-sm ml-2">Contato</a>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!auth()->check()): ?>
                        <div class="flex items-center p-2 text-emerald-700"><i class="fa fa-user mr-2"></i>Visitante</div>
                        <a href="<?php echo e(route('register')); ?>" class="block p-2 rounded bg-emerald-600 text-white">Cadastro</a>
                        <a href="<?php echo e(route('login')); ?>" class="block p-2 rounded bg-yellow-400 text-gray-900">Login</a>
                    <?php else: ?>
                        <div class="flex items-center p-2 text-emerald-700"><i class="fa fa-user-check mr-2"></i><?php echo e(auth()->user()->name); ?></div>
                        <form method="POST" action="<?php echo e(url('/logout')); ?>" class="p-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full block p-2 rounded border-2 border-emerald-600 text-emerald-700 hover:bg-emerald-600 hover:text-white">Logout</button>
                        </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <?php ($canPastoreio = auth()->user()?->canAccessPage('/pastoreio')); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canPastoreio): ?>
                        <div class="border-t border-gray-200 pt-2">
                            <p class="text-sm text-gray-500 px-2 mb-1">Pastoreio</p>
                            <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Painel de Controle</a>
                            <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Controle de Frequência</a>
                            <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Sorteio de Grupos</a>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <main id="main" class="min-h-screen fade-in">
        <?php echo e($slot); ?>

    </main>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((empty($minimal) || !$minimal) && (empty($hideFooter) || !$hideFooter)): ?>
    <footer class="mt-16 border-t">
        <div class="max-w-7xl mx-auto p-6 grid md:grid-cols-3 gap-6">
            <div>
                <div class="text-emerald-700 font-semibold mb-2">Contato</div>
                <div class="text-sm text-gray-700">Endereço: <?php echo e(data_get($siteSettings, 'site.address')); ?></div>
                <div class="text-sm text-gray-700">Telefone: <?php echo e(data_get($siteSettings, 'site.phone')); ?></div>
                <div class="text-sm text-gray-700">WhatsApp: <?php echo e(data_get($siteSettings, 'site.whatsapp')); ?></div>
            </div>
            <div>
                <div class="text-emerald-700 font-semibold mb-2">Redes</div>
                <div class="flex items-center gap-4 text-2xl">
                    <a href="<?php echo e(data_get($siteSettings, 'social.instagram', '#')); ?>" class="text-emerald-700 hover:gold"><i class="fab fa-instagram" aria-label="Instagram"></i></a>
                    <a href="<?php echo e(data_get($siteSettings, 'social.facebook', '#')); ?>" class="text-emerald-700 hover:gold"><i class="fab fa-facebook" aria-label="Facebook"></i></a>
                    <a href="<?php echo e(data_get($siteSettings, 'social.youtube', '#')); ?>" class="text-emerald-700 hover:gold"><i class="fab fa-youtube" aria-label="YouTube"></i></a>
                </div>
            </div>
            <div>
                <div class="text-emerald-700 font-semibold mb-2">Links Rápidos</div>
                <div class="grid gap-2 text-sm">
                    <a href="/events" class="hover:text-emerald-700">Próximos Eventos</a>
                    <a href="/groups" class="hover:text-emerald-700">Grupos de Oração</a>
                    <a href="/register" class="hover:text-emerald-700">Cadastro</a>
                </div>
            </div>
        </div>
        <div class="p-4 text-center text-xs text-gray-500">© <?php echo e(date('Y')); ?> RCC • Excelência visual e simplicidade</div>
    </footer>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <script>
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (menuBtn) menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const expanded = menuBtn.getAttribute('aria-expanded') === 'true';
            menuBtn.setAttribute('aria-expanded', (!expanded).toString());
            mobileMenu.setAttribute('aria-hidden', expanded ? 'true' : 'false');
            if (!expanded) {
                const firstLink = mobileMenu.querySelector('a, button');
                if (firstLink) firstLink.focus();
            }
        });

        const closeMenus = () => {
            document.querySelectorAll('.menu-panel').forEach(panel => {
                panel.dataset.open = 'false';
            });
            document.querySelectorAll('[aria-haspopup="true"]').forEach(btn => btn.setAttribute('aria-expanded','false'));
        };

        const initMenuButton = (btnId, menuId) => {
            const btn = document.getElementById(btnId);
            const menu = document.getElementById(menuId);
            if (!btn || !menu) return;
            const items = Array.from(menu.querySelectorAll('[role="menuitem"]'));
            btn.addEventListener('click', () => {
                const open = btn.getAttribute('aria-expanded') === 'true';
                closeMenus();
                btn.setAttribute('aria-expanded', (!open).toString());
                menu.dataset.open = (!open).toString();
                if (!open && items.length) items[0].focus();
            });
            btn.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') { e.preventDefault(); if (items.length) { btn.setAttribute('aria-expanded','true'); menu.dataset.open = 'true'; items[0].focus(); } }
                if (e.key === 'ArrowUp') { e.preventDefault(); if (items.length) { btn.setAttribute('aria-expanded','true'); menu.dataset.open = 'true'; items[items.length - 1].focus(); } }
                if (e.key === 'Escape') { closeMenus(); btn.focus(); }
            });
            menu.addEventListener('keydown', (e) => {
                const idx = items.indexOf(document.activeElement);
                if (e.key === 'ArrowDown') { e.preventDefault(); const next = idx < items.length - 1 ? idx + 1 : 0; items[next].focus(); }
                if (e.key === 'ArrowUp') { e.preventDefault(); const prev = idx > 0 ? idx - 1 : items.length - 1; items[prev].focus(); }
                if (e.key === 'Home') { e.preventDefault(); items[0].focus(); }
                if (e.key === 'End') { e.preventDefault(); items[items.length - 1].focus(); }
                if (e.key === 'Escape' || e.key === 'Tab') { closeMenus(); }
            });
        };

        initMenuButton('btn-mais','menu-mais');
        initMenuButton('btn-pastoreio','menu-pastoreio');
        initMenuButton('btn-user','menu-user');

        document.addEventListener('click', (e) => {
            const isButton = e.target.closest('[aria-haspopup="true"]');
            const isPanel = e.target.closest('.menu-panel');
            if (!isButton && !isPanel) closeMenus();
        });
        mobileMenu && mobileMenu.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                mobileMenu.classList.add('hidden');
                menuBtn.setAttribute('aria-expanded','false');
                mobileMenu.setAttribute('aria-hidden','true');
                menuBtn.focus();
            }
        });
    </script>
</body>
</html>
<?php /**PATH /var/www/html/rcc-system/resources/views/components/layouts/app.blade.php ENDPATH**/ ?>