@php($title = 'Área do Membro')
<x-layouts.app :title="$title">
    <div class="max-w-7xl mx-auto p-6 md:p-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-emerald-700">Área do Membro</h1>
            <form action="/logout" method="post">
                @csrf
                <button class="px-4 py-2 rounded border border-emerald-600 text-emerald-700">Sair</button>
            </form>
        </div>
        <div id="react-member-app"></div>
    </div>
</x-layouts.app>
