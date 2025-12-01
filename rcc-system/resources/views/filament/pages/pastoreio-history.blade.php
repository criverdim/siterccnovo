@php($user = auth()->user())
<x-filament::page>
    @can('manage_pastoreio')
        <div class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">Linha do Tempo</x-slot>
                <div class="grid gap-4">
                    {{-- Presenças --}}
                    <x-filament::card>
                        <x-slot name="header">Presenças</x-slot>
                        <div class="text-sm text-gray-600">Últimos registros de presença do usuário</div>
                    </x-filament::card>
                    {{-- Pedidos de Oração --}}
                    <x-filament::card>
                        <x-slot name="header">Pedidos de Oração</x-slot>
                        <div class="text-sm text-gray-600">Em breve</div>
                    </x-filament::card>
                    {{-- Eventos --}}
                    <x-filament::card>
                        <x-slot name="header">Eventos</x-slot>
                        <div class="text-sm text-gray-600">Participações e inscrições</div>
                    </x-filament::card>
                    {{-- Acompanhamentos --}}
                    <x-filament::card>
                        <x-slot name="header">Acompanhamentos</x-slot>
                        <div class="text-sm text-gray-600">Relatos e visitas</div>
                    </x-filament::card>
                </div>
            </x-filament::section>
        </div>
    @else
        <div class="fi-empty-state">
            <div class="text-gray-700">Acesso restrito ao Pastoreio. Solicite permissão.</div>
        </div>
    @endcan
</x-filament::page>
