@extends('layouts.app')
@section('header', 'Compare Resources')
@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ selectedIds: {{ json_encode(request('resources', [])) }} }">
    <h2 class="text-xl font-bold">Resource Comparison</h2>
    <form method="GET" class="glass rounded-xl p-5">
        <p class="text-sm text-slate-400 mb-3">Select up to 4 resources to compare</p>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @for($i = 0; $i < 4; $i++)
            <select name="resources[]" class="form-input"><option value="">Select resource...</option>@foreach($allResources as $r)<option value="{{ $r->id }}" {{ isset($resources[$i]) && $resources[$i]->id == $r->id ? 'selected' : '' }}>{{ Str::limit($r->title, 40) }}</option>@endforeach</select>
            @endfor
        </div>
        <button type="submit" class="btn btn-primary btn-sm mt-3">Compare</button>
    </form>

    @if(count($resources) > 0)
    <div class="glass rounded-xl p-6">
        <canvas id="comparisonRadar" class="max-h-96"></canvas>
    </div>
    <div class="glass rounded-xl overflow-hidden">
        <div class="table-container">
            <table class="data-table">
                <thead><tr><th>Criterion</th>@foreach($resources as $r)<th class="text-center">{{ Str::limit($r->title, 25) }}</th>@endforeach</tr></thead>
                <tbody>
                @foreach($criteria as $c)
                <tr><td class="font-medium">{{ $c->name }}</td>
                @foreach($resources as $r)
                @php $avgScore = $r->assessments->where('status','submitted')->flatMap->scores->where('criterion_id', $c->id)->avg('score'); @endphp
                <td class="text-center"><span class="font-semibold {{ $avgScore >= 8 ? 'text-emerald-400' : ($avgScore >= 6 ? 'text-cyan-400' : 'text-amber-400') }}">{{ $avgScore ? number_format($avgScore, 1) : '—' }}</span></td>
                @endforeach</tr>
                @endforeach
                <tr class="font-bold border-t border-slate-600"><td>Overall</td>@foreach($resources as $r)<td class="text-center">{{ $r->average_score ? number_format($r->average_score, 1) : '—' }}</td>@endforeach</tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@if(count($resources ?? []) > 0)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = ['rgba(99,102,241,0.8)', 'rgba(139,92,246,0.8)', 'rgba(16,185,129,0.8)', 'rgba(245,158,11,0.8)'];
    const bgColors = ['rgba(99,102,241,0.15)', 'rgba(139,92,246,0.15)', 'rgba(16,185,129,0.15)', 'rgba(245,158,11,0.15)'];
    new Chart(document.getElementById('comparisonRadar'), {
        type: 'radar',
        data: {
            labels: {!! json_encode($criteria->pluck('name')) !!},
            datasets: [
                @foreach($resources as $idx => $r)
                { label: '{{ Str::limit($r->title, 30) }}', data: {!! json_encode($criteria->map(fn($c) => $r->assessments->where('status','submitted')->flatMap->scores->where('criterion_id',$c->id)->avg('score') ?? 0)) !!}, borderColor: colors[{{ $idx }}], backgroundColor: bgColors[{{ $idx }}], borderWidth: 2, pointRadius: 3 },
                @endforeach
            ]
        },
        options: { responsive: true, scales: { r: { min: 0, max: 10, ticks: { stepSize: 2, color: '#64748b', backdropColor: 'transparent' }, grid: { color: 'rgba(148,163,184,0.1)' }, pointLabels: { color: '#cbd5e1' } } }, plugins: { legend: { labels: { color: '#cbd5e1' } } } }
    });
});
</script>
@endpush
@endif
@endsection
