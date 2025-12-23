@php($title = $event->name . ' - RCC System')
<x-layouts.app :title="$title">
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-emerald-600 to-teal-700 text-white">
            <div class="absolute inset-0 bg-black/30"></div>
            
            @php($gallery = is_array($event->gallery_images ?? null) ? array_values(array_filter($event->gallery_images)) : [])
            @php($heroList = $gallery)
            @php($heroUrls = collect($heroList)->map(function ($p) {
                $p = is_array($p) ? (isset($p[0]) ? $p[0] : (isset($p['path']) ? $p['path'] : null)) : $p;
                if (! is_string($p) || $p === '') {
                    return null;
                }
                if (\Illuminate\Support\Str::startsWith($p, ['http://', 'https://'])) {
                    return $p;
                }
                if (\Illuminate\Support\Str::startsWith($p, ['/storage', 'storage/'])) {
                    $np = \Illuminate\Support\Str::startsWith($p, '/storage/') ? substr($p, 9) : (str_starts_with($p, 'storage/') ? substr($p, 8) : ltrim($p, '/'));
                    return asset('storage/'.ltrim($np, '/'));
                }
                $np = ltrim($p, '/');
                if (! \Illuminate\Support\Str::contains($np, '/')) {
                    $np = 'events/gallery/'.$np;
                }
                try {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($np)) {
                        return \Illuminate\Support\Facades\Storage::disk('public')->url($np);
                    }
                } catch (\Throwable $e) {
                }
                return asset('storage/'.ltrim($np, '/'));
            })->filter()->values()->all())
            @if(count($heroUrls))
                <div id="hero-carousel" class="absolute inset-0">
                    <div class="absolute inset-0 w-full h-full bg-center bg-cover opacity-40 transition-opacity duration-700" style="background-image: url('{{ $heroUrls[0] }}');"></div>
                </div>
            @endif
            @if(count($heroUrls) > 1)
                <script>
                    (function () {
                        var imgs = @json($heroUrls);
                        var root = document.getElementById('hero-carousel');
                        if (!root || !imgs || imgs.length < 2) return;
                        var el = root.querySelector('div');
                        var i = 0;
                        setInterval(function () {
                            i = (i + 1) % imgs.length;
                            el.style.backgroundImage = "url('"+imgs[i]+"')";
                        }, 5000);
                    })();
                </script>
            @endif
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                <div class="max-w-4xl">
                    <div class="mb-6">
                        <a href="{{ route('events.index') }}" 
                           class="inline-flex items-center text-emerald-200 hover:text-white mb-4 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Voltar para Eventos
                        </a>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        {{ $event->name }}
                    </h1>
                    
                    @if($event->short_description)
                        <p class="text-xl md:text-2xl text-emerald-100 mb-8 max-w-3xl">
                            {{ $event->short_description }}
                        </p>
                    @endif
                    
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
                                {{ $event->start_date->format('d/m/Y') }} às {{ $event->start_date->format('H:i') }}
                                @if($event->end_date && $event->end_date->format('Y-m-d') !== $event->start_date->format('Y-m-d'))
                                    <br>até {{ $event->end_date->format('d/m/Y') }}
                                @endif
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
                            <p class="text-emerald-100">{{ $event->location }}</p>
                            @if($event->address)
                                <p class="text-emerald-200 text-sm">{{ $event->address }}</p>
                            @endif
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <span class="font-semibold">Vagas</span>
                            </div>
                            @if($event->capacity)
                                <p class="text-emerald-100">
                                    {{ $event->availableTickets() }} disponíveis
                                    <span class="text-emerald-200 text-sm">de {{ $event->capacity }}</span>
                                </p>
                            @else
                                <p class="text-emerald-100">
                                    Vagas ilimitadas
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Botão de Ação -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($hasTicket)
                            <a href="{{ route('events.my-tickets') }}" 
                               class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Meus Ingressos
                            </a>
                        @elseif($event->isSoldOut())
                            <button disabled 
                                    class="bg-gray-400 text-white px-8 py-4 rounded-xl font-semibold text-lg cursor-not-allowed">
                                Ingressos Esgotados
                            </button>
                        @elseif(!$event->isActive())
                            <button disabled 
                                    class="bg-gray-400 text-white px-8 py-4 rounded-xl font-semibold text-lg cursor-not-allowed">
                                Evento Indisponível
                            </button>
                        @else
                            <a href="{{ route('events.purchase', $event) }}" 
                               class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                Participar do Evento
                            </a>
                        @endif
                        
                        @if($event->price > 0)
                            <div class="flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4">
                                <span class="text-2xl font-bold text-white">
                                    R$ {{ number_format($event->price, 2, ',', '.') }}
                                </span>
                                @if($event->is_paid)
                                    <span class="text-emerald-200 ml-2">por pessoa</span>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4">
                                <span class="text-xl font-bold text-white">
                                    Entrada Gratuita
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-8 space-y-12">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden md:h-[336px]">
                        <div class="flex flex-col md:flex-row md:items-stretch">
                            <div class="relative flex-1 h-[230px] md:h-full md:self-stretch overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 min-h-[240px] md:min-h-[336px]">
                                @php(
                                    $imgCandidate = $event->folder_image ?: $event->featured_image
                                )
                                @php(
                                    $path = is_array($imgCandidate)
                                        ? (isset($imgCandidate[0]) ? $imgCandidate[0] : (isset($imgCandidate['path']) ? $imgCandidate['path'] : null))
                                        : (string) $imgCandidate
                                )
                                @php(
                                    $isUrl = is_string($path) && str_starts_with($path, 'http')
                                )
                                @php(
                                    $normalized = is_string($path)
                                        ? (str_starts_with($path, 'storage/')
                                            ? substr($path, 8)
                                            : (str_starts_with($path, '/storage/')
                                                ? substr($path, 9)
                                                : ltrim($path, '/')))
                                        : null
                                )
                                @php(
                                    $normalized = is_string($normalized) && !str_contains($normalized, '/') ? ('events/folder/'.$normalized) : $normalized
                                )
                                @php(
                                    $imgUrl = null
                                )
                                @php(
                                    $imgUrl = $isUrl ? $path : ($normalized ? ('/storage/'.ltrim($normalized, '/')) : null)
                                )
                                @if($imgUrl)
                                    <div class="absolute inset-0 bg-center bg-cover" style="background-image: url('{{ $imgUrl }}');"></div>
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    @if($event->isSoldOut())
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">Esgotado</span>
                                    @elseif($event->status === 'active')
                                        <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-sm font-medium">Disponível</span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-4 md:p-6 bg-gradient-to-b from-white to-emerald-50 md:flex-1 flex flex-col">
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ $event->name }}</h2>
                                @if($event->short_description)
                                    <p class="text-gray-700 mb-4">{{ \Illuminate\Support\Str::limit(strip_tags($event->short_description), 250) }}</p>
                                @endif
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                    <div class="flex items-center bg-white rounded-xl shadow-sm p-3 h-full">
                                        <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $event->start_date->format('d/m/Y') }}</p>
                                            <p class="text-sm text-gray-500">{{ $event->start_date->format('H:i') }}h</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center bg-white rounded-xl shadow-sm p-3 h-full">
                                        <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $event->location }}</p>
                                            @if($event->address)
                                                <p class="text-sm text-gray-500">{{ $event->address }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center bg-white rounded-xl shadow-sm p-3 h-full">
                                        <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900 whitespace-nowrap">{{ $event->availableTickets() }} disponíveis</p>
                                            @if($event->capacity)
                                                <p class="text-sm text-gray-500">de {{ $event->capacity }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between bg-white rounded-xl shadow-sm p-3 h-full gap-4">
                                        <div class="text-2xl font-bold text-emerald-700 leading-none">
                                            @if($event->price > 0)
                                                R$ {{ number_format($event->price, 2, ',', '.') }}
                                            @else
                                                <span class="text-green-600">Gratuito</span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sobre o Evento -->
                    @if($event->description)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Sobre o Evento</h2>
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed md:columns-2 lg:columns-2 gap-8 prose-ul:ml-4 prose-ol:ml-4">
                                @php($allowedTags = '<p><br><strong><em><ul><ol><li>')
                                {!! strip_tags((string) $event->description, $allowedTags) !!}
                            </div>
                        </section>
                    @endif
                    
                    <!-- Programação -->
                    @if($event->schedule && count($event->schedule) > 0)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Programação</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($event->schedule as $item)
                                    <div class="flex items-start bg-gradient-to-br from-gray-50 to-emerald-50 rounded-xl p-6 shadow-sm">
                                        <div class="bg-emerald-600 text-white rounded-lg px-4 py-2 font-semibold mr-6 min-w-[80px] text-center">
                                            {{ $item['time'] ?? '' }}
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 mb-2">{{ $item['title'] ?? '' }}</h3>
                                            @if(isset($item['description']))
                                                <p class="text-gray-600">{{ $item['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                    
                    <!-- Palestrantes/Organizadores -->
                    @if($event->organizers && count($event->organizers) > 0)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Palestrantes & Organizadores</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($event->organizers as $organizer)
                                    <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center">
                                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $organizer['name'] ?? '' }}</h3>
                                            @if(isset($organizer['role']))
                                                <p class="text-emerald-600 text-sm">{{ $organizer['role'] }}</p>
                                            @endif
                                            @if(isset($organizer['description']))
                                                <p class="text-gray-600 text-sm mt-1">{{ $organizer['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if(count($heroUrls))
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Galeria</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($heroUrls as $u)
                                    <a href="{{ $u }}" target="_blank" rel="noopener" class="block rounded-xl overflow-hidden border bg-gray-100">
                                        <img src="{{ $u }}" alt="" class="w-full h-36 md:h-44 object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                    
                    

                    <!-- Mapa de Localização -->
                    @if($event->map_embed_url)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Mapa de Localização</h2>
                            <div class="rounded-xl overflow-hidden bg-gray-100 border">
                                <iframe src="{{ $event->map_embed_url }}" width="100%" style="height:420px;border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <div class="mt-4">
                                <button type="button" onclick="openGpsRoute()" class="block mx-auto bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-base md:text-lg px-7 py-3.5 rounded-xl w-full sm:w-[85%] md:w-[75%] text-center">
                                    Me leve até o local
                                </button>
                            </div>
                        </section>
                    @endif

                    <!-- Participantes / Interessados -->
                    @if(isset($participants) && $participants->count() > 0)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Participantes</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($participants as $p)
                                    <div class="flex items-center bg-gray-50 rounded-xl p-4">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold mr-3">
                                            {{ strtoupper(substr($p->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 line-clamp-1">{{ $p->user->name ?? 'Usuário' }}</div>
                                            <div class="text-xs text-gray-500">{{ $p->payment_status === 'approved' ? 'Confirmado' : 'Interessado' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                    
                    <!-- Informações Adicionais -->
                    @if($event->additional_info && count($event->additional_info) > 0)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Informações Importantes</h2>
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($event->additional_info as $info)
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-emerald-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $info['label'] ?? '' }}</h4>
                                                <p class="text-gray-600">{{ $info['value'] ?? '' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endif

                    @if($event->arrival_info)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Chegada e estacionamento</h2>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                                <p class="text-gray-700 leading-relaxed">{!! nl2br(e($event->arrival_info)) !!}</p>
                            </div>
                        </section>
                    @endif

                    @if(is_array($event->extra_services) && count($event->extra_services) > 0)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Serviços adicionais</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($event->extra_services as $srv)
                                    <div class="bg-white rounded-xl shadow-sm border p-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $srv['title'] ?? '' }}</h3>
                                            @if(isset($srv['price']))
                                                <span class="text-emerald-700 font-medium">
                                                    {{ is_numeric($srv['price']) ? ('R$ ' . number_format((float) $srv['price'], 2, ',', '.')) : $srv['price'] }}
                                                </span>
                                            @endif
                                        </div>
                                        @if(isset($srv['desc']))
                                            <p class="text-gray-600">{{ $srv['desc'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($event->terms)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Termos e condições</h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                {!! $event->terms !!}
                            </div>
                        </section>
                    @endif

                    @if($event->rules)
                        <section>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">Regras de participação</h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                {!! $event->rules !!}
                            </div>
                        </section>
                    @endif
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
                                        <p class="font-medium">{{ $event->start_date->format('d/m/Y') }}</p>
                                        <p class="text-sm text-gray-500">{{ $event->start_date->format('H:i') }}h</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium">{{ $event->location }}</p>
                                        @if($event->address)
                                            <p class="text-sm text-gray-500">{{ $event->address }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <div>
                                        @if($event->capacity)
                                            <p class="font-medium whitespace-nowrap">{{ $event->availableTickets() }} vagas</p>
                                            <p class="text-sm text-gray-500">de {{ $event->capacity }} disponíveis</p>
                                        @else
                                            <p class="font-medium whitespace-nowrap">Vagas ilimitadas</p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($event->price > 0)
                                    <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium">R$ {{ number_format($event->price, 2, ',', '.') }}</p>
                                            <p class="text-sm text-gray-500">por pessoa</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="grid grid-cols-[24px_1fr] items-center gap-3 text-gray-600">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-green-600">Entrada Gratuita</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Botão de Ação -->
                            <div class="mt-6">
                                @if($hasTicket)
                                    <a href="{{ route('events.my-tickets') }}" 
                                       class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Meus Ingressos
                                    </a>
                                @elseif($event->isSoldOut())
                                    <button disabled 
                                            class="w-full bg-gray-400 text-white px-6 py-3 rounded-xl font-semibold cursor-not-allowed">
                                        Ingressos Esgotados
                                    </button>
                                @elseif(!$event->isActive())
                                    <button disabled 
                                            class="w-full bg-gray-400 text-white px-6 py-3 rounded-xl font-semibold cursor-not-allowed">
                                        Evento Indisponível
                                    </button>
                                @else
                                    <a href="{{ route('events.purchase', $event) }}" 
                                       class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                        Garantir Vaga
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Compartilhar -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Compartilhar</h3>
                            <div class="grid grid-cols-3 gap-3">
                                <button onclick="shareOnWhatsApp()" class="h-10 bg-[#25D366] hover:bg-[#1ebe57] text-white px-4 rounded-lg text-xs font-medium whitespace-nowrap transition-colors duration-200 flex items-center justify-center">
                                    <svg aria-hidden="true" focusable="false" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.397.099-.099.173-.223.087-.347-.086-.124-.67-.52-.92-.622-.223-.099-.48-.016-.67.11-.173.149-1.19 1.135-1.19 2.763 0 1.627 1.19 2.4 1.355 2.564.149.149 2.395 3.646 5.81 5.095 3.414 1.45 3.414.991 4.035.94.62-.05 1.995-.806 2.277-1.585.281-.78.281-1.45.198-1.585-.087-.135-.32-.223-.617-.372z"/></svg>
                                    WhatsApp
                                </button>
                                <button onclick="shareOnFacebook()" class="h-10 bg-[#1877F2] hover:bg-[#166fe0] text-white px-4 rounded-lg text-xs font-medium whitespace-nowrap transition-colors duration-200 flex items-center justify-center">
                                    <svg aria-hidden="true" focusable="false" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35C.597 0 0 .597 0 1.326v21.348C0 23.403.597 24 1.326 24h11.495v-9.294H9.691V11.01h3.13V8.414c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.794.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.31h3.587l-.467 3.696h-3.12V24h6.116C23.403 24 24 23.403 24 22.674V1.326C24 .597 23.403 0 22.675 0z"/></svg>
                                    Facebook
                                </button>
                                <button onclick="copyLink()" class="h-10 bg-gray-600 hover:bg-gray-700 text-white px-4 rounded-lg text-xs font-medium whitespace-nowrap transition-colors duration-200 flex items-center justify-center">
                                    <svg aria-hidden="true" focusable="false" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    Copiar Link
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
                    
                    @if($hasTicket)
                        <a href="{{ route('events.my-tickets') }}" 
                           class="inline-flex items-center bg-white text-emerald-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-emerald-50 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Meus Ingressos
                        </a>
                    @elseif($event->isSoldOut())
                        <button disabled 
                                class="bg-gray-400 text-white px-8 py-4 rounded-xl font-semibold text-lg cursor-not-allowed">
                            Ingressos Esgotados
                        </button>
                    @elseif(!$event->isActive())
                        <button disabled 
                                class="bg-gray-400 text-white px-8 py-4 rounded-xl font-semibold text-lg cursor-not-allowed">
                            Evento Indisponível
                        </button>
                    @else
                        <a href="{{ route('events.purchase', $event) }}" 
                           class="inline-flex items-center bg-white text-emerald-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-emerald-50 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            Garantir Minha Vaga Agora
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const imgs = @json(array_map(fn($p) => Storage::disk('public')->url($p), array_filter($heroImages ?? [])));
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
    <script>
        function shareOnWhatsApp() {
            const text = `Confira este evento: {{ $event->name }} - {{ route('events.show', $event) }}`;
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }
        function shareOnFacebook() {
            const u = encodeURIComponent(`{{ route('events.show', $event) }}`);
            const url = `https://www.facebook.com/sharer/sharer.php?u=${u}`;
            window.open(url, '_blank', 'noopener');
        }
        
        function copyLink() {
            const url = '{{ route('events.show', $event) }}';
            navigator.clipboard.writeText(url).then(function() {
                alert('Link copiado para a área de transferência!');
            }, function(err) {
                console.error('Erro ao copiar link: ', err);
            });
        }

        function toggleFavorite(){
            const key = `fav_event_{{ $event->id }}`;
            const current = localStorage.getItem(key);
            if(current){
                localStorage.removeItem(key);
                alert('Evento removido dos favoritos');
            } else {
                localStorage.setItem(key, '1');
                alert('Evento adicionado aos favoritos');
            }
        }

        function openGpsRoute(){
            const destRaw = `{{ ($event->address ?: $event->location) }}`;
            const dest = encodeURIComponent(destRaw);
            const isIOS = /iPad|iPhone|iPod/i.test(navigator.userAgent);
            const isAndroid = /Android/i.test(navigator.userAgent);

            const fallbackWeb = `https://www.google.com/maps/dir/?api=1&destination=${dest}`;

            const tryLaunch = (primary, fallback) => {
                let handled = false;
                const timer = setTimeout(() => { if (!handled) window.open(fallback, '_blank'); }, 800);
                const onVis = () => { if (document.hidden) { handled = true; clearTimeout(timer); document.removeEventListener('visibilitychange', onVis); } };
                document.addEventListener('visibilitychange', onVis);
                window.location.href = primary;
            };

            if (isIOS) {
                const google = `comgooglemaps://?daddr=${dest}&directionsmode=driving`;
                const apple = `maps://?daddr=${dest}`;
                tryLaunch(google, fallbackWeb);
                setTimeout(() => tryLaunch(apple, fallbackWeb), 300);
                return;
            }

            if (isAndroid) {
                const geo = `geo:0,0?q=${destRaw}`;
                const intent = `google.navigation:q=${destRaw}`;
                tryLaunch(intent, fallbackWeb);
                setTimeout(() => tryLaunch(geo, fallbackWeb), 300);
                return;
            }

            window.open(fallbackWeb, '_blank');
        }
    </script>
</x-layouts.app>
