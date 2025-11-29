<x-layouts.app>
    <div class="max-w-md mx-auto p-6">
        <h1 class="text-2xl font-bold text-emerald-700 mb-4">Alterar senha</h1>
        @if(session('status'))<div class="p-3 rounded bg-emerald-50 text-emerald-700 mb-3">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="p-3 rounded bg-red-50 text-red-700 mb-3">{{ $errors->first() }}</div>@endif
        <form method="post" action="/area/password/change" class="grid gap-3">
            @csrf
            <label class="grid gap-1">
                <span class="text-sm">Senha atual</span>
                <input type="password" name="current_password" class="input" required />
            </label>
            <label class="grid gap-1">
                <span class="text-sm">Nova senha</span>
                <input type="password" name="password" class="input" required />
            </label>
            <label class="grid gap-1">
                <span class="text-sm">Confirmar nova senha</span>
                <input type="password" name="password_confirmation" class="input" required />
            </label>
            <div class="text-xs text-gray-600">A senha deve ter no mínimo 8 caracteres, incluir maiúscula, minúscula, número e símbolo.</div>
            <button class="btn btn-primary">Alterar senha</button>
        </form>
    </div>
</x-layouts.app>

