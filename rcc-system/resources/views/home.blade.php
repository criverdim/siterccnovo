@extends('layouts.app')

@section('title', 'Início')
@section('description', 'Renovação Carismática Católica — Grupo de Oração')

@section('content')
@php($homeCfg = (function () { try { return (array) (\App\Models\Setting::where('key','home')->value('value') ?? []); } catch (\Throwable $e) { return []; } })())
@php($heroTitle = $homeCfg['hero_title'] ?? 'Renovação Carismática Católica')
@php($heroSubtitle = $homeCfg['hero_subtitle'] ?? 'Grupo de Oração — Louvor, Palavra e Intercessão')
@php($carousel = array_values(array_filter((array)($homeCfg['carousel'] ?? []))))
@php($limit = (int)($homeCfg['carousel_limit'] ?? 0))
@php($speedMs = max(500, (int)($homeCfg['carousel_speed_ms'] ?? 6000)))
@php($autoplay = (bool)($homeCfg['carousel_autoplay'] ?? true))
@php($pauseHover = (bool)($homeCfg['carousel_pause_on_hover'] ?? true))
@php($transitionMs = max(100, (int)($homeCfg['carousel_transition_ms'] ?? 700)))
@php($direction = (string)($homeCfg['carousel_direction'] ?? 'forward'))
@php($startRandom = (bool)($homeCfg['carousel_start_random'] ?? false))
@php($showArrows = (bool)($homeCfg['carousel_show_arrows'] ?? true))
@php($showDots = (bool)($homeCfg['carousel_show_dots'] ?? true))
@php($heroImages = array_map(fn($p) => asset('storage/'.ltrim($p,'/')), $carousel))
@php($heroImages = ($limit > 0 ? array_slice($heroImages, 0, $limit) : $heroImages))
@php($defaults = [
    'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Renova%C3%A7%C3%A3o%20Carism%C3%A1tica%20Cat%C3%B3lica%2C%20grupo%20de%20ora%C3%A7%C3%A3o%2C%20louvor%20com%20m%C3%BAsica%2C%20pessoas%20rezando%2C%20luz%20quente%2C%20igreja&image_size=landscape_16_9',
    'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Pregacao%20da%20Palavra%2C%20Biblia%20aberta%2C%20comunidade%20catolica%2C%20grupo%20de%20ora%C3%A7%C3%A3o%2C%20luz%20suave&image_size=landscape_16_9',
    'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Intercess%C3%A3o%2C%20pessoas%20de%20m%C3%A3os%20dadas%20em%20ora%C3%A7%C3%A3o%2C%20capela%2C%20velas%2C%20esperan%C3%A7a&image_size=landscape_16_9',
])
@php($heroImages = count($heroImages) ? $heroImages : $defaults)

<section class="relative h-[500px] overflow-hidden">
    <div id="heroCarousel" class="carousel-container absolute inset-0">
        @foreach($heroImages as $idx => $img)
            <img data-slide="{{ $idx }}" src="{{ $img }}" alt="Slide {{ $idx+1 }}" class="carousel-slide absolute inset-0 w-full h-full object-cover transition-opacity ease-in-out {{ $idx === 0 ? 'opacity-100' : 'opacity-0' }}" style="transition-duration: {{ $transitionMs }}ms" onerror="this.src='data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'1280\' height=\'720\'%3E%3Crect fill=\'%23ecfdf5\' width=\'100%25\' height=\'100%25\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%230b7a48\' font-size=\'32\'%3EImagem indispon%C3%ADvel%3C/text%3E%3C/svg%3E'">
        @endforeach
    </div>
    <div class="absolute inset-0 z-10 bg-gradient-to-r from-emerald-900/70 to-emerald-800/50"></div>
    <div class="relative z-20 h-full flex items-center justify-center text-center text-white">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ $heroTitle }}</h1>
            <p class="text-xl md:text-2xl mb-8">{{ $heroSubtitle }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            </div>
        </div>
    </div>
    @if($showArrows)
    <button type="button" id="heroPrev" aria-label="Anterior" class="absolute z-30 left-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-900 rounded-full p-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" id="heroNext" aria-label="Próximo" class="absolute z-30 right-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-900 rounded-full p-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    @endif
    @if($showDots)
    <div class="absolute z-30 bottom-6 left-0 right-0 flex justify-center space-x-2">
        @foreach($heroImages as $i => $img)
            <button type="button" data-dot="{{ $i }}" aria-label="Slide {{ $i+1 }}" class="w-2.5 h-2.5 rounded-full {{ $i === 0 ? 'bg-white ring-2 ring-white' : 'bg-white/50' }}"></button>
        @endforeach
    </div>
    @endif
    
</section>

<section class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4 mb-12">
            <h2 class="text-3xl lg:text-5xl font-bold text-gray-900">Nossa missão</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Vivermos a experiência do Batismo no Espírito Santo por meio do louvor, da Palavra e da oração.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl p-8 shadow-lg hover-lift transition-all group">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-dove text-emerald-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Louvor e Adoração</h3>
                <p class="text-gray-600">Momento de entrega e exaltação, abrindo o coração à ação do Espírito Santo.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-lg hover-lift transition-all group">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-book-bible text-emerald-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pregação da Palavra</h3>
                <p class="text-gray-600">Anúncio vivo do Evangelho, com testemunhos e partilha fraterna.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-lg hover-lift transition-all group">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-hands text-emerald-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Intercessão</h3>
                <p class="text-gray-600">Oramos uns pelos outros e pelas necessidades da comunidade.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Acesso rápido</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('events.index') }}" class="p-4 rounded-xl border bg-white hover:bg-gray-50 font-medium text-gray-800">Eventos</a>
            <a href="{{ url('/groups') }}" class="p-4 rounded-xl border bg-white hover:bg-gray-50 font-medium text-gray-800">Grupos</a>
            <a href="{{ url('/calendar') }}" class="p-4 rounded-xl border bg-white hover:bg-gray-50 font-medium text-gray-800">Calendário</a>
            @auth
                <span class="p-4 rounded-xl border bg-white font-medium text-gray-800">Olá, {{ auth()->user()->name }}</span>
                <form id="logoutFormQuick" method="POST" action="{{ url('/logout') }}" class="p-4 rounded-xl border bg-white">
                    @csrf
                    <button type="submit" class="font-medium text-gray-800">Logout</button>
                </form>
            @endauth
            <a href="{{ url('/area/servo') }}" class="p-4 rounded-xl border bg-white hover:bg-gray-50 font-medium text-gray-800">Área do Servo</a>
            <a href="{{ url('/area/membro') }}" class="p-4 rounded-xl border bg-white hover:bg-gray-50 font-medium text-gray-800">Área do Membro</a>
        </div>
    </div>
