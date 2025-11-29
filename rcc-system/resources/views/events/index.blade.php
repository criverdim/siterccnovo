@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-emerald-700">Eventos</h1>
            <p class="mt-2 text-lg text-gray-700">Encontros, palestras e celebrações especiais</p>
            
            <form method="get" class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="relative md:col-span-2">
                    <input type="text" name="q" value="{{ $q ?? request('q') }}" placeholder="Buscar por nome, local..." class="input input-lg input-icon-left w-full" aria-label="Buscar eventos">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
                <select name="paid" class="input" aria-label="Tipo">
                    <option value="">Todos os tipos</option>
                    <option value="free" @selected(($paid ?? request('paid'))==='free')>Gratuitos</option>
                    <option value="paid" @selected(($paid ?? request('paid'))==='paid')>Pagos</option>
                </select>
                <select name="month" class="input" aria-label="Mês">
                    <option value="">Qualquer mês</option>
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" @selected((int)($month ?? request('month'))===$m)>{{ str_pad($m,2,'0',STR_PAD_LEFT) }}</option>
                    @endfor
                </select>
                <button class="btn btn-primary" type="submit">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div id="react-events-app"></div>
    </div>
</div>
@endsection
