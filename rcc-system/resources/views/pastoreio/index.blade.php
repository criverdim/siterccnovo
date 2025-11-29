<x-layouts.app :title="'Pastoreio'">
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-emerald-700 mb-4">Pastoreio</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Presenças Totais</div>
            <div class="text-2xl font-bold">{{ $totalAttendance ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Últimos 30 dias</div>
            <div class="text-2xl font-bold">{{ $last30 ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Últimos 60 / 90 dias</div>
            <div class="text-lg font-semibold">60: {{ $last60 ?? 0 }} • 90: {{ $last90 ?? 0 }}</div>
        </div>
    </div>
    <div class="mb-6">
        <h2 class="font-semibold">Ranking dos mais presentes</h2>
        <ul class="list-disc pl-6">
            @foreach(($ranking ?? []) as $item)
                <li>{{ $item->user->name ?? '—' }} — {{ $item->total }} presenças</li>
            @endforeach
        </ul>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Novos participantes (30d)</div>
            <div class="text-2xl font-bold">{{ $newParticipantsCount ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white border rounded">
            <div class="text-sm text-gray-500">Fieis em risco</div>
            <div class="text-2xl font-bold">{{ $atRiskCount ?? 0 }}</div>
        </div>
    </div>
    <div id="react-pastoreio-app"></div>
</div>
@foreach($groups as $group)
    <span class="hidden" data-group-option data-id="{{ $group->id }}" data-name="{{ $group->name }}"></span>
@endforeach
</x-layouts.app>
