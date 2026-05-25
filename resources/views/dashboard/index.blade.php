@extends('layouts.app')
@section('header', 'Dashboard')
@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @if(auth()->user()->isSuperAdmin())
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-100">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">Total Users</p><p class="text-2xl font-bold mt-1">{{ $totalUsers }}</p></div>
                <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
            </div>
        </div>
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-200">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">Resources</p><p class="text-2xl font-bold mt-1">{{ $totalResources }}</p></div>
                <div class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
            </div>
        </div>
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-300">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">Assessments</p><p class="text-2xl font-bold mt-1">{{ $totalAssessments }}</p></div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
            </div>
        </div>
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-400">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">Open Flags</p><p class="text-2xl font-bold mt-1">{{ $totalFlags }}</p></div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg></div>
            </div>
        </div>
        @elseif(auth()->user()->isReviewer())
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-100">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">My Drafts</p><p class="text-2xl font-bold mt-1">{{ $draftCount }}</p></div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
            </div>
        </div>
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-200">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">Submitted</p><p class="text-2xl font-bold mt-1">{{ $submittedCount }}</p></div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            </div>
        </div>
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-300 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">Total Assessments</p><p class="text-2xl font-bold mt-1">{{ $draftCount + $submittedCount }}</p></div>
                <a href="{{ route('resources.index') }}" class="btn btn-primary btn-sm">Browse Resources</a>
            </div>
        </div>
        @else
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-100 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">Top Rated Resources</p><p class="text-2xl font-bold mt-1">{{ $topResources->count() }}</p></div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
            </div>
        </div>
        <div class="glass rounded-xl p-5 card-hover animate-fade-in delay-200 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div><p class="text-sm text-slate-400">Published Assessments</p><p class="text-2xl font-bold mt-1">{{ $recentAssessments->count() }}</p></div>
                <a href="{{ route('resources.index') }}" class="btn btn-primary btn-sm">Browse All</a>
            </div>
        </div>
        @endif
    </div>

    {{-- Content sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @if(auth()->user()->isSuperAdmin())
        {{-- Recent Assessments --}}
        <div class="glass rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Assessments</h3>
            <div class="space-y-3">
                @forelse($recentAssessments as $assessment)
                <a href="{{ route('assessments.show', $assessment) }}" class="block glass-light rounded-lg p-4 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-sm">{{ $assessment->resource->title }}</p>
                            <p class="text-xs text-slate-400 mt-1">by {{ $assessment->reviewer->name }}</p>
                        </div>
                        <span class="status-{{ $assessment->status }} px-2 py-1 rounded-full text-xs font-semibold">{{ ucfirst($assessment->status) }}</span>
                    </div>
                </a>
                @empty
                <p class="text-slate-400 text-sm text-center py-4">No assessments yet</p>
                @endforelse
            </div>
        </div>
        {{-- Recent Flags --}}
        <div class="glass rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Flags</h3>
            <div class="space-y-3">
                @forelse($recentFlags as $flag)
                <a href="{{ route('flags.show', $flag) }}" class="block glass-light rounded-lg p-4 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-sm">{{ $flag->title }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $flag->resource->title }}</p>
                        </div>
                        <span class="status-{{ str_replace('_', '-', $flag->status) }} px-2 py-1 rounded-full text-xs font-semibold">{{ ucfirst(str_replace('_', ' ', $flag->status)) }}</span>
                    </div>
                </a>
                @empty
                <p class="text-slate-400 text-sm text-center py-4">No flags raised</p>
                @endforelse
            </div>
        </div>
        @elseif(auth()->user()->isReviewer())
        {{-- My Assessments --}}
        <div class="glass rounded-xl p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4">My Assessments</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead><tr><th>Resource</th><th>Status</th><th>Score</th><th>Date</th><th></th></tr></thead>
                    <tbody>
                    @forelse($myAssessments as $assessment)
                    <tr>
                        <td class="font-medium">{{ $assessment->resource->title }}</td>
                        <td><span class="status-{{ $assessment->status }} px-2 py-1 rounded-full text-xs font-semibold">{{ ucfirst($assessment->status) }}</span></td>
                        <td>{{ $assessment->overall_score ? number_format($assessment->overall_score, 1) : '—' }}</td>
                        <td class="text-slate-400 text-sm">{{ $assessment->created_at->format('M d, Y') }}</td>
                        <td><a href="{{ route('assessments.show', $assessment) }}" class="text-indigo-400 hover:text-indigo-300 text-sm">View →</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-slate-400 py-6">No assessments yet. <a href="{{ route('resources.index') }}" class="text-indigo-400 hover:underline">Browse resources</a> to get started.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($myAssessments->hasPages())
            <div class="mt-4 pagination-wrapper">{{ $myAssessments->links() }}</div>
            @endif
        </div>
        @else
        {{-- Viewer: Top Resources --}}
        <div class="glass rounded-xl p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Top Rated Resources</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($topResources as $resource)
                <a href="{{ route('resources.show', $resource) }}" class="glass-light rounded-lg p-4 card-hover block">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate">{{ $resource->title }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $resource->author }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $resource->subject }}</p>
                        </div>
                        @if($resource->average_score)
                        <span class="ml-2 px-2 py-1 rounded-lg text-xs font-bold {{ $resource->average_score >= 8 ? 'score-excellent' : ($resource->average_score >= 6 ? 'score-good' : 'score-average') }} text-white">{{ number_format($resource->average_score, 1) }}</span>
                        @endif
                    </div>
                </a>
                @empty
                <p class="text-slate-400 text-sm col-span-3 text-center py-4">No rated resources yet</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
