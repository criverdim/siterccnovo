<div class="grid md:grid-cols-2 gap-6">
    <div>
        <div class="text-emerald-700 font-semibold mb-2">Contato</div>
        <div class="text-sm text-gray-700">Endereço: {{ data_get($siteSettings, 'site.address') }}</div>
        <div class="text-sm text-gray-700">Telefone: {{ data_get($siteSettings, 'site.phone') }}</div>
        <div class="text-sm text-gray-700">WhatsApp: {{ data_get($siteSettings, 'site.whatsapp') }}</div>
        @if(data_get($siteSettings,'site.email'))
            <div class="text-sm text-gray-700">E-mail: {{ data_get($siteSettings, 'site.email') }}</div>
        @endif
    </div>
    <div>
        <div class="text-emerald-700 font-semibold mb-2">Redes</div>
        <div class="flex items-center gap-4 text-2xl">
            <a href="{{ data_get($siteSettings, 'social.instagram', '#') }}" class="text-emerald-700 hover:gold" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="{{ data_get($siteSettings, 'social.facebook', '#') }}" class="text-emerald-700 hover:gold" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="{{ data_get($siteSettings, 'social.youtube', '#') }}" class="text-emerald-700 hover:gold" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</div>

