<x-filament::page>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Leitor de QR Code</h2>
                    <div class="inline-flex items-center rounded-full bg-gray-100 p-1 text-xs md:text-sm">
                        <button id="camera-back-btn" type="button" class="px-3 py-1 rounded-full bg-emerald-600 text-white font-medium">
                            Traseira
                        </button>
                        <button id="camera-front-btn" type="button" class="px-3 py-1 rounded-full text-gray-700 font-medium">
                            Frontal
                        </button>
                    </div>
                </div>
                <div id="reader" class="w-full aspect-[3/4] md:aspect-video rounded-xl overflow-hidden shadow-lg border-2 border-gray-300 bg-black"></div>
                <div id="error-feedback" class="hidden p-3 bg-red-100 text-red-800 rounded-lg shadow-md text-center font-semibold text-sm"></div>
            </div>

            <div class="w-full md:w-80 space-y-3">
                <div id="scan-result-container" class="hidden rounded-xl shadow-md border border-gray-200 bg-white overflow-hidden transition-all duration-300">
                    <div class="h-2 bg-gradient-to-r from-emerald-500 via-emerald-400 to-amber-400"></div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-center gap-3">
                            <div id="status-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-xl"></div>
                            <div>
                                <h3 id="status-title" class="text-lg font-bold"></h3>
                                <p id="status-subtitle" class="text-xs text-gray-500"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div id="user-photo-wrapper" class="w-14 h-14 rounded-full overflow-hidden ring-2 ring-emerald-500 bg-gray-100 hidden">
                                <img id="user-photo" src="" alt="Foto do participante" class="w-full h-full object-cover">
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-500 text-xs uppercase tracking-wide">Participante</p>
                                <p id="user-name" class="text-base font-semibold text-gray-900"></p>
                            </div>
                        </div>

                        <div class="text-xs text-gray-600 space-y-1">
                            <p><span class="font-semibold text-gray-700">Evento:</span> <span id="event-name" class="text-gray-800"></span></p>
                            <p><span class="font-semibold text-gray-700">Data:</span> <span id="event-date" class="text-gray-800"></span></p>
                            <p><span class="font-semibold text-gray-700">Ingresso:</span> <span id="ticket-type" class="text-gray-800"></span></p>
                            <p><span class="font-semibold text-gray-700">Código:</span> <span id="ticket-code" class="font-mono text-gray-900"></span></p>
                            <p><span class="font-semibold text-gray-700">Check-in:</span> <span id="checkin-time" class="text-gray-800"></span></p>
                        </div>

                        <div class="space-y-2">
                            <button id="confirm-entry-btn" type="button" class="w-full py-3 rounded-lg bg-gradient-to-r from-emerald-600 via-green-500 to-emerald-600 text-white font-semibold text-sm tracking-wide">
                                Entrada confirmada – escanear próximo
                            </button>
                        </div>
                    </div>
                </div>

                <div id="history-container" class="hidden rounded-xl border border-gray-200 bg-white shadow-sm p-3">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Últimos check-ins</p>
                    <div id="history-list" class="space-y-1 text-xs text-gray-700"></div>
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
                return '<div class="flex items-center justify-between gap-2">' +
                    '<div class="truncate">' +
                    '<span class="font-semibold">' + item.user + '</span>' +
                    '<span class="text-gray-500"> • ' + item.event + '</span>' +
                    '</div>' +
                    '<span class="text-gray-500 whitespace-nowrap">' + item.time + '</span>' +
                '</div>';
            }).join('');
        }

        function showSuccess(data){
            isScanning = false;
            html5QrCode.pause();
            
            resultContainer.classList.remove('hidden', 'bg-red-50', 'bg-green-50');
            resultContainer.classList.add('bg-green-50', 'border', 'border-green-200');
            
            document.getElementById('status-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-xl bg-green-600';
            document.getElementById('status-icon').innerHTML = '✓';
            document.getElementById('status-title').textContent = 'Entrada Confirmada';
            document.getElementById('status-title').className = 'text-lg font-bold text-green-800';
            document.getElementById('status-subtitle').textContent = data.ticket.checked_by_name ? 'Operador: ' + data.ticket.checked_by_name : '';

            document.getElementById('user-name').textContent = data.ticket.user_name;
            document.getElementById('event-name').textContent = data.ticket.event_name;
            document.getElementById('event-date').textContent = data.ticket.event_date || '';
            document.getElementById('ticket-type').textContent = data.ticket.ticket_type || 'Entrada';
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
            resultContainer.classList.remove('hidden');

            history.unshift({
                user: data.ticket.user_name,
                event: data.ticket.event_name,
                time: data.ticket.checkin_at
            });
            if (history.length > 5) {
                history.pop();
            }
            renderHistory();
        }

        function showError(msg){
            // Não para o scanner, apenas mostra erro temporário
            errorFeedback.textContent = msg;
            errorFeedback.classList.remove('hidden');
            setTimeout(() => errorFeedback.classList.add('hidden'), 3000);
        }

        window.resetScanner = function(){
            errorFeedback.classList.add('hidden');
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
            if (currentCameraIndex === getBackCameraIndex()) {
                cameraBackBtn.classList.add('bg-emerald-600', 'text-white');
                cameraFrontBtn.classList.remove('bg-emerald-600', 'text-white');
                cameraFrontBtn.classList.add('text-gray-700');
            } else {
                cameraFrontBtn.classList.add('bg-emerald-600', 'text-white');
                cameraBackBtn.classList.remove('bg-emerald-600', 'text-white');
                cameraBackBtn.classList.add('text-gray-700');
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

            readerEl.classList.add('opacity-50');

            html5QrCode.start(
                selected.id,
                { fps: 10, qrbox: { width: 250, height: 250 } },
                handleDecoded,
                function () {}
            ).then(function () {
                isScanning = true;
                readerEl.classList.remove('opacity-50');
                updateCameraButtons();
            }).catch(function () {
                readerEl.classList.remove('opacity-50');
                showError('Falha ao iniciar câmera');
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
                readerEl.classList.add('opacity-50');

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
                    readerEl.classList.remove('opacity-50');
                    
                    if(res.ok && j.success){
                        // beepOk.play(); // Descomentar se tiver arquivo de audio real
                        showSuccess(j);
                    } else {
                        showError(j.error || 'Falha no check-in');
                    }
                }).catch(()=>{
                    readerEl.classList.remove('opacity-50');
                    showError('Erro de rede no check-in');
                });
            } catch(e){ 
                readerEl.classList.remove('opacity-50');
                showError('Erro ao processar QR'); 
            }
        }

        html5QrCode = new Html5Qrcode('reader');
        Html5Qrcode.getCameras().then(function (cams) {
            cameras = cams || [];
            if (!cameras.length) {
                showError('Nenhuma câmera disponível');
                updateCameraButtons();
                return;
            }
            currentCameraIndex = getBackCameraIndex();
            updateCameraButtons();
            startCameraWithCurrent();
        }).catch(function () {
            showError('Falha ao acessar câmera');
        });
    })();
    </script>
</x-filament::page>
