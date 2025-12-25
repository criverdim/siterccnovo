<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto p-6 md:p-10">
    <?php ($hasParticipation = !empty($participation_id)); ?>
    <?php ($amount = $hasParticipation ? (optional(optional(\App\Models\EventParticipation::find($participation_id))->event)->price ?? 0) : 0); ?>
    <?php ($defaultInstallments = (int) request()->integer('installments') ?: 1); ?>
    <?php ($maxInstallments = (int) request()->integer('max_installments') ?: 12); ?>
    <?php ($selectedMethod = ($payment_method ?? request()->string('method')->toString() ?? 'pix')); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($mp_public_key) && $hasParticipation): ?>
        <script src="https://www.mercadopago.com/v2/security.js" view="checkout"></script>
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script>
            const mp = new MercadoPago("<?php echo e($mp_public_key); ?>", { locale: 'pt-BR' });
            document.addEventListener('DOMContentLoaded', ()=>{
                const bricksBuilder = mp.bricks();
                const renderPixBrick = async ()=>{
                    const { create } = bricksBuilder;
                    await create('payment', 'mp-pix-brick', {
                        initialization: {
                            amount: <?php echo e((float) $amount); ?>,
                        },
                        customization: {
                            paymentMethods: { ticket: ['pix'] },
                        },
                        callbacks: {
                            onReady: ()=>{},
                            onSubmit: async ({ formData }) => {
                                const deviceId = window.MP_DEVICE_SESSION_ID || null;
                                const res = await fetch("<?php echo e(route('checkout')); ?>", {
                                    method:'POST', headers:{ 'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'') },
                                    body: JSON.stringify({
                                        participation_id: <?php echo e((int)($participation_id ?? 0)); ?>,
                                        payment_method:'pix',
                                        payer: { email: formData.payer?.email || 'user@local' },
                                        device_id: deviceId
                                    })
                                });
                                const j = await res.json();
                                if (j && j.message) {
                                    alert(j.message);
                                } else {
                                    alert('PIX status: '+(j?.status||'ok'));
                                }
                            },
                            onError: (error) => { console.error(error); alert('Falha PIX'); },
                        }
                    });
                };
                const renderBoletoBrick = async ()=>{
                    const { create } = bricksBuilder;
                    await create('payment', 'mp-boleto-brick', {
                        initialization: {
                            amount: <?php echo e((float) $amount); ?>,
                        },
                        customization: { paymentMethods: { ticket: ['boleto'] } },
                        callbacks: {
                            onReady: ()=>{},
                            onSubmit: async ({ formData }) => {
                                const addr = formData.payer?.address || {};
                                const deviceId = window.MP_DEVICE_SESSION_ID || null;
                                const res = await fetch("<?php echo e(route('checkout')); ?>", {
                                    method:'POST', headers:{ 'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'') },
                                    body: JSON.stringify({
                                        participation_id: <?php echo e((int)($participation_id ?? 0)); ?>,
                                        payment_method:'boleto',
                                        payer: {
                                            email: formData.payer?.email || 'user@local',
                                            first_name: formData.payer?.firstName || 'Teste',
                                            last_name: formData.payer?.lastName || 'Usuário',
                                            identification: {
                                                type: formData.payer?.identification?.type || 'CPF',
                                                number: formData.payer?.identification?.number || '00000000000'
                                            },
                                            address: {
                                                street_name: addr.street_name || 'Rua',
                                                street_number: addr.street_number || 'S/N',
                                                zip_code: addr.zip_code || '00000000',
                                                neighborhood: addr.neighborhood || 'Centro',
                                                state: addr.state || 'SP',
                                                city: addr.city || 'São Paulo'
                                            }
                                        },
                                        device_id: deviceId
                                    })
                                });
                                const j = await res.json();
                                if (j && j.message) {
                                    alert(j.message);
                                } else {
                                    alert('Boleto status: '+(j?.status||'ok'));
                                }
                            },
                            onError: (error) => { console.error(error); alert('Falha Boleto'); },
                        }
                    });
                };
                const renderCardBrick = async ()=>{
                    await bricksBuilder.create('cardPayment', 'mp-card-brick', {
                        initialization: {},
                        callbacks: {
                            onReady: ()=>{},
                            onSubmit: async ({ formData }) => {
                                const deviceId = window.MP_DEVICE_SESSION_ID || null;
                                const paymentMethodId = formData.payment_method_id || formData.paymentMethodId || '';
                                const issuerId = formData.issuer_id || formData.issuerId || null;
                                const payerIdentification = formData.payer?.identification || {
                                    type: formData.identificationType || 'CPF',
                                    number: formData.identificationNumber || ''
                                };
                                const res = await fetch("<?php echo e(route('checkout')); ?>", {
                                    method:'POST',
                                    headers:{
                                        'Content-Type':'application/json',
                                        'X-Requested-With':'XMLHttpRequest',
                                        'X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'')
                                    },
                                    body: JSON.stringify({
                                        participation_id: <?php echo e((int)($participation_id ?? 0)); ?>,
                                        payment_method:'credit_card',
                                        payer: {
                                            email: formData.cardholderEmail,
                                            identification: payerIdentification
                                        },
                                        token: formData.token,
                                        installments: (formData.installments || <?php echo e($defaultInstallments); ?>),
                                        issuer_id: issuerId,
                                        payment_method_id: paymentMethodId,
                                        device_id: deviceId
                                    })
                                });
                                const j = await res.json();
                                if (j && j.message) {
                                    alert(j.message);
                                } else {
                                    alert('Status: '+(j?.status||'ok'));
                                }
                            },
                            onError: (error) => { console.error(error); alert('Falha ao processar cartão'); },
                        }
                    });
                };
                const method = '<?php echo e($selectedMethod); ?>';
                if (method === 'credit_card') { renderCardBrick(); }
                else if (method === 'pix') { renderPixBrick(); }
                else if (method === 'boleto') { renderBoletoBrick(); }
            });
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <h1 class="text-3xl font-bold text-emerald-700 mb-4">Checkout</h1>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$hasParticipation): ?>
        <div class="p-4 rounded-xl border bg-white">
            <div class="text-gray-700">Selecione um evento e faça login para continuar.</div>
        </div>
    <?php else: ?>
        <div class="p-4 rounded-xl border bg-white">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($payment_method)): ?>
            <form method="post" action="<?php echo e(route('checkout')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="participation_id" value="<?php echo e($participation_id); ?>" />
                <div>
                    <label class="block text-sm font-medium text-gray-700">Método de pagamento</label>
                    <select name="payment_method" class="rounded-md border px-3 py-2">
                        <option value="pix" <?php if($payment_method==='pix'): echo 'selected'; endif; ?>>PIX</option>
                        <option value="credit_card" <?php if($payment_method==='credit_card'): echo 'selected'; endif; ?>>Cartão</option>
                        <option value="boleto" <?php if($payment_method==='boleto'): echo 'selected'; endif; ?>>Boleto</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pagador</label>
                    <div class="grid md:grid-cols-2 gap-3">
                        <input type="text" name="payer[name]" placeholder="Nome" class="rounded-md border px-3 py-2" required />
                        <input type="email" name="payer[email]" placeholder="Email" class="rounded-md border px-3 py-2" required />
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($maxInstallments>1): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Parcelas (até <?php echo e($maxInstallments); ?>x)</label>
                    <select name="installments" class="rounded-md border px-3 py-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=1;$i<=$maxInstallments;$i++): ?>
                            <option value="<?php echo e($i); ?>" <?php if($i===$defaultInstallments): echo 'selected'; endif; ?>><?php echo e($i); ?>x</option>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <button type="submit" class="px-4 py-2 rounded-md bg-emerald-600 text-white">Confirmar Pagamento</button>
            </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($payment_method ?? '')==='credit_card' && !empty($mp_public_key) && $hasParticipation): ?>
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-2">Cartão de Crédito (Bricks)</h2>
                    <div id="mp-card-brick"></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(($payment_method ?? '')==='pix' && !empty($mp_public_key) && $hasParticipation): ?>
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-2">PIX (Bricks)</h2>
                    <div id="mp-pix-brick"></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(($payment_method ?? '')==='boleto' && !empty($mp_public_key) && $hasParticipation): ?>
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-2">Boleto (Bricks)</h2>
                    <div id="mp-boleto-brick"></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/rcc-system/resources/views/checkout/index.blade.php ENDPATH**/ ?>