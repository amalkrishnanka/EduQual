@extends('layouts.app')
@section('header', 'Flags & Reviews')
@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex items-center justify-between"><h2 class="text-xl font-bold">Content Flags</h2></div>
    {{-- Status Tabs --}}
    <div class="flex gap-2 flex-wrap">
        @foreach(['all' => 'All', 'open' => 'Open', 'under_review' => 'Under Review', 'resolved' => 'Resolved', 'dismissed' => 'Dismissed'] as $key => $label)
        <a href="{{ route('flags.index', ['status' => $key == 'all' ? '' : $key]) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status', '') == ($key == 'all' ? '' : $key) ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'glass-light text-slate-400 hover:text-white' }}">{{ $label }}</a>
        @endforeach
    </div>
    <div class="space-y-3">
        @forelse($flags as $flag)
        <a href="{{ route('flags.show', $flag) }}" class="glass rounded-xl p-5 card-hover block">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold">{{ $flag->title }}</h3>
                    <p class="text-sm text-slate-400 mt-1">{{ $flag->resource->title }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs text-slate-500">{{ $flag->raisedBy->name }}</span>
                        <span class="text-xs text-slate-600">·</span>
                        <span class="text-xs text-slate-500">{{ $flag->created_at->diffForHumans() }}</span>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-slate-700 text-slate-300">{{ ucfirst($flag->category) }}</span>
                    </div>
                </div>
                <span class="status-{{ str_replace('_', '-', $flag->status) }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $flag->status)) }}</span>
            </div>
        </a>
        @empty
        <div class="text-center py-12"><p class="text-slate-400">No flags found</p></div>
        @endforelse
    </div>
    @if($flags->hasPages())<div class="pagination-wrapper">{{ $flags->links() }}</div>@endif
</div>
@endsection
