@props(['type' => 'info', 'message' => ''])

@php
    $styles = [
        'success' => 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300',
        'error'   => 'bg-rose-500/10 border-rose-500/30 text-rose-300',
        'warning' => 'bg-amber-500/10 border-amber-500/30 text-amber-300',
        'info'    => 'bg-cyan-500/10 border-cyan-500/30 text-cyan-300',
    ];
    $style = $styles[$type] ?? $styles['info'];

    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
        'error'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/>',
        'info'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>',
    ];
    $icon = $icons[$type] ?? $icons['info'];
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-2"
     class="mt-4 flex items-center gap-3 px-4 py-3 rounded-xl border {{ $style }} animate-fade-in"
     role="alert">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
    <p class="text-sm font-medium flex-1">{{ $message }}</p>
    <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
    </button>
</div>
