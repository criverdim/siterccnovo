@php($title = 'Evento - '.$event->name)
<x-layouts.app :title="$title">
    <div class="max-w-5xl mx-auto p-6 md:p-10">
        <h1 class="text-4xl font-bold text-emerald-700 mb-2">{{ $event->name }}</h1>
        @if($event->category)
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-sm mb-4">
                <i class="fa fa-tag mr-2"></i>{{ ucfirst($event->category) }}
            </div>
        @endif
        <div class="text-sm text-gray-700 mb-2">Data: {{ optional($event->start_date)->format('d/m/Y') }} @if($event->end_date) – {{ $event->end_date->format('d/m/Y') }} @endif • Horário: {{ $event->start_time }} @if($event->end_time) – {{ $event->end_time }} @endif</div>
        <div class="text-sm text-gray-700 mb-4">Local: {{ $event->location }}</div>
        @if($event->arrival_info)
            <div class="p-4 rounded-xl border bg-white mb-6">
                <div class="font-semibold text-emerald-700 mb-2"><i class="fa fa-car mr-2"></i>Chegada e estacionamento</div>
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
            <div id="react-event-show-app"></div>
            <div>
                <div class="mt-6">
                    <div class="relative overflow-hidden rounded-2xl border" role="region" aria-roledescription="carousel" aria-label="Galeria de fotos do evento">
                        <div class="flex gap-6 snap-x snap-mandatory overflow-x-auto p-4" id="eventCarousel" tabindex="0">
                            @foreach(($event->photos ?? []) as $photo)
                                <img src="{{ asset('storage/'.$photo) }}" alt="Foto do evento {{ $event->name }}" loading="lazy" decoding="async" fetchpriority="low" width="280" height="160" class="min-w-[280px] h-40 object-cover rounded-xl border snap-start" />
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
                        <h2 class="font-semibold mb-4">Serviços adicionais</h2>
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
                @if($event->terms)
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="font-semibold mb-2">Termos e condições</h2>
                        <div class="prose max-w-none">{!! $event->terms !!}</div>
                    </div>
                @endif
                @if($event->rules)
                    <div class="mt-6 p-4 border rounded">
                        <h2 class="font-semibold mb-2">Regras de participação</h2>
                        <div class="prose max-w-none">{!! $event->rules !!}</div>
                    </div>
                @endif
            </div>
            <span class="hidden" data-event-id="{{ $event->id }}" data-user-email="{{ auth()->user()->email ?? '' }}"></span>
        </div>
    </div>
    <script>
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
    </script>
</x-layouts.app>
