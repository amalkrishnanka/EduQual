@extends('layouts.app')
@section('header', 'Resources')
@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header & Search --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold">Resource Catalogue</h2>
        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('resources.create') }}" class="btn btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add Resource</a>
        @endif
    </div>

    {{-- Filters --}}
    <form method="GET" class="glass rounded-xl p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, author, ISBN..." class="form-input">
            <select name="type" class="form-input"><option value="">All Types</option>@foreach($types as $key => $label)<option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select>
            <select name="subject" class="form-input"><option value="">All Subjects</option>@foreach($subjects as $s)<option value="{{ $s }}" {{ request('subject') == $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach</select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </div>
    </form>

    {{-- Resource Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($resources as $resource)
        <a href="{{ route('resources.show', $resource) }}" class="glass rounded-xl overflow-hidden card-hover block">
            <div class="h-32 bg-gradient-to-br from-indigo-600/30 to-violet-600/30 flex items-center justify-center">
                @if($resource->cover_image)
                <img src="{{ Storage::url($resource->cover_image) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                @else
                <svg class="w-12 h-12 text-indigo-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                @endif
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-sm line-clamp-2">{{ $resource->title }}</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $resource->author }}</p>
                <div class="flex items-center gap-2 mt-3 flex-wrap">
                    <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">{{ $resource->type }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-violet-500/20 text-violet-300 border border-violet-500/30">{{ $resource->subject }}</span>
                </div>
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-700/50">
                    <span class="text-xs text-slate-400">{{ $resource->assessment_count ?? 0 }} assessments</span>
                    @if($resource->average_score)
                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $resource->average_score >= 8 ? 'score-excellent' : ($resource->average_score >= 6 ? 'score-good' : ($resource->average_score >= 4 ? 'score-average' : 'score-poor')) }} text-white">{{ number_format($resource->average_score, 1) }}/10</span>
                    @else
                    <span class="text-xs text-slate-500">Not rated</span>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-12">
            <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <p class="text-slate-400 font-medium">No resources found</p>
            <p class="text-slate-500 text-sm mt-1">Try adjusting your search or filters</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($resources->hasPages())
    <div class="pagination-wrapper">{{ $resources->links() }}</div>
    @endif
</div>
@endsection
