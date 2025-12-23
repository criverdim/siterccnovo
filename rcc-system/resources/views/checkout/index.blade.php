@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 md:p-10">
    @php($hasParticipation = !empty($participation_id))
    @php($amount = $hasParticipation ? (optional(optional(\App\Models\EventParticipation::find($participation_id))->event)->price ?? 0) : 0)
    @php($defaultInstallments = (int) request()->integer('installments') ?: 1)
    @php($maxInstallments = (int) request()->integer('max_installments') ?: 12)
    @php($selectedMethod = ($payment_method ?? request()->string('method')->toString() ?? 'pix'))
    @if(!empty($mp_public_key) && $hasParticipation)
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script>
            const mp = new MercadoPago("{{ $mp_public_key }}", { locale: 'pt-BR' });
            document.addEventListener('DOMContentLoaded', ()=>{
                const bricksBuilder = mp.bricks();
                const renderPixBrick = async ()=>{
                    const { create } = bricksBuilder;
                    await create('payment', 'mp-pix-brick', {
                        initialization: {
                            amount: {{ (float) $amount }},
                        },
                        customization: {
                            paymentMethods: { ticket: ['pix'] },
                        },
                        callbacks: {
                            onReady: ()=>{},
                            onSubmit: async ({ formData }) => {
                                const res = await fetch("{{ route('checkout') }}", {
                                    method:'POST', headers:{ 'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'') },
                                    body: JSON.stringify({ participation_id: {{ (int)($participation_id ?? 0) }}, payment_method:'pix', payer: { email: formData.payer?.email || 'user@local' } })
                                });
                                const j = await res.json();
                                alert('PIX status: '+(j?.status||'ok'));
                            },
                            onError: (error) => { console.error(error); alert('Falha PIX'); },
                        }
                    });
                };
                const renderBoletoBrick = async ()=>{
                    const { create } = bricksBuilder;
                    await create('payment', 'mp-boleto-brick', {
                        initialization: {
                            amount: {{ (float) $amount }},
                        },
                        customization: { paymentMethods: { ticket: ['boleto'] } },
                        callbacks: {
                            onReady: ()=>{},
                            onSubmit: async ({ formData }) => {
                                const addr = formData.payer?.address || {};
                                const res = await fetch("{{ route('checkout') }}", {
                                    method:'POST', headers:{ 'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'') },
                                    body: JSON.stringify({ participation_id: {{ (int)($participation_id ?? 0) }}, payment_method:'boleto', payer: { email: formData.payer?.email || 'user@local', first_name: formData.payer?.firstName || 'Teste', last_name: formData.payer?.lastName || 'Usuário', identification: { type: formData.payer?.identification?.type || 'CPF', number: formData.payer?.identification?.number || '00000000000' }, address: { street_name: addr.street_name || 'Rua', street_number: addr.street_number || 'S/N', zip_code: addr.zip_code || '00000000', neighborhood: addr.neighborhood || 'Centro', state: addr.state || 'SP', city: addr.city || 'São Paulo' } })
                                });
                                const j = await res.json();
                                alert('Boleto status: '+(j?.status||'ok'));
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
                                const res = await fetch("{{ route('checkout') }}", {
                                    method:'POST', headers:{ 'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'') },
                                    body: JSON.stringify({ participation_id: {{ (int)($participation_id ?? 0) }}, payment_method:'credit_card', payer: { email: formData.cardholderEmail, identification: formData.payer?.identification || { type:'CPF', number:'' } }, token: formData.token, installments: (formData.installments || {{ $defaultInstallments }}), issuer_id: formData.issuerId, payment_method_id: formData.paymentMethodId })
                                });
                                const j = await res.json();
                                alert('Status: '+(j?.status||'ok'));
                            },
                            onError: (error) => { console.error(error); alert('Falha ao processar cartão'); },
                        }
                    });
                };
                const method = '{{ $selectedMethod }}';
                if (method === 'credit_card') { renderCardBrick(); }
                else if (method === 'pix') { renderPixBrick(); }
                else if (method === 'boleto') { renderBoletoBrick(); }
            });
        </script>
    @endif
    <h1 class="text-3xl font-bold text-emerald-700 mb-4">Checkout</h1>
    @if(!$hasParticipation)
        <div class="p-4 rounded-xl border bg-white">
            <div class="text-gray-700">Selecione um evento e faça login para continuar.</div>
        </div>
    @else
        <div class="p-4 rounded-xl border bg-white">
            @if(empty($payment_method))
            <form method="post" action="{{ route('checkout') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="participation_id" value="{{ $participation_id }}" />
                <div>
                    <label class="block text-sm font-medium text-gray-700">Método de pagamento</label>
                    <select name="payment_method" class="rounded-md border px-3 py-2">
                        <option value="pix" @selected($payment_method==='pix')>PIX</option>
                        <option value="credit_card" @selected($payment_method==='credit_card')>Cartão</option>
                        <option value="boleto" @selected($payment_method==='boleto')>Boleto</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pagador</label>
                    <div class="grid md:grid-cols-2 gap-3">
                        <input type="text" name="payer[name]" placeholder="Nome" class="rounded-md border px-3 py-2" required />
                        <input type="email" name="payer[email]" placeholder="Email" class="rounded-md border px-3 py-2" required />
                    </div>
                </div>
                @if($maxInstallments>1)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Parcelas (até {{ $maxInstallments }}x)</label>
                    <select name="installments" class="rounded-md border px-3 py-2">
                        @for($i=1;$i<=$maxInstallments;$i++)
                            <option value="{{ $i }}" @selected($i===$defaultInstallments)>{{ $i }}x</option>
                        @endfor
                    </select>
                </div>
                @endif
                <button type="submit" class="px-4 py-2 rounded-md bg-emerald-600 text-white">Confirmar Pagamento</button>
            </form>
            @endif
            @if(($payment_method ?? '')==='credit_card' && !empty($mp_public_key) && $hasParticipation)
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-2">Cartão de Crédito (Bricks)</h2>
                    <div id="mp-card-brick"></div>
                </div>
            @endif
            @if(($payment_method ?? '')==='pix' && !empty($mp_public_key) && $hasParticipation)
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-2">PIX (Bricks)</h2>
                    <div id="mp-pix-brick"></div>
                </div>
            @endif
            @if(($payment_method ?? '')==='boleto' && !empty($mp_public_key) && $hasParticipation)
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-2">Boleto (Bricks)</h2>
                    <div id="mp-boleto-brick"></div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
