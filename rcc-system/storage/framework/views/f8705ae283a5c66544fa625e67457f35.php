<?php ($title = 'Login'); ?>
<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $title,'minimal' => request()->boolean('minimal', false),'hideFooter' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'minimal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->boolean('minimal', false)),'hideFooter' => true]); ?>
    <?php ($logoUrl = data_get($siteSettings ?? [], 'brand_logo')); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $logoUrl): ?>
        <?php ($hasSettings = \Illuminate\Support\Facades\Schema::hasTable('settings')); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSettings): ?>
            <?php ($brand = \App\Models\Setting::where('key','brand')->first()); ?>
            <?php ($path = data_get($brand?->value ?? [], 'logo')); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($path): ?>
                <?php ($base = (string) config('filesystems.disks.public.url')); ?>
                <?php ($logoUrl = $base ? rtrim($base,'/').'/'.ltrim($path,'/') : \Illuminate\Support\Facades\Storage::disk('public')->url($path)); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                        <img src="<?php echo e($logoUrl); ?>" alt="Logo" class="site-logo mx-auto mb-4 max-h-20" />
                    <?php else: ?>
                        <div class="mx-auto mb-4 w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xl font-bold">R</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">Bem-vindo</h1>
                    <p class="text-gray-700 mb-5">Acesse sua conta para continuar</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-700"><?php echo e($errors->first()); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                        <div class="mb-4 p-3 rounded bg-emerald-100 text-emerald-800"><?php echo e(session('status')); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php ($redir = request()->string('redirect')->toString()); ?>
                    <?php ($redir = ($redir === '/admin/dashboard') ? '/admin' : ($redir ?: (request()->string('area')->toString() === 'admin' ? '/admin' : ''))); ?>
                    <form method="post" action="/login" class="grid gap-3 text-left" style="gap:12px;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="redirect" value="<?php echo e($redir); ?>" />
                        <div class="grid gap-2">
                            <label class="text-sm text-gray-700">E-mail</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full rounded-xl border bg-white px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-emerald-600" required />
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/rcc-system/resources/views/auth/login.blade.php ENDPATH**/ ?>