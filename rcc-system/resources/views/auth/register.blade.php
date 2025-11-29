@php($title = 'Cadastro')
<x-layouts.app :title="$title">
    <div class="max-w-3xl mx-auto p-6 md:p-10">
        <h1 class="text-3xl font-bold text-emerald-700 mb-6">Cadastro Único</h1>
        <div id="react-register-app"></div>
    </div>
    @foreach($groups as $g)
        <span class="hidden" data-group-option data-id="{{ $g->id }}" data-name="{{ $g->name }}"></span>
    @endforeach
    @foreach($ministries as $m)
        <span class="hidden" data-ministry-option data-id="{{ $m->id }}" data-name="{{ $m->name }}"></span>
    @endforeach
</x-layouts.app>
