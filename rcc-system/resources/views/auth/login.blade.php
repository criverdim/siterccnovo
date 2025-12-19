@php($title = 'Login')
<x-layouts.app :title="$title" :minimal="request()->boolean('minimal', false)" :hideFooter="true">
    @php($logoUrl = data_get($siteSettings ?? [], 'brand_logo'))
    @if(! $logoUrl)
        @php($hasSettings = \Illuminate\Support\Facades\Schema::hasTable('settings'))
        @if($hasSettings)
            @php($brand = \App\Models\Setting::where('key','brand')->first())
            @php($path = data_get($brand?->value ?? [], 'logo'))
            @if($path)
                @php($base = (string) config('filesystems.disks.public.url'))
                @php($logoUrl = $base ? rtrim($base,'/').'/'.ltrim($path,'/') : \Illuminate\Support\Facades\Storage::disk('public')->url($path))
            @endif
        @endif
    @endif
    <section class="relative min-h-[60vh] overflow-hidden" style="min-height:60vh;">
        <script>
            (function(){
                var usp = new URLSearchParams(window.location.search);
                var r = usp.get('redirect') || '';
                if (r === '/admin/dashboard') {
                    var url = new URL(window.location.href);
                    url.searchParams.set('redirect','/admin');
                    window.history.replaceState({},'',url.toString());
                }
            })();
        </script>
        <div class="absolute inset-0 z-10 bg-gradient-to-r from-emerald-900/70 to-emerald-800/50" style="background:linear-gradient(90deg, rgba(6,78,59,.7), rgba(11,122,72,.5));"></div>
        <img src="https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20church%20interior%2C%20soft%20bokeh%2C%20warm%20atmospheric%20lighting%2C%20clean%20background%2C%20no%20text%2C%20no%20watermark%2C%20professional%20photography%20style&image_size=landscape_16_9" alt="Login RCC" class="absolute inset-0 w-full h-full object-cover" />
        <div class="relative z-20 min-h-[60vh] flex items-center justify-center" style="min-height:calc(100vh - 300px);display:flex;align-items:center;justify-content:center;padding:20px 0;">
            <div class="w-full max-w-md px-4" style="max-width:440px;margin:0 auto;">
                <div class="rounded-2xl border border-gray-200 bg-white shadow p-5 md:p-6 text-center">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" class="site-logo mx-auto mb-4 max-h-20" />
                    @else
                        <div class="mx-auto mb-4 w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xl font-bold">R</div>
                    @endif
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">Bem-vindo</h1>
                    <p class="text-gray-700 mb-5">Acesse sua conta para continuar</p>
                    @if($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-700">{{ $errors->first() }}</div>
                    @endif
                    @if(session('status'))
                        <div class="mb-4 p-3 rounded bg-emerald-100 text-emerald-800">{{ session('status') }}</div>
                    @endif
                    @php($redir = request()->string('redirect')->toString())
                    @php($redir = ($redir === '/admin/dashboard') ? '/admin' : ($redir ?: (request()->string('area')->toString() === 'admin' ? '/admin' : '')))
                    <form method="post" action="/login" class="grid gap-3 text-left" style="gap:12px;">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ $redir }}" />
                        <div class="grid gap-2">
                            <label class="text-sm text-gray-700">E-mail</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border bg-white px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-emerald-600" required />
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <label class="text-sm text-gray-700">Senha</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="password" name="password" class="w-full rounded-xl border bg-white px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-emerald-600" required />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-xl font-semibold">Entrar</button>
                    </form>
                    <div class="mt-6 flex items-center justify-between text-gray-700">
                        <a href="/password/forgot" class="text-emerald-700 hover:underline">Esqueci minha senha</a>
                        <a href="/" class="hover:text-emerald-700">Voltar para a página inicial</a>
                    </div>
                </div>
                <div class="text-center mt-6 text-sm text-white">
                    Não possui cadastro? <a href="/register" class="underline">Cadastre-se</a>
                </div>
            </div>
        </div>
    </section>
    <script>
        (function(){
                    document.addEventListener('DOMContentLoaded', function(){
                        var f = document.querySelector('form[action="/login"]');
                        if(!f) return;
                        f.addEventListener('submit', function(){
                            var redEl = f.querySelector('input[name="redirect"]');
                            var red = redEl ? (redEl.value || '') : '';
                            if (red && red.indexOf('/admin') === 0) {
                                var areaInput = f.querySelector('input[name="area"]');
                                if (!areaInput) {
                                    areaInput = document.createElement('input');
                                    areaInput.type = 'hidden';
                                    areaInput.name = 'area';
                                    f.appendChild(areaInput);
                                }
                                areaInput.value = 'admin';
                            }
                        });
                    });
                })();
            </script>
</x-layouts.app>
