@php
    $rol = auth()->user()?->rol;
@endphp

<x-layouts.app.sidebar :title="$title ?? null" :subheading="$subheading ?? null">
    <x-toast />
    <flux:main>
        {{-- <div class="flex items-center gap-2 mb-4">
            <flux:icon.code-bracket-square class="hidden lg:inline cursor-pointer w-5" id="toggle-sidebar" />
            <flux:heading size="lg" level="1">{{ $title ?? 'Configuración' }}</flux:heading>
        </div>
            <flux:subheading class="mb-4">{{ $subheading ?? '' }}</flux:subheading>
        <flux:separator variant="subtle" class="mb-4" /> --}}
        
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
