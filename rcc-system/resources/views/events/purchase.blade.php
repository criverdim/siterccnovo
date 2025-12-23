@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-600 to-purple-700 text-white">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Comprar Ingresso</h1>
                <p class="text-xl text-blue-100">{{ $event->name }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Event Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    @if($event->images && count($event->images) > 0)
                        <img src="{{ $event->images[0] }}" alt="{{ $event->name }}" class="w-full h-64 object-cover">
                    @endif
                    
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $event->name }}</h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $event->location }}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span>R$ {{ number_format($event->price, 2, ',', '.') }} por ingresso</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                @if($event->capacity)
                                    <span>{{ $event->availableTickets() }} ingressos disponíveis</span>
                                @else
                                    <span>Ingressos ilimitados</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($event->description)
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Descrição</h3>
                                <div class="text-gray-700 leading-relaxed">
                                    @php($allowedTags = '<p><br><strong><em><ul><ol><li>')
                                    {!! strip_tags((string) $event->description, $allowedTags) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Purchase Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Completar Compra</h3>
                    
                    <form id="purchase-form" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantidade de Ingressos
                            </label>
                            <select id="quantity" name="quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @for($i = 1; $i <= min(10, $event->availableTickets()); $i++)
                                    <option value="{{ $i }}">{{ $i }} ingresso{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                E-mail para recebimento
                            </label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Valor unitário:</span>
                                <span class="font-medium">R$ {{ number_format($event->price, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Quantidade:</span>
                                <span class="font-medium" id="quantity-display">1</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-gray-900" id="total-display">R$ {{ number_format($event->price, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de pagamento</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <label class="inline-flex items-center gap-2 border rounded-md px-3 py-2 cursor-pointer">
                                            <input type="radio" name="pay_method" value="pix" class="accent-emerald-600" checked>
                                            <span>PIX</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 border rounded-md px-3 py-2 cursor-pointer">
                                            <input type="radio" name="pay_method" value="credit_card" class="accent-emerald-600">
                                            <span>Cartão</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 border rounded-md px-3 py-2 cursor-pointer">
                                            <input type="radio" name="pay_method" value="boleto" class="accent-emerald-600">
                                            <span>Boleto</span>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1" id="pix-email-notice">
                                        <span class="font-bold">Importante:</span> use um e-mail válido. Você receberá o comprovante oficial do Mercado Pago e o ingresso por aqui.
                                    </p>
                                </div>
                                <div id="boleto-fields" class="mt-4 hidden space-y-3">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label for="boleto-name" class="block text-sm font-medium text-gray-700 mb-1">Nome completo</label>
                                            <input type="text" id="boleto-name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" value="{{ auth()->user()->name ?? '' }}">
                                        </div>
                                        <div>
                                            <label for="boleto-cpf" class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                                            <input type="text" id="boleto-cpf" maxlength="14" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label for="boleto-cep" class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                                            <input type="text" id="boleto-cep" maxlength="9" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label for="boleto-street" class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                                            <input type="text" id="boleto-street" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label for="boleto-number" class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                                            <input type="text" id="boleto-number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label for="boleto-neighborhood" class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                                            <input type="text" id="boleto-neighborhood" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label for="boleto-city" class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                                            <input type="text" id="boleto-city" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label for="boleto-state" class="block text-sm font-medium text-gray-700 mb-1">Estado (UF)</label>
                                            <input type="text" id="boleto-state" maxlength="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Estes dados são exigidos pelo Mercado Pago para emissão do boleto.
                                    </p>
                                </div>
                                @if($event->parceling_enabled)
                                <div id="installments-block" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Parcelamento (até {{ (int) ($event->parceling_max ?? 12) }}x)</label>
                                    <select id="installments" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                        @for($i=1; $i<=max(1,(int)($event->parceling_max ?? 12)); $i++)
                                            <option value="{{ $i }}">{{ $i }}x</option>
                                        @endfor
                                    </select>
                                </div>
                                @endif
                                @php($mpKey = config('services.mercadopago.public_key'))
                                @if(!empty($mpKey))
                                    <script src="https://sdk.mercadopago.com/js/v2"></script>
                                    <div id="mp-bricks-loading" class="text-center py-4 text-gray-500">
                                        <svg class="animate-spin h-8 w-8 mx-auto mb-2 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm">Carregando pagamentos...</span>
                                    </div>
                                    <div id="mp-card-brick" class="hidden"></div>
                                    <div id="mp-pix-brick" class="hidden"></div>
                                    <div id="mp-boleto-brick" class="hidden"></div>
                                    <div id="pix-info" class="hidden space-y-2">
                                        <div class="flex justify-center">
                                            <img id="pix-qr" class="w-48 h-48 border rounded-md object-contain" alt="QR Code PIX" />
                                        </div>
                                        <div class="flex gap-2">
                                            <input id="pix-code" class="flex-1 px-2 py-1 border rounded" readonly />
                                            <button id="pix-copy" type="button" class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition-colors">Copiar</button>
                                        </div>
                                        <p class="text-xs text-center text-gray-500">Abra o app do seu banco e escaneie o QR Code ou copie o código.</p>
                                    </div>
                                    <div id="boleto-info" class="hidden space-y-2">
                                        <div class="p-3 bg-gray-100 rounded text-center break-all font-mono text-sm" id="boleto-barcode"></div>
                                        <button id="boleto-copy" type="button" class="w-full px-3 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition-colors">Copiar código de barras</button>
                                        <div class="text-center">
                                            <a id="boleto-link" href="#" target="_blank" class="text-emerald-600 hover:underline text-sm">Visualizar Boleto PDF</a>
                                        </div>
                                    </div>
                                    <div class="pt-4">
                                        <button id="confirm-payment" type="button" class="w-full px-4 py-3 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors inline-flex items-center justify-center gap-2 font-semibold shadow-md">
                                            Confirmar Pagamento
                                        </button>
                                    </div>

                                @endif
                                <div id="purchase-error" class="mt-3 hidden text-sm text-red-600"></div>
                                <div id="purchase-success" class="mt-2 hidden text-sm text-emerald-700"></div>
                            </div>
                        </div>
                        <script>
                            (function(){
                                const qtySel = document.getElementById('quantity');
                                const qtyDisp = document.getElementById('quantity-display');
                                const totalDisp = document.getElementById('total-display');
                                const emailInput = document.getElementById('email');
                                const err = document.getElementById('purchase-error');
                                const ok = document.getElementById('purchase-success');
                                const unit = {{ (float) $event->price }};
                                const fmt = (n)=> new Intl.NumberFormat('pt-BR', { style:'currency', currency:'BRL' }).format(n);
                                const update = ()=> {
                                    const q = parseInt(qtySel.value || '1', 10);
                                    qtyDisp.textContent = String(q);
                                    totalDisp.textContent = fmt(unit * q);
                                };
                                qtySel && qtySel.addEventListener('change', update);
                                update();
                                
                                const methodInputs = document.querySelectorAll('input[name=pay_method]');
                                const instBlock = document.getElementById('installments-block');
                                const instSel = document.getElementById('installments');
                                const pixInfo = document.getElementById('pix-info');
                                const pixQr = document.getElementById('pix-qr');
                                const pixCode = document.getElementById('pix-code');
                                const pixCopy = document.getElementById('pix-copy');
                                const boletoInfo = document.getElementById('boleto-info');
                                const boletoBarcode = document.getElementById('boleto-barcode');
                                const boletoCopy = document.getElementById('boleto-copy');
                                const boletoLink = document.getElementById('boleto-link');
                                const confirmBtn = document.getElementById('confirm-payment');
                                const boletoFields = document.getElementById('boleto-fields');
                                const boletoCpf = document.getElementById('boleto-cpf');
                                const boletoCep = document.getElementById('boleto-cep');
                                const boletoState = document.getElementById('boleto-state');

                                const toggleInstallments = ()=>{
                                    const m = (document.querySelector('input[name=pay_method]:checked')?.value)||'pix';
                                    if (instBlock) instBlock.classList.toggle('hidden', m!=='credit_card');
                                    if (boletoFields) boletoFields.classList.toggle('hidden', m!=='boleto');
                                };
                                methodInputs.forEach(el=>el.addEventListener('change', ()=>{ toggleInstallments(); renderBricks(); }));
                                toggleInstallments();
                                
                                if (boletoCpf) {
                                    boletoCpf.addEventListener('input', () => {
                                        let v = boletoCpf.value.replace(/\D/g, '').slice(0, 11);
                                        if (v.length > 9) {
                                            v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
                                        } else if (v.length > 6) {
                                            v = v.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
                                        } else if (v.length > 3) {
                                            v = v.replace(/(\d{3})(\d{0,3})/, '$1.$2');
                                        }
                                        boletoCpf.value = v;
                                    });
                                }
                                
                                if (boletoCep) {
                                    boletoCep.addEventListener('input', () => {
                                        let v = boletoCep.value.replace(/\D/g, '').slice(0, 8);
                                        if (v.length > 5) {
                                            v = v.replace(/(\d{5})(\d{0,3})/, '$1-$2');
                                        }
                                        boletoCep.value = v;
                                    });
                                }
                                
                                if (boletoState) {
                                    boletoState.addEventListener('input', () => {
                                        boletoState.value = boletoState.value.replace(/[^a-zA-Z]/g, '').toUpperCase().slice(0, 2);
                                    });
                                }
                                
                                pixCopy?.addEventListener('click', () => {
                                    if(pixCode?.value) { navigator.clipboard.writeText(pixCode.value); alert('Código PIX copiado!'); }
                                });
                                boletoCopy?.addEventListener('click', () => {
                                    if(boletoBarcode?.textContent) { navigator.clipboard.writeText(boletoBarcode.textContent); alert('Código de barras copiado!'); }
                                });

                                let participationId = null;
                                let lastPaymentId = null;
                                let pixPollInterval = null;
                                let pixStartTime = null;
                                const PIX_TTL_MS = 15 * 60 * 1000;

                                function stopPixPolling() {
                                    if (pixPollInterval) {
                                        clearInterval(pixPollInterval);
                                        pixPollInterval = null;
                                    }
                                }

                                async function checkPixStatus() {
                                    if (!participationId) {
                                        return;
                                    }
                                    if (pixStartTime && (Date.now() - pixStartTime) > PIX_TTL_MS) {
                                        stopPixPolling();
                                        if (pixInfo) pixInfo.classList.add('hidden');
                                        if (pixQr) pixQr.src = '';
                                        if (pixCode) pixCode.value = '';
                                        if (ok) {
                                            ok.textContent = 'O tempo do QR Code expirou. Clique em \"Confirmar Pagamento\" para gerar um novo código.';
                                            ok.classList.remove('hidden');
                                        }
                                        confirmBtn.disabled = false;
                                        confirmBtn.innerHTML = 'Confirmar Pagamento';
                                        return;
                                    }
                                    try {
                                        const res = await fetch('/area/api/participations/' + participationId, {
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        });
                                        if (!res.ok) {
                                            return;
                                        }
                                        const data = await res.json();
                                        const st = (data.payment_status || '').toLowerCase();
                                        if (['approved','completed','paid','processed'].includes(st)) {
                                            stopPixPolling();
                                            if (ok) {
                                                ok.textContent = 'Pagamento confirmado! Seu ingresso estará em \"Meus Ingressos\" em instantes.';
                                                ok.classList.remove('hidden');
                                            }
                                            setTimeout(() => {
                                                window.location.href = "{{ route('events.my-tickets') }}";
                                            }, 2000);
                                        } else if (['rejected', 'cancelled', 'refunded'].includes(st)) {
                                            stopPixPolling();
                                            if (err) {
                                                err.textContent = 'O pagamento foi ' + st + '. Tente gerar um novo QR Code.';
                                                err.classList.remove('hidden');
                                            }
                                        }
                                    } catch (e) {
                                    }
                                }

                                function startPixPolling() {
                                    stopPixPolling();
                                    pixStartTime = Date.now();
                                    pixPollInterval = setInterval(checkPixStatus, 5000);
                                }
                                
                                async function ensureParticipation(){
                                    try {
                                        const res = await fetch("{{ route('events.participate', $event) }}", { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content || '') } });
                                        if (!res.ok) throw new Error('Erro HTTP: '+res.status);
                                        const j = await res.json();
                                        participationId = j.participation_id || participationId;
                                    } catch(e) {
                                        console.error('Falha na participação:', e);
                                        err.textContent = 'Não foi possível iniciar o processo de compra. Verifique sua conexão e recarregue a página.';
                                        err.classList.remove('hidden');
                                        document.getElementById('mp-bricks-loading')?.classList.add('hidden');
                                        throw e;
                                    }
                                }

                                @if(!empty($mpKey))
                                const mp = new MercadoPago("{{ $mpKey }}", { locale: 'pt-BR' });
                                const bricksBuilder = mp.bricks();
                                let pixBrickController = null, cardBrickController = null, boletoBrickController = null;
                                
                                function validateBoletoFields() {
                                    const nameInput = document.getElementById('boleto-name');
                                    const cpfInput = boletoCpf;
                                    const cepInput = boletoCep;
                                    const streetInput = document.getElementById('boleto-street');
                                    const numberInput = document.getElementById('boleto-number');
                                    const neighborhoodInput = document.getElementById('boleto-neighborhood');
                                    const cityInput = document.getElementById('boleto-city');
                                    const stateInput = boletoState;

                                    const fullName = (nameInput?.value || '').trim();
                                    const cpf = (cpfInput?.value || '').replace(/\D/g, '');
                                    const cep = (cepInput?.value || '').replace(/\D/g, '');
                                    const street = (streetInput?.value || '').trim();
                                    const number = (numberInput?.value || '').trim();
                                    const neighborhood = (neighborhoodInput?.value || '').trim();
                                    const city = (cityInput?.value || '').trim();
                                    const state = (stateInput?.value || '').trim();

                                    if (!fullName || fullName.split(' ').filter(Boolean).length < 2) {
                                        err.textContent = 'Informe o nome completo para emissão do boleto.';
                                        err.classList.remove('hidden');
                                        return false;
                                    }

                                    if (cpf.length !== 11) {
                                        err.textContent = 'Informe um CPF válido com 11 dígitos.';
                                        err.classList.remove('hidden');
                                        return false;
                                    }

                                    if (cep.length !== 8) {
                                        err.textContent = 'Informe um CEP válido com 8 dígitos.';
                                        err.classList.remove('hidden');
                                        return false;
                                    }

                                    if (!street || !number || !neighborhood || !city || state.length !== 2) {
                                        err.textContent = 'Preencha todos os dados de endereço para emissão do boleto.';
                                        err.classList.remove('hidden');
                                        return false;
                                    }

                                    return true;
                                }
                                
                                async function renderBricks(){
                                    const loading = document.getElementById('mp-bricks-loading');
                                    
                                    // Timeout de segurança para não ficar carregando infinitamente
                                    const loadTimeout = setTimeout(() => {
                                        if(loading && !loading.classList.contains('hidden')) {
                                            loading.classList.add('hidden');
                                            if(err && err.classList.contains('hidden')) {
                                                err.textContent = 'O carregamento do pagamento está demorando. Verifique sua conexão ou recarregue a página.';
                                                err.classList.remove('hidden');
                                            }
                                        }
                                    }, 10000); // 10 segundos

                                    try {
                                        if (!participationId) await ensureParticipation();
                                        
                                        const unitAmount = {{ (float) $event->price }};
                                        const quantity = parseInt(qtySel?.value || '1', 10) || 1;
                                        const amount = unitAmount * quantity;
                                        const method = (document.querySelector('input[name=pay_method]:checked')?.value)||'pix';
                                        
                                        if (ok) {
                                            ok.textContent = '';
                                            ok.classList.add('hidden');
                                        }
                                        
                                        // Reset UI
                                        document.getElementById('mp-card-brick')?.classList.add('hidden');
                                        pixInfo?.classList.add('hidden');
                                        boletoInfo?.classList.add('hidden');
                                        err.classList.add('hidden');
                                        
                                        // Toggle aviso de email
                                        const notice = document.getElementById('pix-email-notice');
                                        if(notice) notice.style.display = (method === 'credit_card') ? 'none' : 'block';
                                        
                                        if (method === 'credit_card') {
                                            document.getElementById('mp-card-brick')?.classList.remove('hidden');
                                            confirmBtn?.classList.add('hidden');
                                            
                                            if (!cardBrickController) {
                                                try {
                                                    cardBrickController = await bricksBuilder.create('cardPayment', 'mp-card-brick', {
                                                        initialization: { amount },
                                                        callbacks: {
                                                            onSubmit: async ({ formData }) => {
                                                                const payload = { 
                                                                    participation_id: participationId, 
                                                                    payment_method: 'credit_card', 
                                                                    payer: { 
                                                                        email: formData.cardholderEmail, 
                                                                        identification: formData.payer?.identification || { type: 'CPF', number: '' } 
                                                                    }, 
                                                                    quantity: parseInt(qtySel?.value || '1', 10) || 1,
                                                                    token: formData.token, 
                                                                    installments: (instSel?.value || 1), 
                                                                    issuer_id: formData.issuerId, 
                                                                    payment_method_id: formData.paymentMethodId 
                                                                };
                                                                
                                                                const res = await fetch("{{ route('checkout') }}", { 
                                                                    method: 'POST', 
                                                                    headers: { 
                                                                        'Content-Type': 'application/json',
                                                                        'X-Requested-With': 'XMLHttpRequest',
                                                                        'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content || '') 
                                                                    }, 
                                                                    body: JSON.stringify(payload) 
                                                                });
                                                                
                                                                const j = await res.json();
                                                                lastPaymentId = j?.payment?.id || lastPaymentId;
                                                                
                                                                if (!res.ok || j.status === 'error') {
                                                                    let msg = j.message || j.error || 'Não foi possível processar o pagamento. Tente novamente em instantes.';
                                                                    if (j.errors) msg += ': ' + Object.values(j.errors).flat().join(', ');
                                                                    
                                                                    if (msg.includes('Invalid users involved') || msg.includes('email forbidden') || msg.includes('UNAUTHORIZED')) {
                                                                        msg += '\n\nDICA: No ambiente de testes (Sandbox), você não pode usar o mesmo e-mail da conta do vendedor (Mercado Pago). Tente usar um e-mail diferente no formulário.';
                                                                    }
                                                                    
                                                                    err.textContent = msg;
                                                                    err.classList.remove('hidden');
                                                                } else {
                                                                    if (ok) {
                                                                        ok.textContent = 'Pagamento aprovado! Seus ingressos estarão em \"Meus Ingressos\" em instantes.';
                                                                        ok.classList.remove('hidden');
                                                                    }
                                                                    setTimeout(() => {
                                                                        window.location.href = "{{ route('events.my-tickets') }}";
                                                                    }, 2000);
                                                                }
                                                            },
                                                            onError: (error) => { 
                                                                console.error(error);
                                                                err.textContent = 'Falha ao processar cartão. Verifique os dados.'; 
                                                                err.classList.remove('hidden'); 
                                                            }
                                                        }
                                                    });
                                                } catch (brickError) {
                                                    console.error('Erro ao criar Brick:', brickError);
                                                    err.textContent = 'Erro ao carregar formulário de cartão: ' + (brickError.message || 'Tente recarregar a página.');
                                                    err.classList.remove('hidden');
                                                }
                                            }
                                        } else {
                                            // PIX or Boleto
                                            confirmBtn?.classList.remove('hidden');
                                        }
                                        
                                        loading?.classList.add('hidden');
                                    } catch(e) {
                                        console.error(e);
                                        loading?.classList.add('hidden');
                                        if (err.textContent === '') {
                                            err.textContent = 'Não foi possível carregar o sistema de pagamentos. Verifique sua conexão e tente novamente.';
                                            err.classList.remove('hidden');
                                        }
                                    } finally {
                                        clearTimeout(loadTimeout);
                                    }
                                }
                                
                                renderBricks();
                                
                                confirmBtn && confirmBtn.addEventListener('click', async ()=>{
                                    const method = (document.querySelector('input[name=pay_method]:checked')?.value)||'pix';
                                    err.classList.add('hidden');
                                    
                                    try {
                                        if (method === 'boleto') {
                                            const ok = validateBoletoFields();
                                            if (!ok) {
                                                return;
                                            }
                                        }

                                        confirmBtn.disabled = true;
                                        confirmBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processando...';
                                        
                                        if (!participationId) await ensureParticipation();
                                        
                                        const quantity = parseInt(qtySel?.value || '1', 10) || 1;
                                        let payload = { 
                                            participation_id: participationId, 
                                            payment_method: method,
                                            quantity,
                                            payer: { email: (document.getElementById('email')?.value || '{{ auth()->user()->email ?? 'user@local' }}') } 
                                        };
                                        
                                        // Additional data for Boleto (mocked if missing, ideally should ask user)
                                        if (method === 'boleto') {
                                            const nameInput = document.getElementById('boleto-name');
                                            const cpfInput = document.getElementById('boleto-cpf');
                                            const cepInput = document.getElementById('boleto-cep');
                                            const streetInput = document.getElementById('boleto-street');
                                            const numberInput = document.getElementById('boleto-number');
                                            const neighborhoodInput = document.getElementById('boleto-neighborhood');
                                            const cityInput = document.getElementById('boleto-city');
                                            const stateInput = document.getElementById('boleto-state');

                                            const fullName = (nameInput?.value || '').trim();
                                            const parts = fullName.split(' ').filter(Boolean);
                                            const firstName = parts[0] || 'Nome';
                                            const lastName = parts.length > 1 ? parts.slice(1).join(' ') : 'Sobrenome';

                                            const cpf = (cpfInput?.value || '').replace(/\D/g, '');
                                            const cep = (cepInput?.value || '').replace(/\D/g, '');

                                            payload.payer = {
                                                ...payload.payer,
                                                first_name: firstName,
                                                last_name: lastName,
                                                identification: { type: 'CPF', number: cpf },
                                                address: {
                                                    street_name: (streetInput?.value || ''),
                                                    street_number: (numberInput?.value || ''),
                                                    zip_code: cep,
                                                    neighborhood: (neighborhoodInput?.value || ''),
                                                    state: (stateInput?.value || '').toUpperCase(),
                                                    city: (cityInput?.value || '')
                                                }
                                            };
                                        }
                                        
                                        const res = await fetch("{{ route('checkout') }}", { 
                                            method: 'POST', 
                                            headers: { 
                                                'Content-Type': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest',
                                                'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content || '') 
                                            }, 
                                            body: JSON.stringify(payload) 
                                        });
                                        
                                        const j = await res.json();
                                         lastPaymentId = j?.payment?.id || lastPaymentId;
                                         
                                         if (!res.ok || j.status === 'error') {
                                             let msg = j.message || j.error || 'Não foi possível criar o pagamento. Tente novamente em instantes.';
                                             if (j.errors) msg += ': ' + Object.values(j.errors).flat().join(', ');
                                             
                                             if (msg.includes('Invalid users involved') || msg.includes('email forbidden') || msg.includes('UNAUTHORIZED')) {
                                                msg += '\n\nDICA: No ambiente de testes (Sandbox), você NÃO PODE usar o mesmo e-mail da conta do vendedor (Mercado Pago). Tente usar um e-mail diferente (ex: test_user_123@test.com) no campo de e-mail acima.';
                                            }
                                             
                                             throw new Error(msg);
                                         }
                                         
                                        const tx = (j?.payment?.point_of_interaction?.transaction_data) || {};
                                       
                                        if (method === 'pix') {
                                            const code = tx.qr_code || '';
                                            const base64 = tx.qr_code_base64 || '';
                                            
                                            if (code) {
                                                if(pixCode) pixCode.value = code;
                                                if(pixQr && base64) pixQr.src = 'data:image/png;base64,' + base64;
                                                pixInfo?.classList.remove('hidden');
                                                startPixPolling();
                                            }
                                        } else if (method === 'boleto') {
                                            const barcode = (j?.payment?.barcode?.content) || (tx.ticket_url) || 'Código gerado';
                                            const url = tx.ticket_url;
                                            
                                            if(boletoBarcode) boletoBarcode.textContent = barcode; // Or message
                                            if(boletoLink && url) { boletoLink.href = url; boletoLink.classList.remove('hidden'); }
                                            
                                            boletoInfo?.classList.remove('hidden');
                                            
                                            // Open boleto in new tab
                                            if(url) window.open(url, '_blank');
                                        }
                                        
                                    } catch (e) {
                                        console.error(e);
                                        err.textContent = e.message || 'Não foi possível confirmar o pagamento. Verifique os dados e tente novamente.'; 
                                        err.classList.remove('hidden');
                                    } finally {
                                        confirmBtn.disabled = false;
                                        confirmBtn.innerHTML = 'Confirmar Pagamento';
                                    }
                                });

                                @endif
                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
