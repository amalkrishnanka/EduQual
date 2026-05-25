@props(['title', 'description' => '', 'actionUrl' => null, 'actionText' => 'Get Started'])
<div class="text-center py-12">
    <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
    </svg>
    <h3 class="text-lg font-medium text-slate-300">{{ $title }}</h3>
    @if($description)<p class="text-sm text-slate-400 mt-2 max-w-md mx-auto">{{ $description }}</p>@endif
    @if($actionUrl)<a href="{{ $actionUrl }}" class="btn btn-primary btn-sm mt-4">{{ $actionText }}</a>@endif
</div>
