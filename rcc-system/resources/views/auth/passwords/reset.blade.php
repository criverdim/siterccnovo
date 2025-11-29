<x-layouts.app>
    <div class="max-w-md mx-auto p-6">
        <h1 class="text-2xl font-bold text-emerald-700 mb-4">Redefinir senha</h1>
        @if($errors->any())<div class="p-3 rounded bg-red-50 text-red-700 mb-3">{{ $errors->first() }}</div>@endif
        <form method="post" action="/password/reset" class="grid gap-3">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />
            <input type="hidden" name="email" value="{{ $email }}" />
            <label class="grid gap-1">
                <span class="text-sm">Nova senha</span>
                <input type="password" name="password" class="input" required />
            </label>
            <label class="grid gap-1">
                <span class="text-sm">Confirmar senha</span>
                <input type="password" name="password_confirmation" class="input" required />
            </label>
            <div class="text-xs text-gray-600">A senha deve ter no mínimo 8 caracteres, incluir maiúscula, minúscula, número e símbolo.</div>
            <button class="btn btn-primary">Redefinir</button>
        </form>
    </div>
</x-layouts.app>

