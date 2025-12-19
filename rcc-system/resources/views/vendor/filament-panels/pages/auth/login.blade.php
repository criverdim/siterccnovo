<x-filament-panels::page.simple class="fi-login-page">
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    <script>
        (function(){
            document.addEventListener('DOMContentLoaded', function(){
                var f = document.getElementById('form');
                if(!f) return;
                var usp = new URLSearchParams(window.location.search);
                var r = usp.get('redirect') || '';
                if(r === '/admin/dashboard'){ usp.set('redirect','/admin'); history.replaceState({},'', location.pathname + '?' + usp.toString()); }
            });
        })();
    </script>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
