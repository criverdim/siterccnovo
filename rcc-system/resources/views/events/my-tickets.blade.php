@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-600 to-purple-700 text-white">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Meus Ingressos</h1>
                <p class="text-xl text-blue-100">Gerencie seus ingressos para eventos</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($participations->count() === 0)
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Você ainda não tem ingressos</h3>
                <p class="text-gray-600 mb-6">Explore nossos eventos e garanta seu ingresso!</p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                    </svg>
                    Ver Eventos
                </a>
            </div>
        @else
            <!-- Tickets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($participations as $participation)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <!-- Event Image -->
                            @if($participation->event?->featured_image)
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ Storage::url($participation->event->featured_image) }}" alt="{{ $participation->event->name }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            
                            <!-- Ticket Info -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $participation->event?->name }}</h3>
                                
                                <!-- Event Date -->
                                <div class="flex items-center text-gray-600 mb-3">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm">
                                        {{ optional($participation->event?->start_date)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                
                                <!-- Location -->
                                <div class="flex items-center text-gray-600 mb-3">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm">{{ $participation->event?->location }}</span>
                                </div>
                                
                                <!-- Ticket Code -->
                                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                    <div class="text-xs text-gray-500 mb-1">UUID do Ingresso</div>
                                    <div class="font-mono text-sm font-bold text-gray-900">{{ $participation->ticket_uuid ?? '—' }}</div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="space-y-2">
                                    <a href="{{ route('events.show', $participation->event) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Ver Detalhes
                                    </a>
                                    
                                    @if($participation->ticket_uuid)
                                    <a href="/area/ticket/{{ $participation->ticket_uuid }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Baixar PDF
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($participations->hasPages())
                <div class="flex justify-center">
                    {{ $participations->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
