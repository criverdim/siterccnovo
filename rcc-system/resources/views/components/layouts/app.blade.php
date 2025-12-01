<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="RCC • Evangelizar com beleza, simplicidade e organização">
    <meta name="theme-color" content="#0b7a48">
    <title>{{ $title ?? 'RCC' }}</title>
    @vite(['resources/css/app.css','resources/js/app.jsx'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <style>
        .gold { color: #c9a043; }
        .bg-gold { background-color: #c9a043; }
    </style>
</head>
<body class="bg-white text-gray-900">
    <a href="#main" class="sr-only focus-ring">Ir para conteúdo principal</a>
    <header class="p-4 md:p-6 border-b bg-white/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/" class="site-logo-wrap">
                @php($brand = \App\Models\Setting::where('key','brand')->first())
                @if(($brand?->value['logo'] ?? null))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($brand->value['logo']) }}" alt="Logo RCC" class="site-logo site-logo-contrast site-logo-ring rounded-md p-1" loading="eager" decoding="async" fetchpriority="high" />
                @else
                    <img src="{{ asset('favicon.ico') }}" alt="Logo RCC" class="site-logo site-logo-contrast site-logo-ring rounded-md p-1" />
                @endif
                <div class="text-xl md:text-2xl font-semibold text-emerald-700">Grupo de Oração</div>
            </a>
            <nav class="hidden md:flex items-center gap-6">
                <a href="/" class="hover:text-emerald-700 transition-colors" @if(request()->is('/')) aria-current="page" @endif>Início</a>
                <a href="/events" class="hover:text-emerald-700 transition-colors" @if(request()->is('events*')) aria-current="page" @endif>Eventos</a>
                <a href="/groups" class="hover:text-emerald-700 transition-colors" @if(request()->is('groups*')) aria-current="page" @endif>Grupos</a>
                <a href="/calendar" class="hover:text-emerald-700 transition-colors" @if(request()->is('calendar*')) aria-current="page" @endif>Calendário</a>
                <div class="relative group">
                    <button class="hover:text-emerald-700 font-medium flex items-center">
                        Pastoreio <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="/pastoreio" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-t-lg">Painel de Controle</a>
                        <a href="/pastoreio" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">Controle de Frequência</a>
                        <a href="/pastoreio" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-b-lg">Sorteio de Grupos</a>
                    </div>
                </div>
                <a href="/register" class="btn btn-primary" @if(request()->is('register')) aria-current="page" @endif>Cadastro</a>
                <a href="/login" class="btn btn-outline" @if(request()->is('login')) aria-current="page" @endif>Área do Servo/Membro</a>
            </nav>
            <button id="menuBtn" class="md:hidden p-2 rounded border" aria-controls="mobileMenu" aria-expanded="false"><i class="fa fa-bars"></i><span class="sr-only">Abrir menu</span></button>
        </div>
        <div id="mobileMenu" class="md:hidden hidden mt-3 max-w-7xl mx-auto">
            <div class="grid gap-2">
                <a href="/" class="block p-2 rounded hover:bg-emerald-50">Início</a>
                <a href="/events" class="block p-2 rounded hover:bg-emerald-50">Eventos</a>
                <a href="/groups" class="block p-2 rounded hover:bg-emerald-50">Grupos</a>
                <a href="/calendar" class="block p-2 rounded hover:bg-emerald-50">Calendário</a>
                <div class="border-t border-gray-200 pt-2">
                    <p class="text-sm text-gray-500 px-2 mb-1">Pastoreio</p>
                    <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Painel de Controle</a>
                    <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Controle de Frequência</a>
                    <a href="/pastoreio" class="block p-2 rounded hover:bg-emerald-50 text-sm ml-2">Sorteio de Grupos</a>
                </div>
                <a href="/register" class="block p-2 rounded bg-emerald-600 text-white">Cadastro</a>
                <a href="/login" class="block p-2 rounded border">Área do Servo/Membro</a>
            </div>
        </div>
    </header>

    <main id="main" class="min-h-screen fade-in">
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t">
        <div class="max-w-7xl mx-auto p-6 grid md:grid-cols-3 gap-6">
            <div>
                <div class="text-emerald-700 font-semibold mb-2">Contato</div>
                <div class="text-sm text-gray-700">Endereço: {{ env('SITE_ADDRESS', 'Rua Exemplo, 123 - Cidade/UF') }}</div>
                <div class="text-sm text-gray-700">Telefone: {{ env('SITE_PHONE', '(00) 0000-0000') }}</div>
                <div class="text-sm text-gray-700">WhatsApp: {{ env('SITE_WHATSAPP', '(00) 90000-0000') }}</div>
            </div>
            <div>
                <div class="text-emerald-700 font-semibold mb-2">Redes</div>
                <div class="flex items-center gap-4 text-2xl">
                    <a href="{{ env('SOCIAL_INSTAGRAM', '#') }}" class="text-emerald-700 hover:gold"><i class="fab fa-instagram" aria-label="Instagram"></i></a>
                    <a href="{{ env('SOCIAL_FACEBOOK', '#') }}" class="text-emerald-700 hover:gold"><i class="fab fa-facebook" aria-label="Facebook"></i></a>
                    <a href="{{ env('SOCIAL_YOUTUBE', '#') }}" class="text-emerald-700 hover:gold"><i class="fab fa-youtube" aria-label="YouTube"></i></a>
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
