@extends('layouts.app')
@section('header', 'Assessments')
@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold font-heading text-forest">All Assessments</h2>
    </div>
    <form method="GET" class="glass-card p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search resource..." class="form-input text-forest">
            <select name="status" class="form-input text-forest"><option value="">All Statuses</option><option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option><option value="submitted" {{ request('status')=='submitted'?'selected':'' }}>Submitted</option></select>
            <button type="submit" class="btn btn-secondary shadow-sm">Filter</button>
        </div>
    </form>
    <div class="glass-card overflow-hidden">
        <div class="table-container">
            <table class="data-table">
                <thead><tr><th>Resource</th><th>Reviewer</th><th>Score</th><th>Status</th><th>Date</th><th></th></tr></thead>
                <tbody>
                @forelse($assessments as $a)
                <tr>
                    <td class="font-medium">{{ $a->resource->title }}</td>
                    <td class="text-forest/60">{{ $a->reviewer->name }}</td>
                    <td>@if($a->overall_score)<span class="font-semibold {{ $a->overall_score >= 8 ? 'text-forest' : ($a->overall_score >= 6 ? 'text-forest-light' : 'text-amber-600') }}">{{ number_format($a->overall_score, 1) }}</span>@else<span class="text-forest/50">—</span>@endif</td>
                    <td><span class="status-{{ $a->status }} px-2 py-1 rounded-full text-xs font-semibold">{{ ucfirst($a->status) }}</span></td>
                    <td class="text-forest/60 text-sm">{{ $a->created_at->format('M d, Y') }}</td>
                    <td><a href="{{ route('assessments.show', $a) }}" class="text-forest hover:text-forest-dark font-medium text-sm">View →</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8 text-forest/50">No assessments found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($assessments->hasPages())<div class="pagination-wrapper mt-4">{{ $assessments->links() }}</div>@endif
</div>
@endsection
