@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Admin Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Administrativo</h1>
                    <p class="text-sm text-gray-500">Painel de controle e métricas do sistema RCC</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Última atualização: {{ now()->format('d/m/Y H:i') }}</span>
                    <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Atualizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="react-admin-app"></div>
    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Membros</p>
                        <p class="text-3xl font-bold text-gray-900">1,247</p>
                        <p class="text-sm text-emerald-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+12% este mês
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-emerald-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Eventos Ativos</p>
                        <p class="text-3xl font-bold text-gray-900">23</p>
                        <p class="text-sm text-emerald-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+5 esta semana
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Grupos de Oração</p>
                        <p class="text-3xl font-bold text-gray-900">18</p>
                        <p class="text-sm text-emerald-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+2 este mês
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-praying-hands text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Inscrições Hoje</p>
                        <p class="text-3xl font-bold text-gray-900">89</p>
                        <p class="text-sm text-emerald-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+23% ontem
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Event Participation Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Participação em Eventos</h3>
                    <select class="text-sm border border-gray-300 rounded-lg px-3 py-1">
                        <option>Últimos 6 meses</option>
                        <option>Este ano</option>
                        <option>2023</option>
                    </select>
                </div>
                <div class="h-64 flex items-end justify-between space-x-2">
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-emerald-600 rounded-t" style="height: 60%"></div>
                        <span class="text-xs text-gray-500 mt-2">Jul</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-emerald-600 rounded-t" style="height: 75%"></div>
                        <span class="text-xs text-gray-500 mt-2">Ago</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-emerald-600 rounded-t" style="height: 45%"></div>
                        <span class="text-xs text-gray-500 mt-2">Set</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-emerald-600 rounded-t" style="height: 80%"></div>
                        <span class="text-xs text-gray-500 mt-2">Out</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-emerald-600 rounded-t" style="height: 65%"></div>
                        <span class="text-xs text-gray-500 mt-2">Nov</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-emerald-700 rounded-t" style="height: 90%"></div>
                        <span class="text-xs text-gray-600 font-semibold mt-2">Dez</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-600">Média de participação: </span>
                    <span class="text-lg font-semibold text-gray-900">156 pessoas/mês</span>
                </div>
            </div>

            <!-- Group Attendance Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Frequência por Grupo</h3>
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-emerald-600 rounded-full mr-2"></div>
                            <span class="text-gray-600">Presentes</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                            <span class="text-gray-600">Ausentes</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Grupo São José</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-sm text-gray-900 font-medium">85%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Grupo Maria</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                            <span class="text-sm text-gray-900 font-medium">78%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Grupo João Paulo II</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                            <span class="text-sm text-gray-900 font-medium">92%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Grupo Divino Espírito</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 67%"></div>
                            </div>
                            <span class="text-sm text-gray-900 font-medium">67%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Grupo Jesus Misericordioso</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 73%"></div>
                            </div>
                            <span class="text-sm text-gray-900 font-medium">73%</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Média geral</span>
                        <span class="text-lg font-semibold text-gray-900">79%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Atividades Recentes</h3>
                <a href="#" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">Ver todas</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-plus text-emerald-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Novo membro registrado</p>
                        <p class="text-xs text-gray-500">Maria Silva se cadastrou no grupo São José</p>
                        <p class="text-xs text-gray-400 mt-1">2 minutos atrás</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-plus text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Novo evento criado</p>
                        <p class="text-xs text-gray-500">Retiro de Carnaval foi agendado para fevereiro</p>
                        <p class="text-xs text-gray-400 mt-1">1 hora atrás</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-praying-hands text-purple-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Grupo de oração realizado</p>
                        <p class="text-xs text-gray-500">Grupo Maria teve encontro com 45 participantes</p>
                        <p class="text-xs text-gray-400 mt-1">3 horas atrás</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-orange-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Pagamento confirmado</p>
                        <p class="text-xs text-gray-500">João Santos pagou inscrição para o retiro</p>
                        <p class="text-xs text-gray-400 mt-1">5 horas atrás</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
