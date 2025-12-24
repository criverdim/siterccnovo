<x-filament::page>
    <div class="space-y-6">
        <div class="rounded-2xl bg-gradient-to-r from-blue-700 via-emerald-600 via-orange-500 to-amber-400 text-white shadow-xl">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Dashboard Administrativo de Eventos</h1>
                <p class="mt-1 text-amber-100">Visão em tempo real de pagamentos, ingressos e check-ins</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="rounded-xl overflow-hidden bg-white shadow-sm ring-1 ring-gray-200">
                <div class="h-2 bg-gradient-to-r from-red-600 via-orange-500 to-amber-400"></div>
                <div class="p-4">
                    @livewire(App\Filament\Widgets\DashboardAlerts::class)
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-xl overflow-hidden bg-white shadow-sm ring-1 ring-gray-200">
                    <div class="h-2 bg-gradient-to-r from-amber-500 via-yellow-400 to-yellow-300"></div>
                    <div class="p-4">
                        @livewire(App\Filament\Widgets\EventPaymentsStats::class)
                    </div>
                </div>
                <div class="rounded-xl overflow-hidden bg-white shadow-sm ring-1 ring-gray-200">
                    <div class="h-2 bg-gradient-to-r from-emerald-600 via-green-500 to-green-400"></div>
                    <div class="p-4">
                        @livewire(App\Filament\Widgets\EventTicketsStats::class)
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-xl overflow-hidden bg-white shadow-sm ring-1 ring-gray-200">
                    <div class="h-2 bg-gradient-to-r from-blue-700 via-indigo-600 to-blue-500"></div>
                    <div class="p-4">
                        @livewire(App\Filament\Widgets\EventSalesLineChart::class)
                    </div>
                </div>
                <div class="rounded-xl overflow-hidden bg-white shadow-sm ring-1 ring-gray-200">
                    <div class="h-2 bg-gradient-to-r from-rose-600 via-red-600 to-orange-500"></div>
                    <div class="p-4">
                        @livewire(App\Filament\Widgets\EventCheckinsBarChart::class)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fi-page { background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); }
    </style>
</x-filament::page>
