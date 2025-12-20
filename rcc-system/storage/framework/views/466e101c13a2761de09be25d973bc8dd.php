<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-600 to-purple-700 text-white">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Comprar Ingresso</h1>
                <p class="text-xl text-blue-100"><?php echo e($event->name); ?></p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Event Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->images && count($event->images) > 0): ?>
                        <img src="<?php echo e($event->images[0]); ?>" alt="<?php echo e($event->name); ?>" class="w-full h-64 object-cover">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4"><?php echo e($event->name); ?></h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span><?php echo e(\Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i')); ?></span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span><?php echo e($event->location); ?></span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span>R$ <?php echo e(number_format($event->price, 2, ',', '.')); ?> por ingresso</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span><?php echo e($event->availableTickets()); ?> ingressos disponíveis</span>
                            </div>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->description): ?>
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Descrição</h3>
                                <div class="text-gray-700 leading-relaxed">
                                    <?php ($allowedTags = '<p><br><strong><em><ul><ol><li>'); ?>
                                    <?php echo strip_tags((string) $event->description, $allowedTags); ?>

                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Purchase Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Completar Compra</h3>
                    
                    <form id="purchase-form" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantidade de Ingressos
                            </label>
                            <select id="quantity" name="quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= min(10, $event->availableTickets()); $i++): ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?> ingresso<?php echo e($i > 1 ? 's' : ''); ?></option>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                E-mail para recebimento
                            </label>
                            <input type="email" id="email" name="email" value="<?php echo e(auth()->user()->email ?? ''); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Valor unitário:</span>
                                <span class="font-medium">R$ <?php echo e(number_format($event->price, 2, ',', '.')); ?></span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Quantidade:</span>
                                <span class="font-medium" id="quantity-display">1</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-gray-900" id="total-display">R$ <?php echo e(number_format($event->price, 2, ',', '.')); ?></span>
                            </div>
                        </div>
                        </form>
                        <div class="mt-6">
                            <form action="<?php echo e(route('events.payment.process', $event)); ?>" method="POST" id="purchase-submit-form" class="space-y-4">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="quantity" id="quantity-hidden" value="1">
                                <input type="hidden" name="email" id="email-hidden" value="<?php echo e(auth()->user()->email ?? ''); ?>">
                                <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors inline-flex items-center justify-center gap-2 whitespace-nowrap">
                                    <i class="fas fa-ticket-alt mr-2"></i>Pagar com Mercado Pago
                                </button>
                            </form>
                            <div id="purchase-error" class="mt-3 hidden text-sm text-red-600"></div>
                        </div>
                        <script>
                            (function(){
                                const qtySel = document.getElementById('quantity');
                                const qtyDisp = document.getElementById('quantity-display');
                                const totalDisp = document.getElementById('total-display');
                                const emailInput = document.getElementById('email');
                                const form = document.getElementById('purchase-submit-form');
                                const err = document.getElementById('purchase-error');
                                const unit = <?php echo e((float) $event->price); ?>;
                                const fmt = (n)=> new Intl.NumberFormat('pt-BR', { style:'currency', currency:'BRL' }).format(n);
                                const update = ()=> {
                                    const q = parseInt(qtySel.value || '1', 10);
                                    qtyDisp.textContent = String(q);
                                    totalDisp.textContent = fmt(unit * q);
                                };
                                qtySel && qtySel.addEventListener('change', update);
                                update();
                                form && form.addEventListener('submit', async (e)=>{
                                    e.preventDefault();
                                    err.classList.add('hidden');
                                    document.getElementById('quantity-hidden').value = qtySel.value;
                                    document.getElementById('email-hidden').value = emailInput.value;
                                    try {
                                        const fd = new FormData(form);
                                        const res = await fetch(form.action, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content || '') }, body: fd });
                                        const j = await res.json().catch(()=>({}));
                                        if (res.ok && j?.init_point) {
                                            window.location.href = j.init_point;
                                            return;
                                        }
                                        err.textContent = (j?.error) || `Erro ${res.status}`;
                                        err.classList.remove('hidden');
                                    } catch (e) {
                                        err.textContent = 'Falha de rede. Tente novamente.';
                                        err.classList.remove('hidden');
                                    }
                                });
                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/rcc-system/resources/views/events/purchase.blade.php ENDPATH**/ ?>