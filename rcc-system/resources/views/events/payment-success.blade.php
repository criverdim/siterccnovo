@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-green-600 to-green-700 text-white">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Pagamento Aprovado!</h1>
                <p class="text-xl text-green-100">Seu ingresso foi confirmado com sucesso</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Parabéns! Sua compra foi aprovada</h2>
                <p class="text-gray-600">Você receberá um e-mail com os detalhes do seu ingresso</p>
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
                        <div class="text-sm text-gray-500 mb-1">Quantidade</div>
                        <div class="font-medium text-gray-900">{{ $payment->quantity }} ingresso{{ $payment->quantity > 1 ? 's' : '' }}</div>
                    </div>
                </div>
            </div>

            <!-- Tickets -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Seus Ingressos</h3>
                <div class="space-y-4">
                    @foreach($tickets as $ticket)
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-900">Ingresso #{{ $loop->iteration }}</div>
                                    <div class="text-sm text-gray-500">Código: {{ $ticket->ticket_code }}</div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('events.ticket.download', ['payment' => $payment, 'ticket' => $ticket]) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                        <i class="fas fa-download mr-1"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.my-tickets') }}" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-center">
                    <i class="fas fa-ticket-alt mr-2"></i>Ver Meus Ingressos
                </a>
                <a href="{{ route('events.show', $event) }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-center">
                    <i class="fas fa-info-circle mr-2"></i>Ver Detalhes do Evento
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
