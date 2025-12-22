@extends('layouts.app')

@section('title', 'Contato - RCC Miguelópolis')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white py-24">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
            Fale com a <span class="text-blue-300">RCC Miguelópolis</span>
        </h1>
        <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto">
            Participe dos nossos grupos, eventos e serviços de evangelização. Estamos prontos para acolher você.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Envie sua Mensagem</h2>
                    <p class="text-gray-600">
                        Conte-nos como podemos ajudar. Nossa equipe responde com carinho e rapidez.
                    </p>
                </div>

                @livewire('contact-form')
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <!-- Contact Details -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Informações de Contato</h3>
                    @php
                        $address = (string) data_get($siteSettings, 'site.address');
                        $phoneRaw = (string) data_get($siteSettings, 'site.phone');
                        $email = (string) data_get($siteSettings, 'site.email');
                        $phoneDigits = preg_replace('/\D+/', '', $phoneRaw);
                        $telHref = $phoneDigits ? ('tel:+'.$phoneDigits) : null;
                        $waUrlConfigured = (string) data_get($siteSettings, 'social.whatsapp');
                        $waNumRaw = (string) data_get($siteSettings, 'site.whatsapp');
                        $waDigits = preg_replace('/\D+/', '', $waNumRaw);
                        $waHref = $waUrlConfigured ?: ($waDigits ? ('https://wa.me/'.$waDigits) : null);
                    @endphp
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">Endereço</h4>
                                <p class="text-gray-600">
                                    {{ $address ?: 'Endereço não informado' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">Telefone</h4>
                                <p class="text-gray-600">
                                    {{ $phoneRaw ?: 'Telefone não informado' }}<br>
                                    <span class="text-sm text-gray-500">Atendimento conforme agenda paroquial</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">E-mail</h4>
                                <p class="text-gray-600">
                                    {{ $email ?: 'Email não informado' }}<br>
                                    <span class="text-sm text-gray-500">Respondemos o quanto antes</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Horário de Atendimento</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Segunda a Sexta</span>
                            <span class="font-semibold text-gray-900">Conforme programação</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Sábado</span>
                            <span class="font-semibold text-gray-900">Atendimentos e grupos</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Domingo</span>
                            <span class="font-semibold text-gray-900">Celebrações e encontros</span>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Redes Sociais</h3>
                    <p class="text-gray-600 mb-6">Acompanhe convites, eventos e comunicados oficiais.</p>
                    @php
                        $facebook = (string) data_get($siteSettings, 'social.facebook');
                        $instagram = (string) data_get($siteSettings, 'social.instagram');
                        $youtube = (string) data_get($siteSettings, 'social.youtube');
                        $tiktok = (string) data_get($siteSettings, 'social.tiktok');
                    @endphp
                    <div class="flex space-x-4">
                        @if($facebook)
                        <a href="{{ $facebook }}" target="_blank" rel="noopener" class="w-12 h-12 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        @endif
                        @if($instagram)
                        <a href="{{ $instagram }}" target="_blank" rel="noopener" class="w-12 h-12 bg-blue-800 text-white rounded-lg flex items-center justify-center hover:bg-blue-900 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        @endif
                        @if($youtube)
                        <a href="{{ $youtube }}" target="_blank" rel="noopener" class="w-12 h-12 bg-pink-600 text-white rounded-lg flex items-center justify-center hover:bg-pink-700 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172 .271-.402 .165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                            </svg>
                        </a>
                        @endif
                        @if($tiktok)
                        <a href="{{ $tiktok }}" target="_blank" rel="noopener" class="w-12 h-12 bg-green-600 text-white rounded-lg flex items-center justify-center hover:bg-green-700 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67 .15-.197 .297-.767 .966-.94 1.164-.173 .199-.347 .223-.644 .075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458 .13-.606 .134-.133 .298-.347 .446-.52 .149-.174 .198-.298 .298-.497 .099-.198 .05-.371-.025-.52 -.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51 -.173-.008 -.371-.01 -.57-.01 -.198 0 -.52 .074 -.792 .372 -.272 .297 -1.04 1.016 -1.04 2.479 0 1.462 1.065 2.875 1.213 3.074 .149 .198 2.096 3.2 5.077 4.487 .709 .306 1.262 .489 1.694 .625 .712 .227 1.36 .195 1.871 .118 .571 -.085 1.758 -.719 2.006 -1.413 .248 -.694 .248 -1.289 .173 -1.413 -.074 -.124 -.272 -.198 -.57 -.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361 -.214 -3.741 .982 .998 -3.648 -.235 -.374a9.86 9.86 0 01-1.51 -5.26c.001 -5.45 4.436 -9.884 9.888 -9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45 -4.437 9.884 -9.885 9.884m8.413 -18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335 .157 11.892c0 2.096 .547 4.142 1.588 5.945L .057 24l6.305 -1.654a11.882 11.882 0 005.683 1.448h .005c6.554 0 11.89 -5.335 11.893 -11.893A11.821 11.821 0 0024.003 11.89"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Onde Estamos</h2>
            <p class="text-lg text-gray-600">Venha nos visitar! Estamos de portas abertas para acolher você.</p>
        </div>
        
        <div class="bg-gray-200 rounded-xl overflow-hidden" style="height: 400px;">
            @if(!empty($address))
                <iframe
                    src="https://www.google.com/maps?q={{ urlencode($address) }}&output=embed"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Mapa do Google</h3>
                        <p class="text-gray-500">Endereço não informado</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Perguntas Frequentes
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Confira as respostas para as dúvidas mais comuns sobre nossos serviços.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- FAQ Item 1 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Qual é o prazo de resposta?</h3>
                <p class="text-gray-600">
                    Respondemos todas as solicitações em até 24 horas úteis. Para projetos urgentes, 
                    entre em contato por telefone para atendimento prioritário.
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Vocês atendem fora de São Paulo?</h3>
                <p class="text-gray-600">
                    Sim! Atendemos clientes em todo o Brasil. Grande parte dos nossos projetos 
                    são realizados remotamente com reuniões online.
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Como funciona o processo de orçamento?</h3>
                <p class="text-gray-600">
                    Após receber sua solicitação, agendamos uma reunião para entender suas necessidades 
                    e apresentamos uma proposta detalhada em até 3 dias úteis.
                </p>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Oferecem garantia nos serviços?</h3>
                <p class="text-gray-600">
                    Sim! Todos os nossos projetos possuem garantia de 6 meses. Oferecemos também 
                    planos de manutenção contínua para garantir o perfeito funcionamento.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Quer participar de um Grupo de Oração?
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Fale com a RCC Miguelópolis e venha viver uma experiência profunda com o Espírito Santo.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ $telHref ?? '#' }}" class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                Ligar Agora
            </a>
            <a href="{{ $waHref ?? '#' }}" class="inline-flex items-center px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0024.003 11.89"/>
                </svg>
                WhatsApp
            </a>
        </div>
    </div>
</section>
@endsection
