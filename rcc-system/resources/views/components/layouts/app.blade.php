<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="RCC • Evangelizar com beleza, simplicidade e organização">
    @if(isset($og))
        <meta property="og:title" content="{{ $og['title'] }}" />
        <meta property="og:description" content="{{ $og['description'] }}" />
        <meta property="og:image" content="{{ $og['image'] }}" />
        <meta property="og:url" content="{{ $og['url'] }}" />
        <meta property="og:type" content="article" />
        <meta name="twitter:card" content="summary_large_image" />
    @endif
    <meta name="theme-color" content="#0b7a48">
    <title>{{ $title ?? 'RCC' }}</title>
    @php($viteManifest = public_path('build/manifest.json'))
    @if(file_exists($viteManifest))
        @vite(['resources/css/app.css','resources/js/app.jsx'])
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <style>
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
    </style>
</head>
<body class="bg-white text-gray-900">
    <a href="#main" class="sr-only focus-ring">Ir para conteúdo principal</a>
    @if(empty($minimal) || !$minimal)
    <header class="bg-white/95 backdrop-blur-sm shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 md:h-[4.18rem] lg:h-[5.28rem] overflow-hidden">
                <div class="flex items-center">
                    @php($logoUrl = data_get($siteSettings ?? [], 'brand_logo'))
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
                            <p class="brand-subtitle text-brand-yellow md:text-xs -mt-0.5">Miguelópolis-SP</p>
                        </div>
                    </a>
                </div>
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/calendar') }}" class="hover:text-emerald-700 transition-colors" @if(request()->is('calendar*')) aria-current="page" @endif>Calendário</a>
                    <a href="{{ route('events.index') }}" class="hover:text-emerald-700 transition-colors" @if(request()->is('events*')) aria-current="page" @endif>Eventos</a>
                    <a href="{{ url('/groups') }}" class="hover:text-emerald-700 transition-colors" @if(request()->is('groups*')) aria-current="page" @endif>Grupos</a>
                    <a href="{{ route('home') }}" class="hover:text-emerald-700 transition-colors">Início</a>
                    <div class="relative group">
                        <button class="hover:text-emerald-700 font-medium flex items-center">Mais <i class="fas fa-chevron-down ml-1 text-xs"></i></button>
                        <div class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('sobre') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Sobre</a>
                            <a href="{{ route('servicos') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Serviços</a>
                            <a href="{{ route('contato') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Contato</a>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="bg-emerald-600 text-white px-3.5 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-semibold shadow-sm" @if(request()->is('register')) aria-current="page" @endif>Cadastro</a>
                    <a href="{{ route('login') }}" class="bg-yellow-400 text-gray-900 px-3.5 py-2 rounded-lg hover:bg-yellow-500 transition-colors font-semibold shadow-sm" @if(request()->is('login')) aria-current="page" @endif>Login</a>
                    <div class="relative group">
                        <button class="hover:text-emerald-700 font-medium flex items-center">Pastoreio <i class="fas fa-chevron-down ml-1 text-xs"></i></button>
                        <div class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="/pastoreio" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-t-lg">Painel de Controle</a>
                            <a href="/pastoreio" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Controle de Frequência</a>
                            <a href="/pastoreio" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-b-lg">Sorteio de Grupos</a>
                        </div>
                    </div>
                </nav>
                <button id="menuBtn" class="md:hidden p-2 rounded border" aria-controls="mobileMenu" aria-expanded="false"><i class="fa fa-bars"></i><span class="sr-only">Abrir menu</span></button>
            </div>
            <div id="mobileMenu" class="md:hidden hidden mt-3">
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
                    <a href="{{ route('register') }}" class="block p-2 rounded bg-emerald-600 text-white">Cadastro</a>
                    <a href="{{ route('login') }}" class="block p-2 rounded bg-yellow-400 text-gray-900">Login</a>
                    <div class="border-t border-gray-200 pt-2">
                        <p class="text-sm text-gray-500 px-2 mb-1">Pastoreio</p>
                        <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Painel de Controle</a>
                        <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Controle de Frequência</a>
                        <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Sorteio de Grupos</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @endif

    <main id="main" class="min-h-screen fade-in">
        {{ $slot }}
    </main>

    @if((empty($minimal) || !$minimal) && (empty($hideFooter) || !$hideFooter))
    <footer class="mt-16 border-t">
        <div class="max-w-7xl mx-auto p-6 grid md:grid-cols-3 gap-6">
            <div>
                <div class="text-emerald-700 font-semibold mb-2">Contato</div>
                <div class="text-sm text-gray-700">Endereço: {{ data_get($siteSettings, 'site.address') }}</div>
                <div class="text-sm text-gray-700">Telefone: {{ data_get($siteSettings, 'site.phone') }}</div>
                <div class="text-sm text-gray-700">WhatsApp: {{ data_get($siteSettings, 'site.whatsapp') }}</div>
            </div>
            <div>
                <div class="text-emerald-700 font-semibold mb-2">Redes</div>
                <div class="flex items-center gap-4 text-2xl">
                    <a href="{{ data_get($siteSettings, 'social.instagram', '#') }}" class="text-emerald-700 hover:gold"><i class="fab fa-instagram" aria-label="Instagram"></i></a>
                    <a href="{{ data_get($siteSettings, 'social.facebook', '#') }}" class="text-emerald-700 hover:gold"><i class="fab fa-facebook" aria-label="Facebook"></i></a>
                    <a href="{{ data_get($siteSettings, 'social.youtube', '#') }}" class="text-emerald-700 hover:gold"><i class="fab fa-youtube" aria-label="YouTube"></i></a>
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
        <div class="p-4 text-center text-xs text-gray-500">© {{ date('Y') }} RCC • Excelência visual e simplicidade</div>
    </footer>
    @endif

    <script>
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (menuBtn) menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const expanded = menuBtn.getAttribute('aria-expanded') === 'true';
            menuBtn.setAttribute('aria-expanded', (!expanded).toString());
        });
    </script>
</body>
</html>
