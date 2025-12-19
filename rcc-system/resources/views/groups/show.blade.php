@php($title = 'Grupo - '.$group->name)
<x-layouts.app :title="$title">
    <div class="max-w-6xl mx-auto p-6 md:p-10">
        <h1 class="text-4xl md:text-5xl font-bold text-emerald-700 mb-6">
            <span aria-hidden="true" style="display:inline-block;width:0.9rem;height:0.9rem;border-radius:9999px;background:{{ $group->color_hex ?? '#0b7a48' }};border:1px solid #e5e7eb;box-shadow:0 0 0 1px #fff inset;margin-right:0.5rem"></span>
            <span class="sr-only">Cor do grupo {{ $group->name }}</span>
            {{ $group->name }}
        </h1>
        <div id="react-group-show-app"></div>
        <span class="hidden"
              data-group-id="{{ $group->id }}"
              data-group-color="{{ $group->color_hex ?? '' }}"
              data-group-weekday="{{ $group->weekday }}"
              data-group-time="{{ optional($group->time)->format('H:i') }}"
              data-group-address="{{ $group->address }}"
              data-group-description="{{ $group->description }}"
              data-group-responsible="{{ $group->responsible }}"
              data-group-photo="{{ $group->cover_photo ?: ((is_array($group->photos) && count($group->photos)) ? $group->photos[0] : '') }}"
              data-group-responsible-phone="{{ $group->responsible_phone }}"
              data-group-responsible-whatsapp="{{ $group->responsible_whatsapp }}"
              data-group-responsible-email="{{ $group->responsible_email }}"
              data-group-photos='@json($group->photos)'
              data-cover-bg-color="{{ $group->cover_bg_color ?? '' }}"
              data-cover-object-position="{{ $group->cover_object_position ?? '' }}"
        ></span>
    </div>
</x-layouts.app>
