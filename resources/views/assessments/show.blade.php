@extends('layouts.app')

@section('header', 'Assessment Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-fade-in pb-12">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col gap-1">
        <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-wider text-forest/40">
            <a href="{{ route('resources.show', $assessment->resource) }}" class="hover:text-forest transition-colors">Resource Details</a>
            <span>/</span>
            <span class="text-forest/60">Assessment Details</span>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-black text-forest tracking-tight">Quality Assessment Profile</h1>
                <p class="text-xs text-forest/50 font-semibold mt-1">Review performed by <span class="text-forest/80 font-bold">{{ $assessment->reviewer->name }}</span></p>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-forest/10 text-forest border border-forest/10">
                    {{ strtoupper($assessment->status) }}
                </span>
                @if($assessment->submitted_at)
                    <span class="text-xs text-forest/40 font-bold">
                        Submitted {{ $assessment->submitted_at->format('M d, Y') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Profile Card --}}
    <div class="glass-card rounded-2xl p-6 flex flex-col md:flex-row items-center md:items-start justify-between gap-6 border border-forest/10 relative overflow-hidden bg-forest/[0.02]">
        <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-forest/5 blur-xl pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 min-w-0 flex-grow text-center sm:text-left">
            @if($assessment->resource->cover_image_url)
                <img src="{{ $assessment->resource->cover_image_url }}" class="w-20 h-28 object-cover rounded-xl shadow-md border border-forest/10 shrink-0">
            @else
                <div class="w-20 h-28 rounded-xl bg-forest/5 flex items-center justify-center border border-forest/10 text-forest/30 shrink-0">
                    <i data-lucide="book-open" class="w-8 h-8"></i>
                </div>
            @endif
            <div class="min-w-0 mt-2">
                <div class="flex items-center justify-center sm:justify-start gap-2 mb-2">
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-widest bg-forest/10 text-forest">{{ $assessment->resource->type }}</span>
                    <span class="text-[10px] font-bold text-forest/40 uppercase tracking-wider">{{ $assessment->resource->subject }}</span>
                </div>
                <h2 class="text-xl font-black text-forest leading-snug">{{ $assessment->resource->title }}</h2>
                <p class="text-xs text-forest/60 font-semibold mt-1">by <span class="text-forest/80 font-bold">{{ $assessment->resource->author }}</span></p>
                
                {{-- Actions --}}
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-4">
                    @if($assessment->isDraft() && auth()->id() === $assessment->reviewer_id)
                        <a href="{{ route('assessments.edit', $assessment) }}" class="border border-forest/20 text-forest/70 hover:text-forest bg-white/20 hover:bg-forest/5 text-[10px] font-black uppercase tracking-wider py-2 px-4 rounded-xl transition-all duration-300 flex items-center gap-1.5 shadow-sm">
                            <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                            Edit Draft
                        </a>
                        <form method="POST" action="{{ route('assessments.submit', $assessment) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-forest text-cream hover:bg-forest-light text-[10px] font-black uppercase tracking-wider py-2 px-4 rounded-xl transition-all duration-300 flex items-center gap-1.5 shadow-md" onclick="return confirm('Submit this assessment? Once submitted, it cannot be modified.')">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                Submit Final Review
                            </button>
                        </form>
                    @endif
                    @if(auth()->user()->isSuperAdmin() && $assessment->isSubmitted())
                        <form method="POST" action="{{ route('admin.assessments.unlock', $assessment) }}" class="inline">
                            @csrf
                            <button type="submit" class="border border-forest/20 text-forest bg-white/40 hover:bg-forest/5 text-[10px] font-black uppercase tracking-wider py-2 px-4 rounded-xl transition-all duration-300 flex items-center gap-1.5 shadow-sm">
                                <i data-lucide="unlock" class="w-3.5 h-3.5"></i>
                                Unlock Review
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Overall Score Radial Display --}}
        <div class="flex flex-col items-center justify-center bg-white/60 border border-forest/10 p-5 rounded-2xl w-32 shadow-sm shrink-0">
            <span class="text-[9px] font-black text-forest/40 uppercase tracking-widest mb-1">Overall</span>
            <div class="text-4xl font-black text-forest leading-none">
                {{ $assessment->overall_score ? number_format($assessment->overall_score, 1) : '—' }}
            </div>
            <span class="text-[8px] font-bold text-forest/40 mt-1 uppercase tracking-wider">scale / 10.0</span>
        </div>
    </div>

    {{-- Score Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Radar Chart Breakdown --}}
        <div class="lg:col-span-5 glass-card rounded-2xl p-6 border border-forest/10 bg-white/40">
            <h3 class="text-sm font-black text-forest uppercase tracking-wider mb-5 flex items-center gap-2">
                <i data-lucide="pie-chart" class="w-4 h-4 text-forest/60"></i>
                Quality Vector Breakdown
            </h3>
            <div class="relative flex items-center justify-center p-2">
                <canvas id="radarChart" class="max-h-72 w-full"></canvas>
            </div>
        </div>

        {{-- Criteria Details --}}
        <div class="lg:col-span-7 glass-card rounded-2xl p-6 border border-forest/10 bg-white/40">
            <h3 class="text-sm font-black text-forest uppercase tracking-wider mb-5 flex items-center gap-2">
                <i data-lucide="list-checks" class="w-4 h-4 text-forest/60"></i>
                Detailed Evaluation Ratings
            </h3>
            <div class="space-y-5">
                @foreach($assessment->scores as $score)
                <div class="bg-white/60 border border-forest/5 rounded-2xl p-4.5 shadow-sm space-y-3 hover:bg-white/80 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="font-extrabold text-xs text-forest tracking-tight">{{ $score->criterion->name }}</span>
                        <div class="flex items-center gap-3">
                            <span class="text-[9px] font-extrabold text-forest/40 uppercase">Weight: {{ number_format($score->criterion->weight * 100) }}%</span>
                            <span class="px-2 py-0.5 rounded bg-forest text-cream text-[10px] font-black tracking-tight">
                                {{ $score->score }}/10
                            </span>
                        </div>
                    </div>
                    
                    {{-- Graphic Bar Indicator --}}
                    <div>
                        <div class="h-1.5 bg-forest/5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-forest transition-all duration-1000" style="width: {{ $score->score * 10 }}%"></div>
                        </div>
                    </div>
                    
                    <p class="text-xs text-forest/70 font-semibold italic pl-2.5 border-l-2 border-forest/10 leading-relaxed">{{ $score->justification }}</p>
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
                label: 'Score Vector',
                data: {!! json_encode($assessment->scores->pluck('score')) !!},
                borderColor: 'rgba(30, 63, 32, 1)',
                backgroundColor: 'rgba(30, 63, 32, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(30, 63, 32, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(30, 63, 32, 1)',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    min: 0,
                    max: 10,
                    ticks: {
                        stepSize: 2,
                        color: 'rgba(30, 63, 32, 0.4)',
                        backdropColor: 'transparent',
                        font: { size: 9, weight: 'bold' }
                    },
                    grid: { color: 'rgba(30, 63, 32, 0.08)' },
                    angleLines: { color: 'rgba(30, 63, 32, 0.08)' },
                    pointLabels: {
                        color: 'rgba(30, 63, 32, 0.7)',
                        font: { size: 10, weight: 'bold', family: 'system-ui' }
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush
@endsection
