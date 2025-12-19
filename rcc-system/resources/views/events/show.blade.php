@php($title = 'Evento - '.$event->name)
@php($og = [
    'title' => $event->name,
    'description' => strip_tags($event->description ?? ''),
    'image' => (is_array($event->photos) && count($event->photos)) ? asset('storage/'.($event->photos[0])) : asset('favicon.ico'),
    'url' => url('/events/'.$event->id),
])
<x-layouts.app :title="$title" :og="$og">
    <div class="max-w-5xl mx-auto p-6 md:p-10">
        <section class="relative rounded-3xl overflow-hidden border shadow mb-6">
            @php($heroPhoto = (is_array($event->photos) && count($event->photos)) ? \Illuminate\Support\Str::of($event->photos[0])->replace('/original/','/thumbs/') : null)
            <img src="{{ $heroPhoto ? asset('storage/'.$heroPhoto) : asset('favicon.ico') }}" alt="{{ $event->name }}" class="w-full h-72 object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <div class="text-sm opacity-90">Evento</div>
                <div class="text-3xl font-bold">{{ $event->name }}</div>
            </div>
        </section>
        <h1 class="text-4xl font-bold text-emerald-700 mb-2">{{ $event->name }}</h1>
        @if($event->category)
            <div class="pill pill-green mb-4"><i class="fa fa-tag mr-2"></i>{{ ucfirst($event->category) }}</div>
        @endif
        <div class="text-sm text-gray-700 mb-2">Data: {{ optional($event->start_date)->format('d/m/Y') }} @if($event->end_date) – {{ $event->end_date->format('d/m/Y') }} @endif • Horário: {{ $event->start_time }} @if($event->end_time) – {{ $event->end_time }} @endif</div>
        <div class="text-sm text-gray-700 mb-4">Local: {{ $event->location }}</div>
        @if($event->arrival_info)
            <div class="p-4 rounded-xl border bg-white mb-6">
                <div class="card-section-title"><i class="fa fa-car mr-2"></i>Chegada e estacionamento</div>
                <div class="text-sm text-gray-700">{!! nl2br(e($event->arrival_info)) !!}</div>
            </div>
        @endif
        <div class="prose max-w-none mb-6">{!! $event->description !!}</div>
        @if($event->map_embed_url)
            <div class="rounded-xl overflow-hidden border mb-6">
                <iframe src="{{ $event->map_embed_url }}" width="100%" height="300" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa do evento"></iframe>
            </div>
        @endif
        <div class="grid md:grid-cols-2 gap-8">
            <div class="p-4 rounded-xl border bg-white">
                <div class="flex items-end justify-between mb-3">
                    <div class="font-semibold">Participar do evento</div>
                    <form id="participateForm" method="post" action="{{ route('events.participate', $event) }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}" />
                        <button type="submit" class="px-4 py-2 rounded-md bg-emerald-600 text-white">Participar</button>
                    </form>
                </div>
                <div class="flex items-center gap-2">
                    <form id="payForm" method="get" action="{{ route('checkout') }}">
                        <select name="method" class="rounded-md border px-3 py-2">
                            <option value="pix">PIX</option>
                            <option value="card">Cartão</option>
                            <option value="boleto">Boleto</option>
                        </select>
                        <input type="hidden" name="event" value="{{ $event->id }}" />
                        <button type="submit" class="px-4 py-2 rounded-md bg-emerald-600 text-white">Pagar</button>
                    </form>
                </div>
            </div>
            <div>
            <div class="mt-6">
                <div class="relative overflow-hidden rounded-2xl border" role="region" aria-roledescription="carousel" aria-label="Galeria de fotos do evento">
                        <div class="flex gap-6 snap-x snap-mandatory overflow-x-auto p-4" id="eventCarousel" tabindex="0">
                            @php($photos = is_array($event->photos) ? $event->photos : [])
                            @foreach($photos as $photo)
                                @php($thumb = \Illuminate\Support\Str::of($photo)->replace('/original/','/thumbs/'))
                                <img src="{{ asset('storage/'.$thumb) }}" data-full="{{ asset('storage/'.$photo) }}" alt="Foto do evento {{ $event->name }}" loading="lazy" decoding="async" fetchpriority="low" width="280" height="160" class="min-w-[280px] h-40 object-cover rounded-xl border snap-start card-hover pulse-soft" />
                            @endforeach
                        </div>
                        <div class="absolute inset-y-0 left-2 flex items-center">
                            <button id="eventPrev" class="p-2 rounded-full bg-white border shadow" aria-controls="eventCarousel" aria-label="Anterior"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                        </div>
                        <div class="absolute inset-y-0 right-2 flex items-center">
                            <button id="eventNext" class="p-2 rounded-full bg-white border shadow" aria-controls="eventCarousel" aria-label="Próxima"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
                @if(is_array($event->extra_services) && count($event->extra_services))
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="card-section-title">Serviços adicionais</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @foreach($event->extra_services as $s)
                                <div class="p-3 rounded-xl border bg-white">
                                    <div class="font-medium">{{ $s['title'] ?? 'Serviço' }}</div>
                                    <div class="text-sm text-gray-700">{{ $s['desc'] ?? '' }}</div>
                                    @if(!empty($s['price']))
                                        <div class="mt-1 text-sm text-emerald-700">Preço adicional: R$ {{ number_format((float)$s['price'],2,',','.') }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="mt-6 p-4 border rounded">
                    <h2 class="card-section-title">Ingressos</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="p-3 rounded-xl border bg-white">
                            <div class="font-medium">Ingresso padrão</div>
                            <div class="text-sm text-gray-700">Acesso a todas as atividades</div>
                            <div class="mt-1 text-lg text-emerald-700 font-semibold">R$ {{ number_format((float)($event->price ?? 0),2,',','.') }}</div>
                            @if($event->parceling_enabled && $event->parceling_max)
                                <div class="text-xs text-gray-600">Parcelamento em até {{ $event->parceling_max }}x sem juros</div>
                            @endif
                            @if($event->coupons_enabled)
                                <div class="text-xs text-gray-600">Cupons promocionais disponíveis</div>
                            @endif
                        </div>
                        @if(($event->min_age ?? null))
                        <div class="p-3 rounded-xl border bg-white">
                            <div class="font-medium">Requisitos</div>
                            <div class="text-sm text-gray-700">Idade mínima: {{ $event->min_age }} anos</div>
                            @if($event->capacity)
                                <div class="text-sm text-gray-700">Capacidade: {{ $event->capacity }} pessoas</div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @if(is_array($event->schedule) && count($event->schedule))
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="card-section-title">Programação</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @foreach($event->schedule as $item)
                                <div class="p-3 rounded-xl border bg-white flex items-start gap-3">
                                    <i class="fa fa-calendar text-emerald-700 mt-1"></i>
                                    <div>
                                        <div class="font-medium">{{ $item['title'] ?? 'Atividade' }}</div>
                                        <div class="text-sm text-gray-700">{{ $item['date'] ?? '' }} {{ $item['time'] ?? '' }}</div>
                                        @if(!empty($item['desc']))
                                            <div class="text-sm text-gray-600">{{ $item['desc'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($event->terms)
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="card-section-title">Termos e condições</h2>
                        <div class="prose max-w-none">{!! $event->terms !!}</div>
                    </div>
                @endif
                @if($event->rules)
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="card-section-title">Regras de participação</h2>
                        <div class="prose max-w-none">{!! $event->rules !!}</div>
                    </div>
                @endif
            </div>
            <span class="hidden" data-event-id="{{ $event->id }}" data-user-email="{{ auth()->user()->email ?? '' }}"></span>
        </div>
    </div>
    <script>
        const participateForm = document.getElementById('participateForm');
        if (participateForm) {
            participateForm.addEventListener('submit', function (e) {
                const loggedIn = !!document.querySelector('[data-user-email]')?.getAttribute('data-user-email');
                if (!loggedIn) {
                    e.preventDefault();
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                }
            });
        }
        const eventCarousel = document.getElementById('eventCarousel');
        const eventPrev = document.getElementById('eventPrev');
        const eventNext = document.getElementById('eventNext');
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        function scrollEventBy(delta){ if(eventCarousel) eventCarousel.scrollBy({left: delta, behavior: prefersReduced ? 'auto' : 'smooth'}); }
        if(eventPrev) eventPrev.addEventListener('click', () => scrollEventBy(-300));
        if(eventNext) eventNext.addEventListener('click', () => scrollEventBy(300));
        if(eventCarousel) eventCarousel.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') scrollEventBy(-300);
            if (e.key === 'ArrowRight') scrollEventBy(300);
        });
        // expand thumbnail to full in a modal in future; for now click opens new tab
        document.querySelectorAll('#eventCarousel img').forEach(img => {
            img.addEventListener('click', () => {
                const full = img.getAttribute('data-full');
                if (full) window.open(full, '_blank');
            });
        });
    </script>
</x-layouts.app>
