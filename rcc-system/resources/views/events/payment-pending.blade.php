@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-yellow-600 to-orange-700 text-white">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Pagamento em Análise</h1>
                <p class="text-xl text-yellow-100">Seu pagamento está sendo processado</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Pending Message -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Aguardando confirmação</h2>
                <p class="text-gray-600">Seu pagamento está em análise e será confirmado em breve</p>
            </div>

            <!-- Event Details -->
            <div class="border rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalhes do Evento</h3>
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

            <!-- Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">O que acontece agora?</h3>
                <ul class="text-blue-700 space-y-2">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Seu pagamento está sendo analisado pelo Mercado Pago
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Você receberá um e-mail assim que o pagamento for aprovado
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Isso geralmente leva poucos minutos, mas pode demorar até 24 horas
                    </li>
                </ul>
            </div>

            <!-- Next Steps -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Próximos passos</h3>
                <div class="space-y-3 text-gray-600">
                    <p><strong>1.</strong> Verifique seu e-mail (inclusive a pasta de spam)</p>
                    <p><strong>2.</strong> Acesse "Meus Ingressos" para acompanhar o status</p>
                    <p><strong>3.</strong> Se não receber confirmação em 24 horas, entre em contato conosco</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.my-tickets') }}" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-center">
                    <i class="fas fa-ticket-alt mr-2"></i>Meus Ingressos
                </a>
                <a href="{{ route('events.show', $event) }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-center">
                    <i class="fas fa-info-circle mr-2"></i>Ver Detalhes do Evento
                </a>
            </div>
        </div>
    </div>
</div>
@endsection