@extends('layouts.app')
@section('header', $resource->title)
@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Resource Header --}}
    <div class="glass rounded-xl p-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="w-full lg:w-48 h-48 rounded-xl bg-gradient-to-br from-indigo-600/30 to-violet-600/30 flex items-center justify-center flex-shrink-0">
                @if($resource->cover_image)<img src="{{ Storage::url($resource->cover_image) }}" class="w-full h-full object-cover rounded-xl">@else<svg class="w-16 h-16 text-indigo-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>@endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold">{{ $resource->title }}</h1>
                <p class="text-slate-400 mt-1">by {{ $resource->author }}</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                    <div><p class="text-xs text-slate-500">Type</p><p class="text-sm font-medium mt-0.5">{{ ucfirst($resource->type) }}</p></div>
                    <div><p class="text-xs text-slate-500">Subject</p><p class="text-sm font-medium mt-0.5">{{ $resource->subject }}</p></div>
                    <div><p class="text-xs text-slate-500">Grade Level</p><p class="text-sm font-medium mt-0.5">{{ $resource->grade_level }}</p></div>
                    <div><p class="text-xs text-slate-500">Language</p><p class="text-sm font-medium mt-0.5">{{ $resource->language }}</p></div>
                    @if($resource->publisher)<div><p class="text-xs text-slate-500">Publisher</p><p class="text-sm font-medium mt-0.5">{{ $resource->publisher }}</p></div>@endif
                    @if($resource->isbn)<div><p class="text-xs text-slate-500">ISBN</p><p class="text-sm font-medium mt-0.5">{{ $resource->isbn }}</p></div>@endif
                    @if($resource->edition)<div><p class="text-xs text-slate-500">Edition</p><p class="text-sm font-medium mt-0.5">{{ $resource->edition }}</p></div>@endif
                </div>
                @if($resource->description)<p class="text-sm text-slate-300 mt-4">{{ $resource->description }}</p>@endif
            </div>
            <div class="flex flex-col items-center gap-3 lg:w-48">
                @if($resource->average_score)
                <div class="text-center">
                    <div class="text-4xl font-bold {{ $resource->average_score >= 8 ? 'text-emerald-400' : ($resource->average_score >= 6 ? 'text-cyan-400' : ($resource->average_score >= 4 ? 'text-amber-400' : 'text-rose-400')) }}">{{ number_format($resource->average_score, 1) }}</div>
                    <p class="text-xs text-slate-400 mt-1">Average Score</p>
                </div>
                @endif
                <p class="text-sm text-slate-400">{{ $resource->assessments->where('status', 'submitted')->count() }} assessments</p>
                <div class="flex flex-col gap-2 w-full">
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isReviewer())
                    <a href="{{ route('assessments.create', $resource) }}" class="btn btn-primary btn-sm w-full">Assess</a>
                    <a href="{{ route('flags.create', $resource) }}" class="btn btn-secondary btn-sm w-full">Flag</a>
                    @endif
                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('resources.edit', $resource) }}" class="btn btn-secondary btn-sm w-full">Edit</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Assessments --}}
    <div class="glass rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-4">Assessments</h2>
        <div class="space-y-4">
            @forelse($resource->assessments->where('status', 'submitted') as $assessment)
            <div class="glass-light rounded-lg p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="font-medium">{{ $assessment->reviewer->name }}</p>
                        <p class="text-xs text-slate-400">{{ $assessment->submitted_at?->format('M d, Y') }}</p>
                    </div>
                    <span class="text-lg font-bold {{ $assessment->overall_score >= 8 ? 'text-emerald-400' : ($assessment->overall_score >= 6 ? 'text-cyan-400' : 'text-amber-400') }}">{{ number_format($assessment->overall_score, 1) }}/10</span>
                </div>
                <div class="space-y-2">
                    @foreach($assessment->scores as $score)
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-400 w-32 flex-shrink-0">{{ $score->criterion->name }}</span>
                        <div class="flex-1 h-2 bg-slate-700 rounded-full overflow-hidden"><div class="h-full rounded-full {{ $score->score >= 8 ? 'bg-emerald-500' : ($score->score >= 6 ? 'bg-cyan-500' : ($score->score >= 4 ? 'bg-amber-500' : 'bg-rose-500')) }}" style="width: {{ $score->score * 10 }}%"></div></div>
                        <span class="text-xs font-semibold w-6 text-right">{{ $score->score }}</span>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('assessments.show', $assessment) }}" class="text-indigo-400 hover:text-indigo-300 text-sm mt-3 inline-block">View details →</a>
            </div>
            @empty
            <p class="text-slate-400 text-center py-6">No assessments submitted yet</p>
            @endforelse
        </div>
    </div>

    {{-- Flags --}}
    @if($resource->flags->count() > 0)
    <div class="glass rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-4">Flags ({{ $resource->flags->count() }})</h2>
        <div class="space-y-3">
            @foreach($resource->flags as $flag)
            <a href="{{ route('flags.show', $flag) }}" class="glass-light rounded-lg p-4 card-hover flex items-center justify-between block">
                <div><p class="font-medium text-sm">{{ $flag->title }}</p><p class="text-xs text-slate-400">{{ $flag->raisedBy->name }} · {{ $flag->created_at->diffForHumans() }}</p></div>
                <span class="status-{{ str_replace('_', '-', $flag->status) }} px-2 py-1 rounded-full text-xs font-semibold">{{ ucfirst(str_replace('_', ' ', $flag->status)) }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
