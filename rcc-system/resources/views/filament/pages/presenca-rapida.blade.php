<x-filament::page>
    @can('manage_pastoreio')
    @php
        try {
            $groups = \App\Models\Group::orderBy('name')->get(['id','name']);
        } catch (\Throwable $e) {
            \Log::error('presenca-rapida groups load failed: '.$e->getMessage());
            $groups = collect();
        }
    @endphp
    <div class="space-y-4">
        <x-filament::section>
            <x-slot name="heading">Buscar Membro</x-slot>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" id="q" name="q" placeholder="Nome, CPF ou Telefone" class="block w-full rounded-md border border-gray-300 px-3 py-2" />
                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700">Grupo</label>
                    <select id="group" name="group" class="block w-full rounded-md border border-gray-300 px-3 py-2" required>
                        <option value="" disabled selected>Selecione o grupo</option>
                        @foreach($groups as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="searchBtn" type="button" class="inline-flex items-center justify-center rounded-md bg-cyan-600 px-4 py-2 text-white">Buscar</button>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Lançar Presença</x-slot>
            <div class="text-sm text-gray-600">Selecione o membro encontrado e lance a presença para hoje.</div>
        </x-filament::section>
    </div>
    @else
    <div class="fi-empty-state">
        <div class="text-gray-700">Acesso restrito ao Pastoreio. Solicite permissão.</div>
    </div>
    @endcan
</x-filament::page>
