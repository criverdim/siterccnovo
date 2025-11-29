@php($title = 'Login')
<x-layouts.app :title="$title">
    <div class="max-w-7xl mx-auto p-6 md:p-10">
        <h1 class="text-3xl font-bold text-emerald-700 mb-6">Área de Login</h1>
        @if(session('status'))
            <div class="mb-4 p-3 rounded bg-emerald-100 text-emerald-800">{{ session('status') }}</div>
        @endif
        <div id="react-login-app"></div>
        <div class="mt-6">
            <h2 class="text-lg font-semibold text-emerald-700 mb-2">Esqueci minha senha</h2>
            <a href="/password/forgot" class="btn btn-outline">Recuperar senha por e-mail</a>
        </div>
        <div class="mt-6 text-sm">
            Não possui cadastro? <a href="/register" class="text-emerald-700 hover:underline">Cadastre-se</a>
        </div>
    </div>
</x-layouts.app>
