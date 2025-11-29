@php($title = 'Calendário')
<x-layouts.app :title="$title">
    <div class="max-w-7xl mx-auto p-6 md:p-10">
        <h1 class="text-3xl font-bold text-emerald-700">Calendário</h1>
        <p class="mt-2 text-gray-700">Veja os próximos eventos em um calendário simples.</p>
        <div id="react-calendar-app" class="mt-6"></div>
    </div>
</x-layouts.app>
