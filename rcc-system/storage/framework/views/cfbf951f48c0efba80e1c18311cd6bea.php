<?php ($title = 'Crie sua Conta'); ?>
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
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-8 md:py-10 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-emerald-600/10 to-transparent -z-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-1/2 -left-24 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="lg:grid lg:grid-cols-12 h-full">
                <!-- Sidebar / Brand Section (Desktop only) -->
                <div class="hidden lg:block lg:col-span-4 bg-gradient-to-br from-emerald-600 to-teal-700 text-white p-12 relative overflow-hidden">
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold tracking-tight">RCC System</span>
                            </div>
                            <h2 class="text-3xl font-bold mb-4 leading-tight">Faça parte da nossa comunidade</h2>
                            <p class="text-emerald-100 text-lg leading-relaxed">
                                Junte-se a nós para fortalecer sua caminhada de fé, participar de grupos de oração e servir nos ministérios.
                            </p>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-semibold">Grupos de Oração</p>
                                    <p class="text-xs text-emerald-200">Encontre seu grupo</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-semibold">Ministérios</p>
                                    <p class="text-xs text-emerald-200">Sirva com seus dons</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-white/20">
                            <p class="text-sm text-emerald-100">&copy; <?php echo e(date('Y')); ?> Renovação Carismática Católica</p>
                        </div>
                    </div>
                    
                    <!-- Decorative Circles -->
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-emerald-500/30 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-teal-500/30 blur-3xl"></div>
                </div>

                <!-- Form Section -->
                <div class="lg:col-span-8 p-6 md:p-8 lg:p-10">
                    <div class="max-w-3xl mx-auto">
                        <div class="mb-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Criar nova conta</h1>
                            <p class="text-gray-500">Preencha seus dados abaixo para se cadastrar no sistema.</p>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                            <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3 animate-fade-in" role="alert">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="text-sm text-red-700">
                                    <p class="font-semibold mb-1">Encontramos alguns problemas:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div id="duplicate-alert" class="hidden mb-6 p-4 rounded-xl border flex items-start gap-3" role="alert" aria-live="polite">
                            <svg id="duplicate-icon" class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M12 3c-3.866 0-7 3.134-7 7 0 2.204 1.02 4.17 2.613 5.46L7 21l5-2 5 2-0.613-5.54C17.98 14.17 19 12.204 19 10c0-3.866-3.134-7-7-7z"></path>
                            </svg>
                            <div class="text-sm">
                                <p id="duplicate-message" class="font-semibold"></p>
                                <div class="mt-2 flex flex-wrap gap-3">
                                    <a id="duplicate-login-link" href="/login" class="text-sm font-semibold underline underline-offset-2">Ir para login</a>
                                    <a id="duplicate-reset-link" href="/password/forgot" class="text-sm font-semibold underline underline-offset-2">Recuperar senha</a>
                                </div>
                            </div>
                        </div>

                        <form id="register-form" method="post" action="/register" enctype="multipart/form-data" class="space-y-6" novalidate>
                            <?php echo csrf_field(); ?>

                            <!-- Section: Dados Pessoais -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 pb-2 border-b border-gray-100">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Dados Pessoais</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <!-- Nome -->
                                    <div class="md:col-span-2 group">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5 transition-colors group-focus-within:text-emerald-600">Nome Completo</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                            <input type="text" name="name" id="name" class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="Seu nome completo" value="<?php echo e(old('name')); ?>" required>
                                        </div>
                                        <span id="name-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>

                                    <!-- Email -->
                                    <div class="md:col-span-2 group">
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5 transition-colors group-focus-within:text-emerald-600">E-mail</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                            </div>
                                            <input type="email" name="email" id="email" class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="seu@email.com" value="<?php echo e(old('email')); ?>" required>
                                        </div>
                                        <span id="email-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>

                                    <!-- Telefone -->
                                    <div class="group">
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5 transition-colors group-focus-within:text-emerald-600">Telefone</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            </div>
                                            <input type="tel" name="phone" id="phone" class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="(00) 0000-0000" value="<?php echo e(old('phone')); ?>" required>
                                        </div>
                                        <span id="phone-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>

                                    <!-- WhatsApp -->
                                    <div class="group">
                                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1.5 transition-colors group-focus-within:text-emerald-600">WhatsApp</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            </div>
                                            <input type="tel" name="whatsapp" id="whatsapp" class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="(00) 00000-0000" value="<?php echo e(old('whatsapp')); ?>" required>
                                        </div>
                                        <span id="whatsapp-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>

                                    <!-- CPF -->
                                    <div class="group">
                                        <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1.5 transition-colors group-focus-within:text-emerald-600">CPF</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <input type="text" name="cpf" id="cpf" class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="000.000.000-00" value="<?php echo e(old('cpf')); ?>">
                                        </div>
                                        <span id="cpf-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>

                                    <!-- Data Nascimento -->
                                    <div class="group">
                                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1.5 transition-colors group-focus-within:text-emerald-600">Data de Nascimento</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <input type="date" name="birth_date" id="birth_date" class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" value="<?php echo e(old('birth_date')); ?>">
                                        </div>
                                        <span id="birth_date-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>

                                    <!-- Gênero -->
                                    <div class="group">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gênero</label>
                                        <div class="flex items-center gap-4">
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="gender" value="male" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500" <?php echo e(old('gender') === 'male' ? 'checked' : ''); ?>>
                                                <span class="text-sm text-gray-700">Masculino</span>
                                            </label>
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="gender" value="female" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500" <?php echo e(old('gender') === 'female' ? 'checked' : ''); ?>>
                                                <span class="text-sm text-gray-700">Feminino</span>
                                            </label>
                                        </div>
                                        <span id="gender-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Endereço -->
                            <div class="space-y-6 pt-6">
                                <div class="flex items-center gap-3 pb-2 border-b border-gray-100">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Endereço</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-6 gap-5">
                                    <!-- CEP -->
                                    <div class="md:col-span-2 group">
                                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-1.5">CEP</label>
                                        <input type="text" name="cep" id="cep" class="block w-full h-11 px-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="00000-000" value="<?php echo e(old('cep')); ?>">
                                    </div>

                                    <!-- Endereço -->
                                    <div class="md:col-span-4 group">
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Logradouro</label>
                                        <input type="text" name="address" id="address" class="block w-full h-11 px-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="Rua, Av..." value="<?php echo e(old('address')); ?>">
                                    </div>

                                    <!-- Número -->
                                    <div class="md:col-span-2 group">
                                        <label for="number" class="block text-sm font-medium text-gray-700 mb-1.5">Número</label>
                                        <input type="text" name="number" id="number" class="block w-full h-11 px-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="123" value="<?php echo e(old('number')); ?>">
                                    </div>

                                    <!-- Complemento -->
                                    <div class="md:col-span-4 group">
                                        <label for="complement" class="block text-sm font-medium text-gray-700 mb-1.5">Complemento</label>
                                        <input type="text" name="complement" id="complement" class="block w-full h-11 px-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="Apto, Bloco..." value="<?php echo e(old('complement')); ?>">
                                    </div>

                                    <!-- Bairro -->
                                    <div class="md:col-span-2 group">
                                        <label for="district" class="block text-sm font-medium text-gray-700 mb-1.5">Bairro</label>
                                        <input type="text" name="district" id="district" class="block w-full h-11 px-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="Bairro" value="<?php echo e(old('district')); ?>">
                                    </div>

                                    <!-- Cidade -->
                                    <div class="md:col-span-3 group">
                                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">Cidade</label>
                                        <input type="text" name="city" id="city" class="block w-full h-11 px-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="Cidade" value="<?php echo e(old('city')); ?>">
                                    </div>

                                    <!-- Estado -->
                                    <div class="md:col-span-1 group">
                                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1.5">UF</label>
                                        <input type="text" name="state" id="state" class="block w-full h-11 px-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="SP" value="<?php echo e(old('state')); ?>" maxlength="2">
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Vida Missionária -->
                            <div class="space-y-6 pt-6">
                                <div class="flex items-center gap-3 pb-2 border-b border-gray-100">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Vida Missionária</h3>
                                </div>

                                <div class="space-y-5">
                                    <!-- Grupos -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Participa de qual Grupo de Oração? <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="relative flex items-start p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 hover:border-emerald-200 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                                                    <div class="flex items-center h-5">
                                                        <input type="checkbox" name="groups[]" value="<?php echo e($g->id); ?>" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <span class="font-medium text-gray-900"><?php echo e($g->name); ?></span>
                                                    </div>
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <span id="groups-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>

                                    <!-- Servo Toggle -->
                                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">Você é servo?</h4>
                                                <p class="text-xs text-gray-500 mt-1">Marque se você já serve em algum ministério</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" id="is_servo" name="is_servo" value="1" class="sr-only peer" <?php echo e(old('is_servo') ? 'checked' : ''); ?>>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                            </label>
                                        </div>

                                        <!-- Ministérios (Hidden by default) -->
                                        <div id="ministries-container" class="hidden mt-4 pt-4 border-t border-gray-200">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Em quais ministérios você serve?</label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $ministries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <label class="flex items-center p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all cursor-pointer">
                                                        <input type="checkbox" name="ministries[]" value="<?php echo e($m->id); ?>" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                                        <span class="ml-2 text-sm text-gray-700"><?php echo e($m->name); ?></span>
                                                    </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Segurança -->
                            <div class="space-y-5 pt-6">
                                <div class="flex items-center gap-3 pb-2 border-b border-gray-100">
                                    <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Segurança</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="group">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5 transition-colors group-focus-within:text-emerald-600">Senha</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            </div>
                                            <input type="password" name="password" id="password" class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="••••••••" required>
                                        </div>
                                        
                                        <!-- Password Strength Meter -->
                                        <div class="mt-2 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                                            <div id="password-strength-bar" class="h-full bg-gray-300 w-0 transition-all duration-300"></div>
                                        </div>
                                        <div class="flex justify-between items-center mt-1">
                                            <span id="password-strength-text" class="text-xs text-gray-500">Mínimo 8 caracteres</span>
                                            <span id="password-error" class="text-xs text-red-500 hidden font-medium"></span>
                                        </div>
                                    </div>

                                    <div class="group">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5 transition-colors group-focus-within:text-emerald-600">Confirmar Senha</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-gray-50/50 focus:bg-white" placeholder="••••••••" required>
                                        </div>
                                        <span id="password_confirmation-error" class="text-xs text-red-500 mt-1 hidden font-medium"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Foto -->
                            <div class="space-y-6 pt-6">
                                <div class="flex items-center gap-3 pb-2 border-b border-gray-100">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Sua Foto</h3>
                                </div>
                                
                                <div class="flex items-center gap-5">
                                    <div class="shrink-0">
                                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300 overflow-hidden" id="photo-preview">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block">
                                            <span class="sr-only">Escolher foto de perfil</span>
                                            <input type="file" name="photo" id="photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer">
                                        </label>
                                        <p class="text-xs text-gray-500 mt-2">Recomendado: JPG ou PNG. Máximo 5MB.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Termos -->
                            <div class="pt-6 border-t border-gray-100">
                                <label class="flex items-start gap-3 p-5 rounded-xl border border-gray-200 bg-gray-50 hover:bg-white hover:border-emerald-200 transition-all cursor-pointer">
                                    <input type="checkbox" name="consent" id="consent" value="1" class="mt-1 w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" required>
                                    <span class="text-sm text-gray-600 leading-relaxed">
                                        Declaro que li e concordo com os termos de uso e política de privacidade. Autorizo o uso dos meus dados pessoais para fins de cadastro e comunicação da RCC, em conformidade com a LGPD.
                                    </span>
                                </label>
                                <span id="consent-error" class="text-xs text-red-500 mt-1 hidden font-medium block ml-1"></span>
                            </div>

                            <!-- Actions -->
                            <div class="pt-6 flex flex-col sm:flex-row items-center gap-5 justify-between">
                                <a href="/login" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Já tenho uma conta
                                </a>
                                <button type="submit" id="submit-btn" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:translate-y-[-2px] hover:brightness-110 transition-all duration-200 flex items-center justify-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed">
                                    <span>Criar minha conta</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    <svg id="loading-spinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const form = document.getElementById('register-form');
        if(!form) return;
        
        const q = (sel) => form.querySelector(sel);
        const setErr = (id, msg) => { 
            const el = document.getElementById(id); 
            if (el) {
                el.textContent = msg || '';
                el.classList.toggle('hidden', !msg);
            }
            // Add visual feedback to input
            const inputId = id.replace('-error', '');
            const input = document.getElementById(inputId);
            if (input) {
                if (msg) {
                    input.classList.add('border-red-300', 'bg-red-50');
                    input.classList.remove('border-gray-200');
                } else {
                    input.classList.remove('border-red-300', 'bg-red-50');
                    input.classList.add('border-gray-200');
                }
            }
        };

        const duplicateAlert = document.getElementById('duplicate-alert');
        const duplicateMsg = document.getElementById('duplicate-message');
        const duplicateLoginLink = document.getElementById('duplicate-login-link');
        const duplicateResetLink = document.getElementById('duplicate-reset-link');
        const duplicateIcon = document.getElementById('duplicate-icon');
        const submitBtn = document.getElementById('submit-btn');
        let hardDuplicate = false;
        let duplicateController = null;
        let duplicateTimer = null;

        const setDuplicateUI = ({ visible, hard, message, links }) => {
            hardDuplicate = !!hard;
            if (!duplicateAlert) return;

            if (!visible) {
                duplicateAlert.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            duplicateAlert.classList.remove('hidden');
            if (duplicateMsg) duplicateMsg.textContent = message || 'Conta possivelmente existente.';
            if (duplicateLoginLink && links?.login) duplicateLoginLink.href = links.login;
            if (duplicateResetLink && links?.password_forgot) duplicateResetLink.href = links.password_forgot;

            const hardClasses = ['bg-red-50', 'border-red-100', 'text-red-700'];
            const softClasses = ['bg-amber-50', 'border-amber-100', 'text-amber-800'];
            duplicateAlert.classList.remove(...hardClasses, ...softClasses);
            duplicateAlert.classList.add(...(hard ? hardClasses : softClasses));
            if (duplicateIcon) {
                duplicateIcon.classList.remove('text-red-500', 'text-amber-600');
                duplicateIcon.classList.add(hard ? 'text-red-500' : 'text-amber-600');
            }
            if (submitBtn) submitBtn.disabled = hardDuplicate;
        };

        const collectDuplicatePayload = () => {
            const payload = {};
            const take = (id) => {
                const el = document.getElementById(id);
                const v = (el?.value || '').trim();
                if (v) payload[id] = v;
            };
            take('email');
            take('cpf');
            take('phone');
            take('whatsapp');
            take('name');
            take('birth_date');
            return payload;
        };

        const runDuplicateCheck = async () => {
            const payload = collectDuplicatePayload();
            const keys = Object.keys(payload);
            const hasAny = keys.some(k => ['email','cpf','phone','whatsapp'].includes(k)) || (payload.name && payload.birth_date);
            if (!hasAny) {
                setDuplicateUI({ visible: false });
                return;
            }

            if (duplicateController) duplicateController.abort();
            duplicateController = new AbortController();

            try {
                const token = form.querySelector('input[name="_token"]')?.value;
                const res = await fetch('/api/register/check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        ...(token ? { 'X-CSRF-TOKEN': token } : {}),
                    },
                    body: JSON.stringify(payload),
                    signal: duplicateController.signal,
                });

                const data = await res.json().catch(() => null);
                if (!data) return;

                if (data.duplicate) {
                    setDuplicateUI({ visible: true, hard: true, message: data.message, links: data.links });
                    setErr('email-error', data.message || 'Já existe um cadastro com estes dados.');
                    return;
                }

                if (data.possible_duplicate) {
                    setDuplicateUI({ visible: true, hard: false, message: data.message, links: data.links });
                    return;
                }

                setDuplicateUI({ visible: false });
            } catch (e) {
                if (e?.name === 'AbortError') return;
            }
        };

        const scheduleDuplicateCheck = () => {
            if (duplicateTimer) clearTimeout(duplicateTimer);
            duplicateTimer = setTimeout(runDuplicateCheck, 450);
        };

        // Phone mask
        const maskPhone = (v) => {
            v = v.replace(/\D/g, "");
            v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
            v = v.replace(/(\d)(\d{4})$/, "$1-$2");
            return v;
        };

        // CPF mask
        const maskCpf = (v) => {
            v = v.replace(/\D/g, "");
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            return v;
        };
        
        // CEP mask
        const maskCep = (v) => {
            v = v.replace(/\D/g, "");
            v = v.replace(/(\d{5})(\d)/, "$1-$2");
            return v;
        };

        ['#phone', '#whatsapp'].forEach(sel => {
            q(sel)?.addEventListener('input', (e) => {
                e.target.value = maskPhone(e.target.value);
                e.target.setAttribute('maxlength', '15');
            });
        });

        q('#cpf')?.addEventListener('input', (e) => {
            e.target.value = maskCpf(e.target.value);
            e.target.setAttribute('maxlength', '14');
        });
        
        q('#cep')?.addEventListener('input', (e) => {
            e.target.value = maskCep(e.target.value);
            e.target.setAttribute('maxlength', '9');
        });

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
            const bar = document.getElementById('password-strength-bar');
            const txt = document.getElementById('password-strength-text');
            
            // Colors and width
            let color = 'bg-gray-300';
            let width = '0%';
            let label = 'Mínimo 8 caracteres';

            if (s.length > 0) {
                width = (sc * 20) + '%';
                if (sc <= 2) { color = 'bg-red-500'; label = 'Fraca'; }
                else if (sc <= 3) { color = 'bg-yellow-500'; label = 'Média'; }
                else { color = 'bg-emerald-500'; label = 'Forte'; }
            }

            if (bar) {
                bar.className = `h-full transition-all duration-300 ${color}`;
                bar.style.width = width;
            }
            if (txt) txt.textContent = label;
        };

        const toggleMinistries = () => {
            const wrap = document.getElementById('ministries-container');
            if (!wrap) return;
            const checked = q('#is_servo').checked;
            if (checked) {
                wrap.classList.remove('hidden');
                setTimeout(() => {
                    wrap.classList.add('opacity-100', 'translate-y-0');
                    wrap.classList.remove('opacity-0', '-translate-y-2');
                }, 10);
            } else {
                wrap.classList.add('opacity-0', '-translate-y-2');
                wrap.classList.remove('opacity-100', 'translate-y-0');
                setTimeout(() => wrap.classList.add('hidden'), 300);
            }
        };

        // Add transition classes to ministry container
        const mWrap = document.getElementById('ministries-container');
        if (mWrap) mWrap.classList.add('transition-all', 'duration-300', 'opacity-0', '-translate-y-2');

        // Photo preview
        q('#photo')?.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    preview.classList.remove('border-dashed', 'border-2');
                    preview.classList.add('border-solid', 'border');
                }
                reader.readAsDataURL(file);
            }
        });

        // Event Listeners
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
            scheduleDuplicateCheck();
        });

        ['#name','#phone','#whatsapp'].forEach(sel => {
            q(sel)?.addEventListener('input', () => {
                const v = q(sel).value || '';
                setErr(sel.replace('#','')+'-error', v ? '' : 'Campo obrigatório');
                scheduleDuplicateCheck();
            });
        });

        ['#cpf', '#birth_date'].forEach(sel => {
            q(sel)?.addEventListener('input', scheduleDuplicateCheck);
        });

        ['#email', '#cpf', '#phone', '#whatsapp', '#name', '#birth_date'].forEach(sel => {
            q(sel)?.addEventListener('blur', scheduleDuplicateCheck);
        });

        q('#is_servo')?.addEventListener('change', toggleMinistries);
        toggleMinistries(); // Init state

        form.addEventListener('submit', (e) => {
            if (hardDuplicate) {
                e.preventDefault();
                if (duplicateAlert) duplicateAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            let hasError = false;

            // Groups
            const groups = Array.from(form.querySelectorAll('input[name="groups[]"]:checked'));
            if (groups.length === 0) {
                setErr('groups-error','Selecione pelo menos um grupo de oração');
                hasError = true;
            } else {
                setErr('groups-error','');
            }

            // Required fields check
            const requiredIds = ['name', 'email', 'phone', 'whatsapp', 'password', 'password_confirmation'];
            requiredIds.forEach(id => {
                const el = document.getElementById(id);
                if (el && !el.value) {
                    setErr(id + '-error', 'Campo obrigatório');
                    hasError = true;
                }
            });

            // Email
            const em = q('#email').value || '';
            if (em && !emailRe.test(em)) {
                setErr('email-error','E-mail inválido');
                hasError = true;
            }

            // Passwords
            const p = q('#password').value || '';
            const c = q('#password_confirmation').value || '';
            if (p !== c) {
                setErr('password_confirmation-error','As senhas não coincidem');
                hasError = true;
            }

            // Consent
            if (!q('#consent').checked) {
                setErr('consent-error','É necessário concordar com os termos');
                hasError = true;
            } else {
                setErr('consent-error','');
            }

            if (hasError) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.text-red-500:not(.hidden)');
                if (firstError) {
                    firstError.parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                // Loading state
                const btn = document.getElementById('submit-btn');
                const spinner = document.getElementById('loading-spinner');
                if (btn && spinner) {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                    spinner.classList.remove('hidden');
                    // Optional: change text
                    btn.querySelector('span').textContent = 'Cadastrando...';
                }
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