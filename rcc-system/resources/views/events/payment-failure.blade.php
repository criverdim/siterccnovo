@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-red-600 to-red-700 text-white">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Pagamento Não Aprovado</h1>
                <p class="text-xl text-red-100">Houve um problema com seu pagamento</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Error Message -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Pagamento não aprovado</h2>
                <p class="text-gray-600">Infelizmente não foi possível processar seu pagamento</p>
            </div>

            <!-- Event Details -->
            <div class="border rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalhes da Tentativa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Evento</div>
                        <div class="font-medium text-gray-900">{{ $event->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Data</div>
                        <div class="font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Local</div>
                        <div class="font-medium text-gray-900">{{ $event->location }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Valor</div>
                        <div class="font-medium text-gray-900">R$ {{ number_format($payment->amount, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Common Issues -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-yellow-800 mb-3">Possíveis causas do problema:</h3>
                <ul class="text-yellow-700 space-y-2">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Cartão com limite insuficiente
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Dados do cartão incorretos
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Problemas temporários com o servidor de pagamento
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Cartão bloqueado ou com restrições
                    </li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.purchase', $event) }}" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-center">
                    <i class="fas fa-redo mr-2"></i>Tentar Novamente
                </a>
                <a href="{{ route('events.show', $event) }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-center">
                    <i class="fas fa-info-circle mr-2"></i>Ver Detalhes do Evento
                </a>
            </div>
        </div>
    </div>
</div>
@endsection