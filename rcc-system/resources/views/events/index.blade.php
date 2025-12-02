@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-emerald-50 to-white">
    <section class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-emerald-700">Eventos RCC</h1>
                    <p class="mt-3 text-lg text-gray-700">Experiências transformadoras, retiros, encontros e celebrações. Participe!</p>
                    <form method="get" class="mt-6 grid grid-cols-1 md:grid-cols-6 gap-3">
                        <div class="relative md:col-span-3">
                            <input type="text" name="q" value="{{ $q ?? request('q') }}" placeholder="Buscar por nome, local..." class="rounded-xl border px-4 py-3 w-full shadow-sm focus:ring-2 focus:ring-emerald-600" aria-label="Buscar eventos">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                        <select name="paid" class="rounded-xl border px-4 py-3 shadow-sm" aria-label="Tipo">
                            <option value="">Todos os tipos</option>
                            <option value="free" @selected(($paid ?? request('paid'))==='free')>Gratuitos</option>
                            <option value="paid" @selected(($paid ?? request('paid'))==='paid')>Pagos</option>
                        </select>
                        <select name="month" class="rounded-xl border px-4 py-3 shadow-sm" aria-label="Mês">
                            <option value="">Qualquer mês</option>
                            @for($m=1;$m<=12;$m++)
                                <option value="{{ $m }}" @selected((int)($month ?? request('month'))===$m)>{{ str_pad($m,2,'0',STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                        <button class="px-5 py-3 rounded-xl bg-emerald-600 text-white shadow hover:bg-emerald-700" type="submit">Filtrar</button>
                    </form>
                </div>
                <div class="relative">
                    <div class="card-hero">
                        <img src="{{ asset('images/hero-events.jpg') }}" alt="RCC Eventos" class="w-full h-64 object-cover" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid md:grid-cols-3 gap-6" aria-live="polite">
            @forelse(($events ?? []) as $ev)
                <article class="rounded-2xl border bg-white shadow-sm overflow-hidden">
                    <img src="{{ asset('images/event-default.jpg') }}" alt="{{ $ev->name }}" class="w-full h-40 object-cover" />
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-xl font-bold text-emerald-700">{{ $ev->name }}</h2>
                            @if($ev->is_paid)
                                <span class="pill pill-green"><i class="fa fa-ticket mr-1"></i>Pago</span>
                            @else
                                <span class="pill pill-green"><i class="fa fa-heart mr-1"></i>Gratuito</span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-600 mb-3">
                            {{ optional($ev->start_date)->format('d/m/Y') }} • {{ $ev->location }}
                        </div>
                        <div class="text-sm text-gray-700 line-clamp-3">{!! $ev->description !!}</div>
                        <div class="mt-4 flex items-center justify-between">
                            <a href="{{ route('events.show', $ev) }}" class="btn btn-outline btn-sm">Detalhes</a>
                            <a href="/events/{{ $ev->id }}/participate" class="btn btn-primary btn-sm">Participar</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-gray-600">Nenhum evento encontrado.</div>
            @endforelse
        </div>
        @if(method_exists(($events ?? null),'links'))
            <div class="mt-6">{{ $events->links() }}</div>
        @endif
    </section>
</div>
@endsection
