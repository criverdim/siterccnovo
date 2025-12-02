<div class="flex items-start">
    @php($src = $getRecord()->photos[0] ?? null)
    <div class="w-16 h-20 rounded-md overflow-hidden border bg-white mr-3">
        @if($src)
            <img src="{{ Storage::disk('public')->url($src) }}" alt="Foto" class="w-full h-full object-cover" />
        @else
            <div class="w-full h-full grid place-items-center text-gray-400">
                <x-filament::icon icon="heroicon-o-user" class="w-8 h-8" />
            </div>
        @endif
    </div>
    <div class="flex-1"></div>
</div>
