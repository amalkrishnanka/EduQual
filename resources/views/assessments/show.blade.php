@extends('layouts.app')
@section('header', 'Assessment Details')
@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="glass rounded-xl p-6">
        <div class="flex flex-col md:flex-row items-start justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold">{{ $assessment->resource->title }}</h1>
                <p class="text-slate-400 mt-1">Reviewed by {{ $assessment->reviewer->name }}</p>
                <div class="flex items-center gap-3 mt-3">
                    <span class="status-{{ $assessment->status }} px-3 py-1 rounded-full text-xs font-semibold">{{ ucfirst($assessment->status) }}</span>
                    @if($assessment->submitted_at)<span class="text-xs text-slate-400">Submitted {{ $assessment->submitted_at->format('M d, Y') }}</span>@endif
                </div>
            </div>
            <div class="text-center">
                <div class="text-5xl font-bold {{ $assessment->overall_score >= 8 ? 'text-emerald-400' : ($assessment->overall_score >= 6 ? 'text-cyan-400' : ($assessment->overall_score >= 4 ? 'text-amber-400' : 'text-rose-400')) }}">{{ $assessment->overall_score ? number_format($assessment->overall_score, 1) : '—' }}</div>
                <p class="text-xs text-slate-400 mt-1">Overall Score</p>
            </div>
        </div>
        <div class="flex gap-2 mt-4">
            @if($assessment->isDraft() && auth()->id() === $assessment->reviewer_id)
            <a href="{{ route('assessments.edit', $assessment) }}" class="btn btn-secondary btn-sm">Edit</a>
            <form method="POST" action="{{ route('assessments.submit', $assessment) }}" class="inline"><@csrf<button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Submit this assessment? This cannot be undone.')">Submit Final</button></form>
            @endif
            @if(auth()->user()->isSuperAdmin() && $assessment->isSubmitted())
            <form method="POST" action="{{ route('admin.assessments.unlock', $assessment) }}" class="inline">@csrf<button type="submit" class="btn btn-secondary btn-sm">Unlock</button></form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Radar Chart --}}
        <div class="glass rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-4">Score Breakdown</h3>
            <canvas id="radarChart" class="max-h-80"></canvas>
        </div>

        {{-- Criteria Details --}}
        <div class="glass rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-4">Criteria Scores</h3>
            <div class="space-y-4">
                @foreach($assessment->scores as $score)
                <div class="glass-light rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium text-sm">{{ $score->criterion->name }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400">Weight: {{ number_format($score->criterion->weight * 100) }}%</span>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $score->score >= 8 ? 'score-excellent' : ($score->score >= 6 ? 'score-good' : ($score->score >= 4 ? 'score-average' : 'score-poor')) }} text-white">{{ $score->score }}/10</span>
                        </div>
                    </div>
                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden mb-2"><div class="h-full rounded-full {{ $score->score >= 8 ? 'bg-emerald-500' : ($score->score >= 6 ? 'bg-cyan-500' : ($score->score >= 4 ? 'bg-amber-500' : 'bg-rose-500')) }}" style="width: {{ $score->score * 10 }}%"></div></div>
                    <p class="text-xs text-slate-400">{{ $score->justification }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('radarChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: {!! json_encode($assessment->scores->pluck('criterion.name')) !!},
            datasets: [{
                label: 'Scores',
                data: {!! json_encode($assessment->scores->pluck('score')) !!},
                borderColor: 'rgba(99, 102, 241, 1)',
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: { r: { min: 0, max: 10, ticks: { stepSize: 2, color: '#64748b', backdropColor: 'transparent' }, grid: { color: 'rgba(148,163,184,0.1)' }, pointLabels: { color: '#cbd5e1', font: { size: 11 } } } },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush
@endsection
