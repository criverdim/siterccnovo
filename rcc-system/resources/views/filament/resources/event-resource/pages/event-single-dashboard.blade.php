<x-filament-panels::page>
    <style>
        :root {
            --brand-green-364: #006036;
            --brand-green-364-alt: #009049;
            --brand-yellow-7406: #fdc800;
            --brand-yellow-7406-alt: #fdfa00;
            --brand-blue-548: #004058;
            --brand-blue-552: #b8d0dc;
        }
        
        /* Modern Card Base */
        .dashboard-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            border-color: var(--brand-blue-552);
        }

        /* Hero Header */
        .dashboard-hero {
            background: linear-gradient(135deg, var(--brand-green-364) 0%, var(--brand-green-364-alt) 100%);
            border-radius: 20px;
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        .dashboard-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            filter: blur(60px);
        }

        /* Stat Cards Customization */
        .brand-stat-green .fi-wi-stats-overview-stat-icon {
            color: var(--brand-green-364) !important;
            background: rgba(0, 96, 54, 0.1) !important;
        }
        .brand-stat-gold .fi-wi-stats-overview-stat-icon {
            color: #b45309 !important; /* Darker version of gold for text readability */
            background: rgba(253, 200, 0, 0.15) !important;
        }
        .brand-stat-blue .fi-wi-stats-overview-stat-icon {
            color: var(--brand-blue-548) !important;
            background: rgba(0, 64, 88, 0.1) !important;
        }
        .brand-stat-yellow .fi-wi-stats-overview-stat-icon {
            color: #b45309 !important;
            background: rgba(253, 250, 0, 0.2) !important;
        }
        
        .fi-wi-stats-overview-stat {
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.04) !important;
        }

        /* Table Styles */
        .custom-table-header {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        .custom-table-row:hover {
            background-color: #f8fafc;
        }
        
        /* Typography */
        .text-brand-green { color: var(--brand-green-364); }
        .text-brand-blue { color: var(--brand-blue-548); }
        
        /* Buttons */
        .btn-brand-primary {
            background-color: white;
            color: var(--brand-green-364);
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-brand-primary:hover {
            background-color: #f0fdf4;
            transform: translateY(-1px);
        }
        
        /* Grid Layout helpers */
        .layout-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }
        @media (min-width: 1024px) {
            .layout-grid {
                grid-template-columns: repeat(12, 1fr);
            }
            .col-span-8 { grid-column: span 8; }
            .col-span-4 { grid-column: span 4; }
        }
    </style>

    <div class="space-y-6">
        <!-- Header Section -->
        @php($isActive = $this->record->isActive())
        @php(
            $imgCandidate = $this->record->folder_image ?: $this->record->featured_image
        )
        @php(
            $path = is_array($imgCandidate)
                ? (isset($imgCandidate[0]) ? $imgCandidate[0] : (isset($imgCandidate['path']) ? $imgCandidate['path'] : null))
                : (string) $imgCandidate
        )
        @php(
            $isUrl = is_string($path) && str_starts_with($path, 'http')
        )
        @php(
            $normalized = is_string($path)
                ? (str_starts_with($path, 'storage/')
                    ? substr($path, 8)
                    : (str_starts_with($path, '/storage/')
                        ? substr($path, 9)
                        : ltrim($path, '/')))
                : null
        )
        @php(
            $normalized = is_string($normalized) && !str_contains($normalized, '/') ? ('events/folder/'.$normalized) : $normalized
        )
        @php(
            $imgUrl = $isUrl ? $path : ($normalized ? ('/storage/'.ltrim($normalized, '/')) : null)
        )
        <section class="dashboard-hero shadow-lg">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-start gap-4">
                    @if($imgUrl)
                        <div class="shrink-0">
                            <img src="{{ $imgUrl }}" alt="{{ $this->record->name }}" class="h-16 w-16 md:h-20 md:w-20 rounded-xl object-cover shadow-md border border-white/30 bg-white/10">
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $isActive ? 'bg-white/20 text-white' : 'bg-red-500/20 text-white' }}">
                                {{ $isActive ? 'Evento Ativo' : 'Evento Inativo' }}
                            </span>
                            <span class="text-white/80 text-sm flex items-center gap-1">
                                <x-filament::icon icon="heroicon-o-calendar" class="h-4 w-4" />
                                {{ optional($this->record->start_date)->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $this->record->name }}</h1>
                        <div class="flex items-center gap-2 text-white/90">
                            <x-filament::icon icon="heroicon-o-map-pin" class="h-5 w-5" />
                            <span class="text-lg">{{ $this->record->location }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ \App\Filament\Resources\EventResource\Pages\EditEvent::getUrl(['record' => $this->record]) }}" 
                       class="btn-brand-primary flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-pencil-square" class="h-5 w-5" />
                        Editar Evento
                    </a>
                </div>
            </div>
        </section>

        <!-- Widgets Section -->
        <div class="w-full">
            <x-filament-widgets::widgets
                :columns="1"
                :data="[]"
                :widgets="[\App\Filament\Resources\EventResource\Widgets\EventSingleStats::class]"
            />
        </div>

        <!-- Main Content Grid -->
        <div class="layout-grid">
            <!-- Left Column: Chart & Table -->
            <div class="col-span-8 space-y-6">
                <!-- Sales Chart -->
                <div class="dashboard-card p-1">
                     @livewire(\App\Filament\Resources\EventResource\Widgets\EventSingleSalesChart::class, ['record' => $this->record])
                </div>

                <!-- Recent Registrations -->
                <div class="dashboard-card overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <div class="p-2 rounded-lg bg-blue-50 text-brand-blue">
                                <x-filament::icon icon="heroicon-o-users" class="h-5 w-5" style="color: var(--brand-blue-548)" />
                            </div>
                            <h3 class="font-bold text-gray-800">Últimos Inscritos</h3>
                        </div>
                        <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">100 recentes</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase text-gray-500 font-semibold bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4">Participante</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Data</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($this->record->participations()->latest()->limit(100)->get() as $p)
                                    <tr class="hover:bg-gray-50/80 transition-colors text-sm">
                                        <td class="px-6 py-3 font-medium text-gray-700">
                                            {{ optional($p->user)->name ?? '—' }}
                                            <div class="text-xs text-gray-400 font-normal">{{ optional($p->user)->email }}</div>
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $this->getStatusColor($p->payment_status) }}">
                                                <x-filament::icon :icon="$this->getStatusIcon($p->payment_status)" class="h-3.5 w-3.5" />
                                                {{ ucfirst($p->payment_status ?? '—') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-gray-500">{{ optional($p->created_at)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & Summary -->
            <div class="col-span-4 space-y-6">
                <!-- Event Info -->
                <div class="dashboard-card p-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-information-circle" class="h-5 w-5 text-brand-green" style="color: var(--brand-green-364)" />
                        Detalhes
                    </h3>
                    
                    @php(
                        $totalApproved = $this->record->payments()->where('status', 'approved')->sum('amount')
                    )

                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <span class="text-sm text-gray-500">Capacidade</span>
                            <span class="font-bold text-gray-800">{{ $this->record->capacity ?? '∞' }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <span class="text-sm text-gray-500">Categoria</span>
                            <span class="font-bold text-gray-800">{{ $this->record->category ?? 'Geral' }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <span class="text-sm text-gray-500">Preço</span>
                            <span class="font-bold text-brand-green" style="color: var(--brand-green-364)">
                                {{ $this->record->is_paid ? ('R$ ' . number_format($this->record->price ?? 0, 2, ',', '.')) : 'Gratuito' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <span class="text-sm text-gray-500">Total Vendido</span>
                            <span class="font-bold text-brand-green" style="color: var(--brand-green-364)">
                                {{ $totalApproved > 0 ? ('R$ ' . number_format($totalApproved, 2, ',', '.')) : 'R$ 0,00' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="dashboard-card p-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-banknotes" class="h-5 w-5" style="color: #b45309" />
                        Pagamentos
                    </h3>

                    <div class="space-y-4">
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <span class="text-xs font-semibold inline-block text-brand-green">PIX</span>
                                <span class="text-xs font-semibold inline-block text-gray-600">{{ $paymentsSummary['pix'] }}</span>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-emerald-100">
                                <div style="width:{{ $paymentsSummary['total'] > 0 ? ($paymentsSummary['pix'] / $paymentsSummary['total']) * 100 : 0 }}%; background-color: var(--brand-green-364)" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center"></div>
                            </div>
                        </div>

                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <span class="text-xs font-semibold inline-block text-blue-600">Cartão de Crédito</span>
                                <span class="text-xs font-semibold inline-block text-gray-600">{{ $paymentsSummary['card'] }}</span>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-100">
                                <div style="width:{{ $paymentsSummary['total'] > 0 ? ($paymentsSummary['card'] / $paymentsSummary['total']) * 100 : 0 }}%; background-color: var(--brand-blue-548)" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center"></div>
                            </div>
                        </div>
                        
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <span class="text-xs font-semibold inline-block text-yellow-600">Dinheiro</span>
                                <span class="text-xs font-semibold inline-block text-gray-600">{{ $paymentsSummary['cash'] }}</span>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-yellow-100">
                                <div style="width:{{ $paymentsSummary['total'] > 0 ? ($paymentsSummary['cash'] / $paymentsSummary['total']) * 100 : 0 }}%; background-color: var(--brand-yellow-7406)" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
