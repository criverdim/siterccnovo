<div class="p-4 md:p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="text-3xl font-bold text-emerald-700">Usuários</div>
                <div class="text-sm text-gray-600">Gerencie membros, servos e administradores</div>
            </div>
            <div class="flex gap-2">
                <a href="/admin/users/create" class="btn btn-primary btn-lg inline-flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-user-plus" class="w-5 h-5" />
                    <span>Novo Usuário</span>
                </a>
                <a href="/admin/users" class="btn btn-outline inline-flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-arrow-path" class="w-5 h-5" />
                    <span>Atualizar</span>
                </a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card stat-total">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <x-filament::icon icon="heroicon-o-users" class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total</div>
                        <div class="stat-value">{{ number_format(\App\Models\User::count()) }}</div>
                    </div>
                </div>
            </div>
            <div class="stat-card stat-servos">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <x-filament::icon icon="heroicon-o-hand-thumb-up" class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Servos</div>
                        <div class="stat-value">{{ number_format(\App\Models\User::where('is_servo', true)->count()) }}</div>
                    </div>
                </div>
            </div>
            <div class="stat-card stat-ativos">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <x-filament::icon icon="heroicon-o-check-circle" class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Ativos</div>
                        <div class="stat-value">{{ number_format(\App\Models\User::where('status','active')->count()) }}</div>
                    </div>
                </div>
            </div>
            <div class="stat-card stat-admins">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <x-filament::icon icon="heroicon-o-shield-check" class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Administradores</div>
                        <div class="stat-value">{{ number_format(\App\Models\User::where('role','admin')->count()) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
