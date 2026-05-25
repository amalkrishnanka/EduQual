@extends('layouts.app')
@section('header', 'Flag Details')
@section('content')
<div class="space-y-6 animate-fade-in max-w-4xl">
    <div class="glass rounded-xl p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold">{{ $flag->title }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="status-{{ str_replace('_', '-', $flag->status) }} px-3 py-1 rounded-full text-xs font-semibold">{{ ucfirst(str_replace('_', ' ', $flag->status)) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-slate-700 text-slate-300">{{ ucfirst($flag->category) }}</span>
                </div>
            </div>
        </div>
        <p class="text-slate-300 mt-4">{{ $flag->description }}</p>
        <div class="flex items-center gap-4 mt-4 text-sm text-slate-400">
            <span>Raised by <strong class="text-slate-300">{{ $flag->raisedBy->name }}</strong></span>
            <span>{{ $flag->created_at->format('M d, Y') }}</span>
        </div>
        <a href="{{ route('resources.show', $flag->resource) }}" class="inline-block mt-3 glass-light rounded-lg p-3 card-hover">
            <p class="text-xs text-slate-400">Related Resource</p>
            <p class="font-medium text-sm mt-0.5">{{ $flag->resource->title }}</p>
        </a>
    </div>

    @if($flag->resolvedBy)
    <div class="glass rounded-xl p-5 border-l-4 {{ $flag->status == 'resolved' ? 'border-emerald-500' : 'border-rose-500' }}">
        <p class="text-sm font-medium">{{ $flag->status == 'resolved' ? 'Resolved' : 'Dismissed' }} by {{ $flag->resolvedBy->name }}</p>
        @if($flag->resolution_notes)<p class="text-sm text-slate-400 mt-1">{{ $flag->resolution_notes }}</p>@endif
        <p class="text-xs text-slate-500 mt-1">{{ $flag->resolved_at?->format('M d, Y H:i') }}</p>
    </div>
    @endif

    @if(auth()->user()->isSuperAdmin() && in_array($flag->status, ['open', 'under_review']))
    <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Update Status</h3>
        <form method="POST" action="{{ route('admin.flags.update-status', $flag) }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm text-slate-400 mb-1">New Status</label><select name="status" class="form-input"><option value="under_review">Under Review</option><option value="resolved">Resolved</option><option value="dismissed">Dismissed</option></select></div>
                <div><label class="block text-sm text-slate-400 mb-1">Resolution Notes</label><textarea name="resolution_notes" rows="2" class="form-input" placeholder="Optional notes..."></textarea></div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Update Status</button>
        </form>
    </div>
    @endif

    {{-- Comments --}}
    <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Comments ({{ $flag->comments->count() }})</h3>
        <div class="space-y-4 mb-6">
            @forelse($flag->comments as $comment)
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center flex-shrink-0"><span class="text-xs font-bold text-indigo-400">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span></div>
                <div class="flex-1 glass-light rounded-lg p-3">
                    <div class="flex items-center gap-2 mb-1"><span class="text-sm font-medium">{{ $comment->user->name }}</span><span class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</span></div>
                    <p class="text-sm text-slate-300">{{ $comment->comment }}</p>
                </div>
            </div>
            @empty
            <p class="text-slate-400 text-sm text-center py-4">No comments yet</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('flags.comment', $flag) }}" class="flex gap-3">
            @csrf
            <input type="text" name="comment" placeholder="Add a comment..." class="form-input flex-1" required>
            <button type="submit" class="btn btn-primary btn-sm">Post</button>
        </form>
    </div>
</div>
@endsection