</section>

<section class="py-16 lg:py-24 gradient-bg text-white">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl lg:text-5xl font-bold mb-6">Venha participar conosco</h2>
        <p class="text-xl mb-8 text-blue-100 max-w-2xl mx-auto">Participe do nosso Grupo de Oração e experimente o amor de Deus de forma nova.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/groups" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition-all hover-lift inline-flex items-center justify-center">Conheça os grupos</a>
            <a href="/contato" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all inline-flex items-center justify-center">Fale conosco</a>
        </div>
    </div>
</section>

<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4 mb-12">
            <h2 class="text-3xl lg:text-5xl font-bold text-gray-900">Testemunhos</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">O que Deus tem feito em nossas vidas por meio do Grupo de Oração.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="bg-gray-50 rounded-2xl p-8 hover-lift transition-all">
                <p class="text-gray-600 mb-4 leading-relaxed">“Senti uma paz e alegria novas ao participar do grupo. Deus tem agido poderosamente.”</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold">AM</div>
                    <div class="ml-3">
                        <div class="font-semibold text-gray-900">Ana Maria</div>
                        <div class="text-sm text-gray-500">Participante</div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 hover-lift transition-all">
                <p class="text-gray-600 mb-4 leading-relaxed">“Na intercessão encontrei consolo e força. Sou grato pela comunidade.”</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold">JC</div>
                    <div class="ml-3">
                        <div class="font-semibold text-gray-900">João Carlos</div>
                        <div class="text-sm text-gray-500">Participante</div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 hover-lift transition-all">
                <p class="text-gray-600 mb-4 leading-relaxed">“A Palavra tocou meu coração e transformou minha vida.”</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold">AP</div>
                    <div class="ml-3">
                        <div class="font-semibold text-gray-900">Ana Paula</div>
                        <div class="text-sm text-gray-500">Participante</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    var headings = Array.from(document.querySelectorAll('h1,h2,h3')).filter(function(el){
        return (el.textContent||'').trim().toLowerCase().includes('acesso rápido');
    });
    headings.forEach(function(h){
        var section = h.closest('section');
        if (section) { section.remove(); }
    });
});
</script>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    var container=document.getElementById('heroCarousel');
    if(!container)return;
    var slides=container.querySelectorAll('img[data-slide]');
    var dots=document.querySelectorAll('[data-dot]');
    var prev=document.getElementById('heroPrev');
    var next=document.getElementById('heroNext');
    var i={{ $startRandom ? 'Math.floor(Math.random()*slides.length)' : 0 }};
    function show(n){
        slides[i].classList.remove('opacity-100');
        slides[i].classList.add('opacity-0');
        slides[n].classList.remove('opacity-0');
        slides[n].classList.add('opacity-100');
        dots.forEach(function(d,di){
            if(di===n){d.classList.add('ring-2');d.classList.add('bg-white');d.classList.remove('bg-white/50');}
            else{d.classList.remove('ring-2');d.classList.remove('bg-white');d.classList.add('bg-white/50');}
        });
        i=n;
    }
    dots.forEach(function(d,di){d.addEventListener('click',function(){show(di);});});
    prev&&prev.addEventListener('click',function(){show((i-1+slides.length)%slides.length);});
    next&&next.addEventListener('click',function(){show((i+1)%slides.length);});
    var interval={{ $speedMs }};
    var autoplay={{ $autoplay ? 'true' : 'false' }};
    var pauseHover={{ $pauseHover ? 'true' : 'false' }};
    var direction='{{ $direction === 'backward' ? 'backward' : 'forward' }}';
    var timer=null;
    if(autoplay){
        timer=setInterval(function(){
            var nextIndex = direction==='backward' ? (i-1+slides.length)%slides.length : (i+1)%slides.length;
            show(nextIndex);
        },interval);
    }
    if(pauseHover){
        container.addEventListener('mouseenter',function(){ if(timer){ clearInterval(timer); timer=null; } });
        container.addEventListener('mouseleave',function(){ if(autoplay && !timer){ timer=setInterval(function(){ var nextIndex = direction==='backward' ? (i-1+slides.length)%slides.length : (i+1)%slides.length; show(nextIndex); },interval); } });
    }
    if({{ $startRandom ? 'true' : 'false' }}){ show(i); }
});
</script>
@endpush
