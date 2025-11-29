<x-filament::page>
    <div class="space-y-6">
        <form method="post" class="flex gap-3">
            @csrf
            <input type="text" name="query" placeholder="email/cpf/phone/whatsapp" class="p-2 rounded border w-full" />
            <button class="px-4 py-2 rounded bg-emerald-600 text-white">Buscar</button>
        </form>
        @if(!empty($results))
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($results as $user)
                    <div class="p-4 border rounded">
                        <div class="font-semibold">{{ $user['name'] }}</div>
                        <div class="text-sm text-gray-600">{{ $user['email'] }} • {{ $user['phone'] }}</div>
                        <form method="post" class="mt-2 flex gap-2">
                            @csrf
                            <input type="hidden" name="sourceId" value="{{ $user['id'] }}" />
                            <input type="number" name="targetId" placeholder="ID destino" class="p-2 rounded border" />
                            <button class="px-3 py-2 rounded bg-gold text-white">Unir</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament::page>

