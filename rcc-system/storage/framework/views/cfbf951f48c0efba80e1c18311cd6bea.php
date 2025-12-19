<?php ($title = 'Cadastro'); ?>
<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title)]); ?>
    <div class="max-w-4xl mx-auto p-6 md:p-10">
        <h1 class="text-3xl font-bold text-emerald-700 mb-6">Cadastro</h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="mb-4 p-3 rounded bg-red-50 text-red-700" role="alert" aria-live="polite"><?php echo e($errors->first()); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <form id="register-form" method="post" action="/register" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Nome completo</span>
                        <input id="name" name="name" type="text" class="input" required aria-describedby="name-error" />
                    </label>
                    <span id="name-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Email</span>
                        <input id="email" name="email" type="email" class="input" required aria-describedby="email-error" />
                    </label>
                    <span id="email-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Telefone</span>
                        <input id="phone" name="phone" type="tel" class="input" required aria-describedby="phone-error" />
                    </label>
                    <span id="phone-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">WhatsApp</span>
                        <input id="whatsapp" name="whatsapp" type="tel" class="input" required aria-describedby="whatsapp-error" />
                    </label>
                    <span id="whatsapp-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Data de nascimento</span>
                        <input id="birth_date" name="birth_date" type="date" class="input" aria-describedby="birth_date-error" />
                    </label>
                    <span id="birth_date-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">CPF</span>
                        <input id="cpf" name="cpf" type="text" class="input" aria-describedby="cpf-error" />
                    </label>
                    <span id="cpf-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">CEP</span>
                        <input id="cep" name="cep" type="text" class="input" aria-describedby="cep-error" />
                    </label>
                    <span id="cep-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Endereço</span>
                        <input id="address" name="address" type="text" class="input" aria-describedby="address-error" />
                    </label>
                    <span id="address-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Número</span>
                        <input id="number" name="number" type="text" class="input" aria-describedby="number-error" />
                    </label>
                    <span id="number-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Complemento</span>
                        <input id="complement" name="complement" type="text" class="input" aria-describedby="complement-error" />
                    </label>
                    <span id="complement-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Bairro</span>
                        <input id="district" name="district" type="text" class="input" aria-describedby="district-error" />
                    </label>
                    <span id="district-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Cidade</span>
                        <input id="city" name="city" type="text" class="input" aria-describedby="city-error" />
                    </label>
                    <span id="city-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Estado</span>
                        <input id="state" name="state" type="text" class="input" aria-describedby="state-error" />
                    </label>
                    <span id="state-error" class="text-sm text-red-600"></span>
                </div>
            </div>
            <div>
                <label class="text-sm text-gray-700 block mb-2">Grupos de oração</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="groups[]" value="<?php echo e($g->id); ?>" aria-label="Selecionar grupo <?php echo e($g->name); ?>">
                            <span><?php echo e($g->name); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <span id="groups-error" class="text-sm text-red-600"></span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Senha</span>
                        <input id="password" name="password" type="password" class="input" required aria-describedby="password-error password-strength" />
                    </label>
                    <div id="password-strength" class="text-xs text-gray-600 mt-1"></div>
                    <span id="password-error" class="text-sm text-red-600"></span>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block">
                        <span class="block mb-1">Confirmação de senha</span>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="input" required aria-describedby="password_confirmation-error" />
                    </label>
                    <span id="password_confirmation-error" class="text-sm text-red-600"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" id="is_servo" name="is_servo" value="1">
                        <span>Sou servo</span>
                    </label>
                </div>
                <div id="ministries-container" class="hidden">
                    <label class="text-sm text-gray-700 block mb-2">Ministérios</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $ministries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="ministries[]" value="<?php echo e($m->id); ?>">
                                <span><?php echo e($m->name); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
            <div>
                <label for="photo" class="text-sm text-gray-700">Foto (opcional)</label>
                <input id="photo" name="photo" type="file" accept="image/*" class="input" />
            </div>
            <div class="flex items-start gap-2">
                <label class="inline-flex items-start gap-2">
                    <input id="consent" type="checkbox" name="consent" value="1" required aria-describedby="consent-error" />
                    <span class="text-sm text-gray-700">Consentimento LGPD</span>
                </label>
            </div>
            <span id="consent-error" class="text-sm text-red-600"></span>
            <div class="flex items-center gap-4">
                <button type="submit" class="btn btn-primary rounded-xl font-semibold">Cadastrar</button>
                <a href="/login" class="text-emerald-700 hover:underline">Já tenho conta</a>
            </div>
        </form>
    </div>
    <script>
    (function(){
        const form = document.getElementById('register-form');
        if(!form) return;
        const q = (sel) => form.querySelector(sel);
        const setErr = (id, msg) => { const el = document.getElementById(id); if (el) el.textContent = msg || ''; };
        const emailRe = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        const strength = (s) => {
            let score = 0;
            if (s.length >= 8) score++;
            if (/[A-Z]/.test(s)) score++;
            if (/[a-z]/.test(s)) score++;
            if (/\d/.test(s)) score++;
            if (/[^A-Za-z0-9]/.test(s)) score++;
            return score;
        };
        const updateStrength = () => {
            const s = q('#password').value || '';
            const sc = strength(s);
            const map = ['Muito fraca','Fraca','Média','Forte','Muito forte'];
            const msg = s.length ? ('Força da senha: ' + map[Math.max(0, sc-1)]) : '';
            const el = document.getElementById('password-strength'); if (el) el.textContent = msg;
        };
        const toggleMinistries = () => {
            const wrap = document.getElementById('ministries-container');
            if (!wrap) return;
            wrap.classList.toggle('hidden', !q('#is_servo').checked);
        };
        ['#password','#password_confirmation'].forEach(sel => {
            q(sel)?.addEventListener('input', () => {
                const p = q('#password').value || '';
                const c = q('#password_confirmation').value || '';
                setErr('password_confirmation-error', (c && p !== c) ? 'As senhas não coincidem' : '');
                updateStrength();
            });
        });
        q('#email')?.addEventListener('input', () => {
            const v = q('#email').value || '';
            setErr('email-error', v && !emailRe.test(v) ? 'E-mail inválido' : '');
        });
        ['#name','#phone','#whatsapp'].forEach(sel => {
            q(sel)?.addEventListener('input', () => {
                const v = q(sel).value || '';
                setErr(sel.replace('#','')+'-error', v ? '' : 'Campo obrigatório');
            });
        });
        q('#is_servo')?.addEventListener('change', toggleMinistries);
        toggleMinistries();
        form.addEventListener('submit', (e) => {
            // valida grupos selecionados
            const groups = Array.from(form.querySelectorAll('input[name="groups[]"]:checked'));
            if (groups.length === 0) {
                e.preventDefault();
                setErr('groups-error','Selecione pelo menos um grupo de oração');
                form.querySelector('input[name="groups[]"]')?.focus();
                return;
            } else {
                setErr('groups-error','');
            }
            // valida email
            const em = q('#email').value || '';
            if (!emailRe.test(em)) {
                e.preventDefault();
                setErr('email-error','E-mail inválido');
                q('#email').focus();
                return;
            } else {
                setErr('email-error','');
            }
            // confirma senha
            const p = q('#password').value || '';
            const c = q('#password_confirmation').value || '';
            if (p !== c) {
                e.preventDefault();
                setErr('password_confirmation-error','As senhas não coincidem');
                q('#password_confirmation').focus();
                return;
            } else {
                setErr('password_confirmation-error','');
            }
            // consent
            if (!q('#consent').checked) {
                e.preventDefault();
                setErr('consent-error','É necessário concordar com o uso dos dados conforme a LGPD');
                q('#consent').focus();
                return;
            } else {
                setErr('consent-error','');
            }
        }, { passive: false });
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
<?php /**PATH /var/www/html/rcc-system/resources/views/auth/register.blade.php ENDPATH**/ ?>