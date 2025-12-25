<x-filament::page>
    <div class="max-w-6xl mx-auto p-2 md:p-4 space-y-6">
        
        {{-- Header com Controles de Câmera --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <x-heroicon-o-qr-code class="w-6 h-6 text-primary-600"/>
                    Scanner de Ingressos
                </h2>
                <p class="text-sm text-gray-500">Aponte a câmera para o QR Code do ingresso</p>
            </div>
            
            <div class="flex bg-gray-100 p-1 rounded-xl">
                <button id="camera-back-btn" type="button" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                    <x-heroicon-o-camera class="w-4 h-4"/>
                    <span>Traseira</span>
                </button>
                <button id="camera-front-btn" type="button" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                    <x-heroicon-o-user class="w-4 h-4"/>
                    <span>Frontal</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- Coluna do Scanner --}}
            <div class="lg:col-span-7 space-y-4">
                <div class="relative overflow-hidden rounded-3xl shadow-xl border-4 border-white bg-black aspect-[3/4] md:aspect-video ring-1 ring-gray-200">
                    <div id="reader" class="w-full h-full object-cover"></div>
                    
                    {{-- Overlay de Scan (apenas visual) --}}
                    <div class="absolute inset-0 pointer-events-none border-[30px] border-black/30">
                        <div class="w-full h-full border-2 border-white/50 relative">
                            <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-primary-500 rounded-tl-lg"></div>
                            <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-primary-500 rounded-tr-lg"></div>
                            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-primary-500 rounded-bl-lg"></div>
                            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-primary-500 rounded-br-lg"></div>
                        </div>
                    </div>
                </div>

                <div id="error-feedback" class="hidden transform transition-all duration-300">
                    <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-100 rounded-xl text-red-700 shadow-sm">
                        <x-heroicon-s-x-circle class="w-6 h-6 shrink-0"/>
                        <span class="font-medium text-sm md:text-base"></span>
                    </div>
                </div>
            </div>

            {{-- Coluna de Resultado e Histórico --}}
            <div class="lg:col-span-5 space-y-6">
                
                {{-- Card de Resultado --}}
                <div id="scan-result-container" class="hidden transform transition-all duration-500 ease-out translate-y-4 opacity-0">
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 relative">
                        {{-- Faixa de Status --}}
                        <div id="status-header" class="p-6 text-center text-white relative overflow-hidden">
                            <div class="relative z-10 flex flex-col items-center gap-2">
                                <div id="status-icon" class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-3xl shadow-inner">
                                    ✓
                                </div>
                                <h3 id="status-title" class="text-2xl font-bold tracking-tight"></h3>
                                <p id="status-subtitle" class="text-white/90 text-sm font-medium"></p>
                            </div>
                        </div>

                        <div class="p-6">
                            {{-- Informações do Participante --}}
                            <div class="flex flex-col items-center -mt-12 mb-6">
                                <div id="user-photo-wrapper" class="w-24 h-24 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-100 mb-3 hidden relative z-20">
                                    <img id="user-photo" src="" alt="Participante" class="w-full h-full object-cover">
                                </div>
                                <h4 id="user-name" class="text-xl font-bold text-gray-900 text-center leading-tight"></h4>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-1">Participante</span>
                            </div>

                            {{-- Detalhes do Ingresso --}}
                            <div class="bg-gray-50 rounded-2xl p-5 space-y-4 border border-gray-100">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase">Evento</p>
                                        <p id="event-name" class="text-sm font-bold text-gray-800 leading-snug"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase">Data</p>
                                        <p id="event-date" class="text-sm font-bold text-gray-800"></p>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 border-dashed my-2"></div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase">Ingresso</p>
                                        <p id="ticket-type" class="text-sm font-bold text-primary-600"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase">Código</p>
                                        <p id="ticket-code" class="text-sm font-mono font-bold text-gray-600"></p>
                                    </div>
                                </div>

                                <div class="bg-white p-3 rounded-xl border border-gray-100 flex items-center justify-between">
                                    <span class="text-xs text-gray-500 font-medium">Check-in às</span>
                                    <span id="checkin-time" class="text-sm font-bold text-gray-900"></span>
                                </div>
                            </div>

                            {{-- Botão de Ação --}}
                            <div class="mt-6">
                                <button id="confirm-entry-btn" type="button" class="group w-full py-4 rounded-xl bg-gray-900 text-white font-bold text-sm hover:bg-gray-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <span>Próximo Ingresso</span>
                                    <x-heroicon-m-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform"/>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Histórico Recente --}}
                <div id="history-container" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-700 text-sm">Últimos Acessos</h3>
                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-md font-medium">Recentes</span>
                    </div>
                    <div id="history-list" class="divide-y divide-gray-50"></div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
    (function(){
        const readerEl = document.getElementById('reader');
        const resultContainer = document.getElementById('scan-result-container');
        const errorFeedback = document.getElementById('error-feedback');
        const confirmBtn = document.getElementById('confirm-entry-btn');
        const cameraBackBtn = document.getElementById('camera-back-btn');
        const cameraFrontBtn = document.getElementById('camera-front-btn');
        const userPhotoWrapper = document.getElementById('user-photo-wrapper');
        const userPhoto = document.getElementById('user-photo');
        const historyContainer = document.getElementById('history-container');
        const historyList = document.getElementById('history-list');
        const statusHeader = document.getElementById('status-header');

        let html5QrCode = null;
        let isScanning = true;
        let cameras = [];
        let currentCameraIndex = 0;
        let history = [];

        function renderHistory() {
            if (!history.length) {
                historyContainer.classList.add('hidden');
                historyList.innerHTML = '';
                return;
            }
            historyContainer.classList.remove('hidden');
            historyList.innerHTML = history.map(function (item) {
                return '<div class="p-4 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors">' +
                    '<div class="flex-1 min-w-0">' +
                    '<p class="text-sm font-bold text-gray-800 truncate">' + item.user + '</p>' +
                    '<p class="text-xs text-gray-500 truncate">' + item.event + '</p>' +
                    '</div>' +
                    '<div class="text-right">' +
                    '<span class="text-xs font-mono font-medium text-gray-400 block">' + item.time + '</span>' +
                    '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-800 mt-1">OK</span>' +
                    '</div>' +
                '</div>';
            }).join('');
        }

        function showSuccess(data){
            isScanning = false;
            html5QrCode.pause();
            
            // Configurar cores de sucesso
            statusHeader.className = 'p-6 text-center text-white relative overflow-hidden bg-gradient-to-br from-emerald-500 to-green-600';
            document.getElementById('status-icon').className = 'w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-3xl shadow-inner mx-auto mb-2 text-white';
            document.getElementById('status-icon').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>';
            
            document.getElementById('status-title').textContent = 'Acesso Liberado';
            document.getElementById('status-subtitle').textContent = data.ticket.checked_by_name ? 'Validado por: ' + data.ticket.checked_by_name : 'Ingresso válido';

            // Preencher dados
            document.getElementById('user-name').textContent = data.ticket.user_name;
            document.getElementById('event-name').textContent = data.ticket.event_name;
            document.getElementById('event-date').textContent = data.ticket.event_date || 'Data não definida';
            document.getElementById('ticket-type').textContent = data.ticket.ticket_type || 'Geral';
            document.getElementById('ticket-code').textContent = data.ticket.code;
            document.getElementById('checkin-time').textContent = data.ticket.checkin_at;

            if (data.ticket.user_photo_url) {
                userPhotoWrapper.classList.remove('hidden');
                userPhoto.src = data.ticket.user_photo_url;
            } else {
                userPhotoWrapper.classList.add('hidden');
                userPhoto.removeAttribute('src');
            }

            errorFeedback.classList.add('hidden');
            
            // Animação de entrada
            resultContainer.classList.remove('hidden');
            // Pequeno delay para permitir que a classe hidden saia antes de animar opacidade
            requestAnimationFrame(() => {
                resultContainer.classList.remove('translate-y-4', 'opacity-0');
            });

            // Adicionar ao histórico
            history.unshift({
                user: data.ticket.user_name,
                event: data.ticket.event_name,
                time: data.ticket.checkin_at
            });
            if (history.length > 5) history.pop();
            renderHistory();
        }

        function showError(msg){
            const errorEl = errorFeedback.querySelector('span');
            if(errorEl) errorEl.textContent = typeof msg === 'string' ? msg : 'Falha no check-in';
            
            errorFeedback.classList.remove('hidden');
            // Animação simples de shake poderia ser adicionada aqui
            
            setTimeout(() => errorFeedback.classList.add('hidden'), 4000);
        }

        window.resetScanner = function(){
            errorFeedback.classList.add('hidden');
            
            // Animação de saída
            resultContainer.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => {
                resultContainer.classList.add('hidden');
            }, 300);

            isScanning = true;
            if (html5QrCode) {
                html5QrCode.resume().catch(function () {});
            }
        };

        confirmBtn.addEventListener('click', function () {
            window.resetScanner();
        });

        function updateCameraButtons() {
            if (!cameras.length) {
                cameraBackBtn.classList.add('opacity-50', 'cursor-not-allowed');
                cameraFrontBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }
            
            const activeClass = ['bg-white', 'text-primary-600', 'shadow-sm', 'ring-1', 'ring-gray-200'];
            const inactiveClass = ['text-gray-500', 'hover:text-gray-700'];

            if (currentCameraIndex === getBackCameraIndex()) {
                cameraBackBtn.classList.add(...activeClass);
                cameraBackBtn.classList.remove(...inactiveClass);
                
                cameraFrontBtn.classList.remove(...activeClass);
                cameraFrontBtn.classList.add(...inactiveClass);
            } else {
                cameraFrontBtn.classList.add(...activeClass);
                cameraFrontBtn.classList.remove(...inactiveClass);
                
                cameraBackBtn.classList.remove(...activeClass);
                cameraBackBtn.classList.add(...inactiveClass);
            }
        }

        function getBackCameraIndex() {
            if (!cameras.length) return 0;
            const backKeywords = ['back', 'rear', 'traseira', 'environment'];
            const index = cameras.findIndex(cam => {
                const label = (cam.label || '').toLowerCase();
                return backKeywords.some(k => label.includes(k));
            });
            return index >= 0 ? index : 0;
        }

        function getFrontCameraIndex() {
            if (!cameras.length) return 0;
            const frontKeywords = ['front', 'frontal', 'user'];
            const index = cameras.findIndex(cam => {
                const label = (cam.label || '').toLowerCase();
                return frontKeywords.some(k => label.includes(k));
            });
            return index >= 0 ? index : 0;
        }

        function startCameraWithCurrent() {
            if (!cameras.length) return;
            const selected = cameras[currentCameraIndex];
            if (!selected || !selected.id) return;

            readerEl.parentElement.classList.add('ring-4', 'ring-primary-100'); // Indicador visual de carregamento

            html5QrCode.start(
                selected.id,
                { fps: 10, qrbox: { width: 250, height: 250 } },
                handleDecoded,
                function () {}
            ).then(function () {
                isScanning = true;
                readerEl.parentElement.classList.remove('ring-4', 'ring-primary-100');
                updateCameraButtons();
            }).catch(function () {
                readerEl.parentElement.classList.remove('ring-4', 'ring-primary-100');
                showError('Falha ao iniciar câmera. Verifique permissões.');
            });
        }

        cameraBackBtn.addEventListener('click', function () {
            if (!cameras.length) return;
            currentCameraIndex = getBackCameraIndex();
            html5QrCode.stop().then(startCameraWithCurrent).catch(startCameraWithCurrent);
        });

        cameraFrontBtn.addEventListener('click', function () {
            if (!cameras.length) return;
            currentCameraIndex = getFrontCameraIndex();
            html5QrCode.stop().then(startCameraWithCurrent).catch(startCameraWithCurrent);
        });

        function handleDecoded(text){
            if(!isScanning) return;

            try {
                const code = String(text || '').trim();
                const uuid = code.startsWith('TICKET:') ? code.replace('TICKET:', '') : code;
                
                // Feedback visual de "processando"
                readerEl.style.opacity = "0.3";

                fetch("{{ route('admin.ticket.checkin') }}",{
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'X-Requested-With':'XMLHttpRequest',
                        'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content || '')
                    },
                    body: JSON.stringify({ ticket_code: uuid })
                }).then(async (res)=>{
                    const j = await res.json();
                    readerEl.style.opacity = "1";
                    
                    if(res.ok && j.success){
                        showSuccess(j);
                    } else {
                        // Se falhar, configurar cabeçalho de erro
                        statusHeader.className = 'p-6 text-center text-white relative overflow-hidden bg-gradient-to-br from-red-500 to-rose-600';
                         document.getElementById('status-icon').className = 'w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-3xl shadow-inner mx-auto mb-2 text-white';
                        document.getElementById('status-icon').innerHTML = '✕';
                        document.getElementById('status-title').textContent = 'Acesso Negado';
                        document.getElementById('status-subtitle').textContent = j.error || 'Ingresso inválido ou já utilizado';
                        
                        // Esconder detalhes de sucesso se houver lixo residual
                        userPhotoWrapper.classList.add('hidden');
                        document.getElementById('user-name').textContent = '---';
                        
                        showError(j.error || 'Falha no check-in');
                    }
                }).catch(()=>{
                    readerEl.style.opacity = "1";
                    showError('Erro de conexão. Tente novamente.');
                });
            } catch(e){ 
                readerEl.style.opacity = "1";
                showError('Erro ao processar QR Code'); 
            }
        }

        html5QrCode = new Html5Qrcode('reader');
        Html5Qrcode.getCameras().then(function (cams) {
            cameras = cams || [];
            if (!cameras.length) {
                showError('Nenhuma câmera detectada neste dispositivo.');
                updateCameraButtons();
                return;
            }
            currentCameraIndex = getBackCameraIndex();
            updateCameraButtons();
            startCameraWithCurrent();
        }).catch(function (e) {
            console.error(e);
            showError('Por favor, permita o acesso à câmera para usar o scanner.');
        });
    })();
    </script>
</x-filament::page>
