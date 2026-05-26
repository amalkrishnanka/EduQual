@extends('layouts.app')
@section('header', 'Recommendations')
@section('content')
<div class="space-y-6 animate-fade-in">
    <h2 class="text-lg font-bold font-heading text-forest">Recommendations</h2>

    {{-- Top Rated --}}
    <div class="glass-card p-5">
        <h3 class="text-base font-bold font-heading mb-4 flex items-center gap-2"><svg class="w-5 h-5 text-forest" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> Top Rated Resources</h3>
        @forelse($topRated as $subject => $resources)
        <div class="mb-5">
            <h4 class="text-sm font-semibold text-forest/70 mb-3">{{ $subject }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($resources as $resource)
                <a href="{{ route('resources.show', $resource) }}" class="glass-light rounded-lg p-3 card-hover block border border-forest/5 bg-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm truncate text-forest">{{ $resource->title }}</p>
                            <p class="text-xs text-forest/60 mt-0.5">{{ $resource->author }}</p>
                        </div>
                        <span class="ml-2 px-2 py-0.5 rounded-lg text-xs font-bold score-excellent text-white">{{ number_format($resource->average_score, 1) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @empty
        <p class="text-forest/50 text-center py-6">No top-rated resources yet</p>
        @endforelse
    </div>

    {{-- Needs Assessment --}}
    <div class="glass-card p-5">
        <h3 class="text-base font-bold font-heading mb-4 flex items-center gap-2"><svg class="w-5 h-5 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> Needs Assessment</h3>
        <div class="space-y-3">
            @forelse($needsAssessment as $resource)
            <div class="glass-light rounded-lg p-3 flex items-center justify-between border border-forest/5 bg-white">
                <div>
                    <p class="font-medium text-sm text-forest">{{ $resource->title }}</p>
                    <p class="text-xs text-forest/60">{{ $resource->author }} · {{ $resource->subject }} · {{ ucfirst($resource->type) }}</p>
                </div>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isReviewer())
                <a href="{{ route('assessments.create', $resource) }}" class="btn btn-primary shadow-sm text-xs py-1.5 px-3">Assess Now</a>
                @else
                <span class="text-xs text-forest/50">Awaiting review</span>
                @endif
            </div>
            @empty
            <p class="text-forest/50 text-center py-6">All resources have been assessed!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
