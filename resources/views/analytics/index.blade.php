@extends('layouts.app')
@section('header', 'Analytics')
@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-card p-4 card-hover"><p class="text-xs text-forest/60 uppercase tracking-wider font-bold">Total Resources</p><p class="text-xl font-bold font-heading mt-1 text-forest">{{ $totalResources }}</p></div>
        <div class="glass-card p-4 card-hover"><p class="text-xs text-forest/60 uppercase tracking-wider font-bold">Total Assessments</p><p class="text-xl font-bold font-heading mt-1 text-forest">{{ $totalAssessments }}</p></div>
        <div class="glass-card p-4 card-hover"><p class="text-xs text-forest/60 uppercase tracking-wider font-bold">Average Score</p><p class="text-xl font-bold font-heading mt-1 text-forest">{{ $averageScore ? number_format($averageScore, 1) : '—' }}</p></div>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="glass-card p-5">
            <h3 class="text-base font-bold font-heading mb-4 text-forest">Average Scores by Subject</h3>
            <canvas id="subjectChart" class="max-h-72"></canvas>
        </div>
        <div class="glass-card p-5">
            <h3 class="text-base font-bold font-heading mb-4 text-forest">Score Distribution</h3>
            <canvas id="distributionChart" class="max-h-72"></canvas>
        </div>
        <div class="glass-card p-5">
            <h3 class="text-base font-bold font-heading mb-4 text-forest">Reviewer Activity (Last 6 Months)</h3>
            <canvas id="activityChart" class="max-h-72"></canvas>
        </div>
        <div class="glass-card p-5 flex flex-col items-center justify-center text-center">
            <h3 class="text-base font-bold font-heading mb-2 text-forest">Compare Resources</h3>
            <p class="text-forest/60 text-sm mb-6 max-w-xs">Compare up to 4 resources side by side to see how they stack up.</p>
            <a href="{{ route('analytics.comparison') }}" class="btn btn-primary shadow-sm text-sm px-6">Open Comparison Tool</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartColors = ['rgba(99,102,241,0.8)', 'rgba(139,92,246,0.8)', 'rgba(16,185,129,0.8)', 'rgba(245,158,11,0.8)', 'rgba(236,72,153,0.8)', 'rgba(6,182,212,0.8)'];
    const defaultOpts = { responsive: true, plugins: { legend: { labels: { color: '#cbd5e1' } } }, scales: { x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(148,163,184,0.06)' } }, y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(148,163,184,0.06)' } } } };

    fetch('{{ route("analytics.scores-by-subject") }}').then(r=>r.json()).then(data => {
        new Chart(document.getElementById('subjectChart'), { type: 'bar', data: { labels: data.labels, datasets: [{ label: 'Avg Score', data: data.scores, backgroundColor: chartColors, borderRadius: 6 }] }, options: { ...defaultOpts, plugins: { legend: { display: false } }, scales: { ...defaultOpts.scales, y: { ...defaultOpts.scales.y, min: 0, max: 10 } } } });
    });

    fetch('{{ route("analytics.score-distribution") }}').then(r=>r.json()).then(data => {
        new Chart(document.getElementById('distributionChart'), { type: 'bar', data: { labels: data.labels, datasets: data.datasets.map((d,i) => ({ ...d, backgroundColor: chartColors[i%chartColors.length], borderRadius: 4 })) }, options: { ...defaultOpts, scales: { ...defaultOpts.scales, x: { ...defaultOpts.scales.x, stacked: true }, y: { ...defaultOpts.scales.y, stacked: true } } } });
    });

    fetch('{{ route("analytics.reviewer-activity") }}').then(r=>r.json()).then(data => {
        new Chart(document.getElementById('activityChart'), { type: 'line', data: { labels: data.labels, datasets: data.datasets.map((d,i) => ({ ...d, borderColor: chartColors[i%chartColors.length], backgroundColor: chartColors[i%chartColors.length].replace('0.8','0.1'), fill: true, tension: 0.4 })) }, options: defaultOpts });
    });
});
</script>
@endpush
@endsection
