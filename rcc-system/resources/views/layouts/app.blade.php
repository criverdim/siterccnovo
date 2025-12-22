<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'RCC System') }} - @yield('title', 'Sistema de Gestão RCC')</title>
    <meta name="description" content="@yield('description', 'Sistema completo de gestão para RCC - Moderno, eficiente e profissional')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    
    <!-- Scripts -->
    @php($viteManifest = public_path('build/manifest.json'))
    @if(file_exists($viteManifest))
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @endif
    
    <!-- Livewire -->
    @if(\Illuminate\Support\Facades\Route::has('livewire.update'))
        @livewireStyles
    @endif
    
    <!-- Custom Styles -->
    <style>
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
        .sr-only:focus, .sr-only:focus-visible { position: static; width: auto; height: auto; margin: 0; overflow: visible; clip: auto; white-space: normal; padding: .5rem .75rem; display: inline-block; background: #eef2ff; border-radius: .375rem; }
        .nav-link { position: relative; display:inline-flex; align-items:center; gap:.5rem; padding:.5rem .75rem; border-radius:.625rem; font-weight:600; color:#374151; transition: all .2s ease-in-out; white-space:nowrap; font-size:.875rem; }
        .nav-link:hover { color:#0b7a48; }
        .nav-link:focus-visible { outline:none; box-shadow:0 0 0 3px rgba(16,185,129,.45); }
        .nav-link-active { color:#006036; }
        .nav-link-active::after { content:""; position:absolute; left:.5rem; right:.5rem; bottom:-.2rem; height:2px; background:#0b7a48; border-radius:2px; }
        .nav-group { display:flex; align-items:center; justify-content:center; gap:1.5rem; }
        .nav-chip { display:inline-flex; align-items:center; gap:.5rem; padding:.5rem .75rem; border-radius:.625rem; font-weight:600; color:#374151; background:#f8fafc; border:1px solid #e5e7eb; transition: all .2s ease-in-out; white-space:nowrap; font-size:.875rem; }
        .nav-chip:hover { background:#eef2ff; color:#0b7a48; border-color:#d1d5db; }
        .nav-caret { margin-left:.5rem; font-size:10px; color:#6b7280; }
        .nav-chip:focus-visible { outline:none; box-shadow:0 0 0 3px rgba(16,185,129,.45); }
        .menu-panel { transition: opacity .2s ease-in-out, transform .2s ease-in-out; }
        .menu-panel[data-open="true"] { opacity:1; visibility:visible; transform: translateY(0); }
        .menu-panel[data-open="false"] { opacity:0; visibility:hidden; transform: translateY(-4px); }
        .font-inter { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .gold { color: #c9a043; }
        .bg-gold { background-color: #c9a043; }
        .text-brand-green { color: #006036; }
        .text-brand-yellow { color: #fdc800; }
        .brand-title { font-weight: 700; }
        .brand-subtitle { font-size: 0.975rem; }
        .text-subtitle { font-size: 0.975rem; }
        @media (max-width: 768px) {
            .brand-title { font-size: 0.675rem; }
            .brand-subtitle { font-size: 0.525rem; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-inter antialiased bg-gray-50 text-gray-900">
    @php($siteSettings = $siteSettings ?? (function(){ try { return app(\App\Services\SiteSettings::class)->all(); } catch (\Throwable $e) { return []; } })())
    <a href="#main" class="sr-only">Ir para conteúdo principal</a>
    <header class="bg-white/95 backdrop-blur-sm shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 md:h-[4.18rem] lg:h-[5.28rem] overflow-visible">
                <div class="flex items-center">
                    @php($logoUrl = data_get($siteSettings ?? [], 'brand_logo'))
                    @if(! $logoUrl)
                        @php($hasSettings = \Illuminate\Support\Facades\Schema::hasTable('settings'))
                        @if($hasSettings)
                            @php($brand = \App\Models\Setting::where('key','brand')->first())
                            @php($path = data_get($brand?->value ?? [], 'logo'))
                            @if($path)
                                @php($base = (string) config('filesystems.disks.public.url'))
                                @php($logoUrl = $base ? rtrim($base,'/').'/'.ltrim($path,'/') : '/storage/'.ltrim($path,'/'))
                            @endif
                        @endif
                    @endif
                    <a href="{{ url('/') }}" class="flex items-center space-x-2 md:space-x-2">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo" class="site-logo shrink-0 h-[1.7rem] md:h-[2.4rem] w-auto max-w-[110px] md:max-w-[100px] object-contain" />
                        @else
                            <div class="w-10 h-10 bg-gold rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">RCC</span>
                            </div>
                        @endif
                        <div class="block max-w-[38vw] md:max-w-[32vw] truncate whitespace-nowrap leading-tight md:leading-[1.0] overflow-hidden">
                            <span class="brand-title text-brand-green md:text-sm">Renovação Carismática Católica</span>
                            <p class="brand-subtitle text-brand-yellow -mt-0.5">Miguelópolis-SP</p>
                        </div>
                    </a>
                </div>
                <nav class="hidden md:flex items-center gap-6" role="navigation" aria-label="Navegação principal">
                    <div class="nav-group">
                        <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) nav-link-active @endif" @if(request()->routeIs('home')) aria-current="page" @endif><i class="fa fa-house"></i>Início</a>
                        <a href="{{ route('events.index') }}" class="nav-link @if(request()->is('events*')) nav-link-active @endif" @if(request()->is('events*')) aria-current="page" @endif><i class="fa fa-calendar-days"></i>Eventos</a>
                        <a href="{{ url('/groups') }}" class="nav-link @if(request()->is('groups*')) nav-link-active @endif" @if(request()->is('groups*')) aria-current="page" @endif><i class="fa fa-people-group"></i>Grupos</a>
                        <a href="{{ url('/calendar') }}" class="nav-link @if(request()->is('calendar*')) nav-link-active @endif" @if(request()->is('calendar*')) aria-current="page" @endif><i class="fa fa-calendar"></i>Calendário</a>
                    </div>
                    <div class="relative">
                        <button id="btn-mais" class="nav-link" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="menu-mais"><i class="fa fa-ellipsis"></i><span>Mais</span><i class="fas fa-chevron-down nav-caret"></i></button>
                        <div id="menu-mais" class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border menu-panel z-50" role="menu" aria-labelledby="btn-mais" data-open="false">
                            <a href="{{ route('sobre') }}" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Sobre</a>
                            <a href="{{ route('servicos') }}" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Serviços</a>
                            <a href="{{ route('contato') }}" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Contato</a>
                        </div>
                    </div>
                    @auth
                        <div class="relative">
                            @php($firstName = explode(' ', auth()->user()->name)[0])
                            <button id="btn-user" class="nav-chip" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="menu-user"><i class="fas fa-user"></i><span class="truncate max-w-[160px]">{{ $firstName }}</span><i class="fas fa-chevron-down nav-caret"></i></button>
                            <div id="menu-user" class="absolute top-full right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border menu-panel z-50" role="menu" aria-labelledby="btn-user" data-open="false">
                                <a href="{{ route('events.my-tickets') }}" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50"><i class="fas fa-ticket-alt mr-2"></i>Meus Ingressos</a>
                                <a href="{{ route('area.membro') }}" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50"><i class="fas fa-user-cog mr-2"></i>Minha Conta</a>
                                <div class="border-t border-gray-200"></div>
                                <form action="/logout" method="post" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50"><i class="fas fa-sign-out-alt mr-2"></i>Sair</button>
                                </form>
                            </div>
                        </div>
                    @else
                        @php($hasRegister = \Illuminate\Support\Facades\Route::has('register'))
                        @php($hasLogin = \Illuminate\Support\Facades\Route::has('login'))
                        <a href="{{ $hasRegister ? route('register') : url('/register') }}" class="bg-emerald-600 text-white px-3.5 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-semibold shadow-sm" @if(request()->is('register')) aria-current="page" @endif>Cadastro</a>
                        <a href="{{ $hasLogin ? route('login') : url('/login') }}" class="bg-yellow-400 text-gray-900 px-3.5 py-2 rounded-lg hover:bg-yellow-500 transition-colors font-semibold shadow-sm" @if(request()->is('login')) aria-current="page" @endif>Login</a>
                    @endauth
                    @auth
                        @php($canPastoreio = auth()->user()?->canAccessPage('/pastoreio'))
                        @if($canPastoreio)
                        <div class="relative">
                            <button id="btn-pastoreio" class="nav-link" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="menu-pastoreio"><span>Pastoreio</span><i class="fas fa-chevron-down nav-caret"></i></button>
                            <div id="menu-pastoreio" class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border menu-panel z-50" role="menu" aria-labelledby="btn-pastoreio" data-open="false">
                                <a href="/pastoreio" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-t-lg">Painel de Controle</a>
                                <a href="/pastoreio" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Controle de Frequência</a>
                                <a href="/pastoreio" role="menuitem" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-b-lg">Sorteio de Grupos</a>
                            </div>
                        </div>
                        @endif
                    @endauth
                </nav>
                <button id="menuBtn" class="md:hidden p-2 rounded border" aria-controls="mobileMenu" aria-expanded="false"><i class="fa fa-bars"></i><span class="sr-only">Abrir menu</span></button>
            </div>
            <div id="mobileMenu" class="md:hidden hidden mt-3" role="navigation" aria-label="Navegação principal" aria-hidden="true">
                <div class="grid gap-2">
                    <a href="{{ url('/calendar') }}" class="block p-2 rounded hover:bg-emerald-50">Calendário</a>
                    <a href="{{ route('events.index') }}" class="block p-2 rounded hover:bg-emerald-50">Eventos</a>
                    <a href="{{ url('/groups') }}" class="block p-2 rounded hover:bg-emerald-50">Grupos</a>
                    <a href="{{ route('home') }}" class="block p-2 rounded hover:bg-emerald-50">Início</a>
                    <div class="border-t border-gray-200 pt-2">
                        <p class="text-sm text-gray-500 px-2 mb-1">Mais</p>
                        <a href="{{ route('sobre') }}" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Sobre</a>
                        <a href="{{ route('servicos') }}" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Serviços</a>
                        <a href="{{ route('contato') }}" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Contato</a>
                    </div>
                    @auth
                        <div class="border-t border-gray-200 pt-2">
                            <p class="text-sm text-gray-500 px-2 mb-1">Minha Conta</p>
                            <a href="{{ route('events.my-tickets') }}" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">
                                <i class="fas fa-ticket-alt mr-2"></i>Meus Ingressos
                            </a>
                            <a href="{{ route('area.membro') }}" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">
                                <i class="fas fa-user-cog mr-2"></i>Minha Conta
                            </a>
                            <form action="/logout" method="post" class="block ml-2">
                                @csrf
                                <button type="submit" class="w-full text-left p-2 rounded hover:bg-emerald-50 text-sm">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Sair
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('register') }}" class="block p-2 rounded bg-emerald-600 text-white">Cadastro</a>
                        <a href="{{ route('login') }}" class="block p-2 rounded bg-yellow-400 text-gray-900">Login</a>
                    @endauth
                    @auth
                        @php($canPastoreio = auth()->user()?->canAccessPage('/pastoreio'))
                        @if($canPastoreio)
                        <div class="border-t border-gray-200 pt-2">
                            <p class="text-sm text-gray-500 px-2 mb-1">Pastoreio</p>
                            <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Painel de Controle</a>
                            <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Controle de Frequência</a>
                            <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Sorteio de Grupos</a>
                        </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main id="main">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        @php($logoUrl = $logoUrl ?? data_get($siteSettings ?? [], 'brand_logo'))
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo" class="site-logo shrink-0 h-10 md:h-12 w-auto max-w-[180px] object-contain" />
                        @else
                            <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">RCC</span>
                            </div>
                        @endif
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
                        <a href="{{ data_get($siteSettings, 'social.instagram', '#') }}" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm6.5-.75a1 1 0 110 2 1 1 0 010-2z"/></svg>
                        </a>
                        <a href="{{ data_get($siteSettings, 'social.facebook', '#') }}" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 10-11.5 9.95v-7.04H7.9V12h2.6V9.8c0-2.56 1.52-3.98 3.85-3.98 1.12 0 2.29.2 2.29.2v2.52h-1.29c-1.27 0-1.66.79-1.66 1.6V12h2.83l-.45 2.91h-2.38v7.04A10 10 0 0022 12z"/></svg>
                        </a>
                        <a href="{{ data_get($siteSettings, 'social.youtube', '#') }}" class="text-gray-400 hover:text-white transition-colors" aria-label="YouTube">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.18a3 3 0 00-2.1-2.12C19.4 3.5 12 3.5 12 3.5s-7.4 0-9.4.56A3 3 0 00.5 6.18 31.9 31.9 0 000 12c0 5.82.5 5.82.5 5.82a3 3 0 002.1 2.12c2 .56 9.4.56 9.4.56s7.4 0 9.4-.56a3 3 0 002.1-2.12c.5-2 .5-5.82.5-5.82s0-3.82-.5-5.82zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}" class="text-gray-300 hover:text-white transition-colors">Início</a></li>
                        <li><a href="{{ url('/sobre') }}" class="text-gray-300 hover:text-white transition-colors">Sobre Nós</a></li>
                        <li><a href="{{ url('/servicos') }}" class="text-gray-300 hover:text-white transition-colors">Serviços</a></li>
                        <li><a href="{{ url('/contato') }}" class="text-gray-300 hover:text-white transition-colors">Contato</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contato</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                            <span>{{ data_get($siteSettings, 'site.email') }}</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            <span>{{ data_get($siteSettings, 'site.phone') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} RCC System. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    
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
        mobileMenu && mobileMenu.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                mobileMenu.classList.add('hidden');
                menuBtn.setAttribute('aria-expanded','false');
                mobileMenu.setAttribute('aria-hidden','true');
                menuBtn.focus();
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
    </script>
    
    @if(\Illuminate\Support\Facades\Route::has('livewire.update'))
        @livewireScripts
    @endif
    @stack('scripts')
</body>
</html>
