@php($title = $event->name . ' - RCC System')
@php($ogImagePath = $event->folder_image ?: ($event->featured_image ?: null))
@php($ogImageUrl = $ogImagePath ? \Illuminate\Support\Facades\Storage::disk('public')->url($ogImagePath) : null)
@php($og = [
    'title' => $event->name,
    'description' => strip_tags($event->short_description ?? $event->description ?? ''),
    'image' => $ogImageUrl,
    'url' => route('events.show', $event),
])
<x-layouts.app :title="$title" :og="$og">
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-emerald-600 to-teal-700 text-white">
            <div class="absolute inset-0 bg-black/30"></div>
            
            <!-- Carrossel de Fundo -->
            @php($gallery = is_array($event->gallery_images ?? null) ? array_values(array_filter($event->gallery_images)) : [])
            @php($heroImages = count($gallery) ? $gallery : ([$event->featured_image] ?? []))
            @if(count(array_filter($heroImages)))
                <img id="hero-image" src="{{ Storage::disk('public')->url($heroImages[0]) }}" 
                     alt="{{ $event->name }}" 
                     class="absolute inset-0 w-full h-full object-cover opacity-40 transition-opacity duration-700">
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
                            <p class="text-emerald-100">
                                {{ $event->availableTickets() }} disponíveis
                                @if($event->capacity)
                                    <span class="text-emerald-200 text-sm">de {{ $event->capacity }}</span>
                                @endif
                            </p>
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
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden md:h-[420px]">
                        <div class="flex flex-col md:flex-row md:items-stretch">
                            <div class="relative flex-1 h-72 md:h-full md:self-stretch overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600">
                                @if($event->featured_image)
                                    <img src="{{ Storage::disk('public')->url($event->folder_image ?? $event->featured_image) }}" alt="{{ $event->name }}" class="absolute inset-0 w-full h-full object-cover">
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
                    
                    

                    <!-- Mapa de Localização -->
                    

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
                    <div class="sticky top-8 space-y-6">
                        
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
                                        <p class="font-medium whitespace-nowrap">{{ $event->availableTickets() }} vagas</p>
                                        @if($event->capacity)
                                            <p class="text-sm text-gray-500">de {{ $event->capacity }} disponíveis</p>
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
                        @if($event->map_embed_url)
                        <div class="bg-white rounded-2xl shadow-xl p-6 ring-1 ring-emerald-100">
                            @php($addr = trim((string) ($event->address ?: $event->location)))
                            @php($embedSrc = $addr ? ('https://www.google.com/maps?q='.urlencode($addr).'&hl=pt-BR&z=16&output=embed') : '')
                            @if($embedSrc)
                                <div class="rounded-xl overflow-hidden bg-gray-100">
                                    <iframe src="{{ $embedSrc }}" width="100%" style="height:360px;border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            @endif
                            <div class="mt-4">
                                <a href="#" onclick="openDirections()" class="block mx-auto bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-base md:text-lg px-7 py-3.5 rounded-xl w-full sm:w-[85%] md:w-[75%] text-center">
                                    Clique aqui para ver o local
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Compartilhar -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Compartilhar</h3>
                            @php($pageUrl = route('events.show',$event))
                            @php($encUrl = urlencode($pageUrl))
                            @php($encTitle = urlencode($event->name))
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <button onclick="shareNative()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white h-11 md:h-12 px-4 transition">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v7a1 1 0 001 1h14a1 1 0 001-1v-7"/><path d="M16 6l-4-4-4 4"/><path d="M12 2v14"/></svg>
                                    <span class="text-sm md:text-base">Nativo</span>
                                </button>
                                <a href="https://wa.me/?text={{ $encTitle }}%20-%20{{ $encUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-500 hover:bg-green-600 text-white h-11 md:h-12 px-4 transition">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.77 11.77 0 0012.06 0C5.4 0 .07 5.33.07 11.9A11.9 11.9 0 001.5 17.6L0 24l6.59-1.73a11.94 11.94 0 005.47 1.39h.01c6.56 0 11.9-5.33 11.9-11.9a11.77 11.77 0 00-3.45-8.28zm-8.46 19.2h-.01a9.84 9.84 0 01-5.02-1.38l-.36-.21-3.91 1.03 1.05-3.81-.24-.39A9.83 9.83 0 012.22 11.9c0-5.43 4.42-9.85 9.86-9.85 2.63 0 5.1 1.02 6.96 2.87a9.78 9.78 0 012.88 6.97c0 5.43-4.43 9.86-9.86 9.86zm5.44-7.4c-.3-.15-1.77-.87-2.05-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.64.08-.3-.15-1.28-.47-2.43-1.49-.9-.8-1.51-1.78-1.68-2.08-.17-.3-.02-.46.13-.61.14-.13.3-.35.46-.52.15-.17.2-.3.3-.4.1-.1.17-.22.09-.35-.08-.13-.62-.5-.85-.6-.21-.1-.46-.02-.63.1-.17.15-1.18 1.12-1.18 2.72s1.22 2.98 1.39 3.19c.17.21 2.41 3.87 5.85 5.42 3.44 1.56 3.3 1.05 3.89.99.6-.05 1.97-.8 2.26-1.58.29-.78.29-1.45.2-1.59-.09-.14-.33-.24-.63-.39z"/></svg>
                                    <span class="text-sm md:text-base">WhatsApp</span>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white h-11 md:h-12 px-4 transition">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35C.596 0 0 .596 0 1.333v21.334C0 23.404.596 24 1.325 24h11.49v-9.294H9.847v-3.622h2.967V8.413c0-2.938 1.793-4.543 4.41-4.543 1.253 0 2.33.093 2.645.135v3.066l-1.816.001c-1.424 0-1.699.677-1.699 1.671v2.189h3.396l-.442 3.622h-2.954V24h5.789C23.404 24 24 23.404 24 22.667V1.333C24 .596 23.404 0 22.675 0z"/></svg>
                                    <span class="text-sm md:text-base">Facebook</span>
                                </a>
                                <a href="https://www.facebook.com/dialog/send?link={{ $encUrl }}&app_id=194411547592286&redirect_uri={{ $encUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white h-11 md:h-12 px-4 transition">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 6.627 5.373 12 12 12s12-5.373 12-12c0-6.627-5.373-12-12-12zm3.33 9.3c.02.292.03.593.03.903 0 9.23-7.025 15.86-15.86 15.86-3.15 0-6.087-.92-8.56-2.5.437.05.88.075 1.33.075 2.615 0 5.02-.892 6.93-2.39a5.59 5.59 0 01-5.22-3.88c.35.067.71.1 1.08.1.52 0 1.02-.07 1.49-.2a5.58 5.58 0 01-4.47-5.48v-.07c.75.416 1.61.666 2.52.694a5.58 5.58 0 01-2.49-4.65c0-1.02.28-1.97.77-2.79a15.8 15.8 0 0011.48 5.84 6.29 6.29 0 01-.14-1.28 5.58 5.58 0 015.58-5.58c1.61 0 3.06.68 4.07 1.78a11.01 11.01 0 003.54-1.35 5.6 5.6 0 01-2.45 3.08 11.15 11.15 0 003.2-.87 12.02 12.02 0 01-2.79 2.89z"/></svg>
                                    <span class="text-sm md:text-base">Messenger</span>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text={{ $encTitle }}&url={{ $encUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl bg-black hover:bg-gray-800 text-white h-11 md:h-12 px-4 transition">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2H21l-6.397 7.292L22 22h-7.244l-4.72-6.395L4.5 22H2l7.02-7.988L2 2h7.244l4.201 5.693L18.244 2zm-2.54 18h2.45l-5.332-7.234L19.5 4h-2.45l-4.95 5.83L9.45 4H7l5.012 6.8L5 20h2.45l5.19-6.11L15.704 20z"/></svg>
                                    <span class="text-sm md:text-base">X</span>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 hover:bg-blue-800 text-white h-11 md:h-12 px-4 transition md:col-span-2 col-span-2">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.327-.027-3.034-1.849-3.034-1.851 0-2.134 1.445-2.134 2.939v5.664H9.355V9h3.414v1.561h.049c.476-.9 1.637-1.849 3.368-1.849 3.602 0 4.268 2.37 4.268 5.455v6.285zM5.337 7.433c-1.144 0-2.07-.927-2.07-2.07 0-1.145.926-2.071 2.07-2.071 1.145 0 2.07.926 2.07 2.07 0 1.144-.925 2.071-2.07 2.071zM6.936 20.452H3.739V9h3.197v11.452zM22.225 0H1.771C.792 0 0 .771 0 1.723v20.554C0 23.229.792 24 1.771 24h20.454C23.204 24 24 23.229 24 22.277V1.723C24 .771 23.204 0 22.225 0z"/></svg>
                                    <span class="text-sm md:text-base">LinkedIn</span>
                                </a>
                                <button onclick="copyLink()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-600 hover:bg-gray-700 text-white h-11 md:h-12 px-4 transition col-span-2 md:col-span-3">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 14L21 3"/><path d="M3 21l12-12"/></svg>
                                    <span class="text-sm md:text-base">Copiar Link</span>
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
        function openDirections(){
            const dest = encodeURIComponent(`{{ ($event->address ?: $event->location) }}`);
            const url = `https://www.google.com/maps/dir/?api=1&destination=${dest}`;
            if (/Android|iPhone|iPad/i.test(navigator.userAgent)) {
                window.location.href = url;
            } else {
                window.open(url, '_blank');
            }
        }
        function shareOnWhatsApp() {
            const text = `Confira este evento: {{ $event->name }} - {{ route('events.show', $event) }}`;
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
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

        async function shareNative(){
            const shareData = {
                title: `{{ $event->name }}`,
                text: `{{ $event->short_description ? strip_tags($event->short_description) : '' }}`,
                url: `{{ route('events.show', $event) }}`,
            };
            if (navigator.share) {
                try { await navigator.share(shareData); } catch(e) {}
            } else {
                shareVia('facebook');
            }
        }

        function shareVia(platform){
            const title = `{{ $event->name }}`;
            const pageUrl = `{{ route('events.show', $event) }}`;
            const text = `${title} - ${pageUrl}`;
            let url = '';
            if (platform === 'whatsapp') {
                const waNative = `whatsapp://send?text=${encodeURIComponent(text)}`;
                const waWeb = `https://wa.me/?text=${encodeURIComponent(text)}`;
                try { window.location.href = waNative; setTimeout(()=>window.open(waWeb,'_blank'), 600); } catch(e){ window.open(waWeb,'_blank'); }
                return;
            } else if (platform === 'messenger') {
                const msNative = `fb-messenger://share/?link=${encodeURIComponent(pageUrl)}`;
                const msWeb = `https://www.facebook.com/dialog/send?link=${encodeURIComponent(pageUrl)}&app_id=194411547592286&redirect_uri=${encodeURIComponent(pageUrl)}`;
                try { window.location.href = msNative; setTimeout(()=>window.open(msWeb,'_blank'), 600); } catch(e){ window.open(msWeb,'_blank'); }
                return;
            } else if (platform === 'facebook') {
                url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`;
            } else if (platform === 'twitter') {
                url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(pageUrl)}`;
            } else if (platform === 'linkedin') {
                url = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`;
            }
            if (url) window.open(url, '_blank');
        }
    </script>
</x-layouts.app>
