@props(['name', 'title' => '', 'maxWidth' => 'lg'])
@php
$maxWidthClass = ['sm' => 'max-w-sm', 'md' => 'max-w-md', 'lg' => 'max-w-lg', 'xl' => 'max-w-xl', '2xl' => 'max-w-2xl'][$maxWidth] ?? 'max-w-lg';
@endphp
<div x-show="{{ $name }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="{{ $name }} = false"></div>
    <div class="glass rounded-xl {{ $maxWidthClass }} w-full relative z-10 animate-scale-in">
        @if($title)
        <div class="flex items-center justify-between p-5 border-b border-slate-700/50">
            <h3 class="text-lg font-semibold">{{ $title }}</h3>
            <button @click="{{ $name }} = false" class="text-slate-400 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        @endif
        <div class="p-5">{{ $slot }}</div>
    </div>
</div>
