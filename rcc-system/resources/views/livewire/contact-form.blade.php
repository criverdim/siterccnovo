<div>
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome Completo *
                </label>
                <input type="text" id="name" wire:model="name" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                    placeholder="Seu nome">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    E-mail *
                </label>
                <input type="email" id="email" wire:model="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror"
                    placeholder="seu@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Telefone
                </label>
                <input type="tel" id="phone" wire:model="phone"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('phone') border-red-500 @enderror"
                    placeholder="(00) 00000-0000">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                    Empresa
                </label>
                <input type="text" id="company" wire:model="company"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('company') border-red-500 @enderror"
                    placeholder="Nome da empresa">
                @error('company')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                Assunto *
            </label>
            <select id="subject" wire:model="subject"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('subject') border-red-500 @enderror">
                <option value="">Selecione um assunto</option>
                <option value="orcamento">Solicitar Orçamento</option>
                <option value="duvida">Dúvida sobre Serviços</option>
                <option value="parceria">Proposta de Parceria</option>
                <option value="suporte">Suporte Técnico</option>
                <option value="outro">Outro</option>
            </select>
            @error('subject')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                Mensagem *
            </label>
            <textarea id="message" wire:model="message" rows="5"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none @error('message') border-red-500 @enderror"
                placeholder="Conte-nos mais sobre sua necessidade..."></textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="mt-1 text-sm text-gray-500">
                <span>{{ strlen($message) }}</span> / 5000 caracteres
            </div>
        </div>

        <div class="flex items-start">
            <input type="checkbox" id="privacy" wire:model="privacy"
                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
            <label for="privacy" class="ml-3 text-sm text-gray-600">
                Concordo com a <a href="#" class="text-blue-600 hover:text-blue-700 underline">Política de Privacidade</a> *
            </label>
        </div>
        @error('privacy')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <button type="submit" 
            wire:loading.attr="disabled"
            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading.remove>
                <span class="flex items-center justify-center">
                    Enviar Mensagem
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </span>
            </span>
            <span wire:loading>
                <span class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enviando...
                </span>
            </span>
        </button>
    </form>
</div>