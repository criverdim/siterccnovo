<x-layouts.app>
    <div class="max-w-md mx-auto p-6">
        <h1 class="text-2xl font-bold text-emerald-700 mb-4">Recuperar senha</h1>
        @if(session('status'))<div class="p-3 rounded bg-emerald-50 text-emerald-700 mb-3">{{ session('status') }}</div>@endif
        <form method="post" action="/password/email" class="grid gap-3">
            @csrf
            <label class="grid gap-1">
                <span class="text-sm">Email cadastrado</span>
                <input type="email" name="email" class="input" required />
            </label>
            <button class="btn btn-primary">Enviar link</button>
        </form>
    </div>
</x-layouts.app>

