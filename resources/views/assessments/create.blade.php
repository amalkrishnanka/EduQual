@extends('layouts.app')

@section('header', isset($assessment) ? 'Edit Assessment' : 'New Assessment')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-fade-in pb-12">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col gap-1">
        <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-wider text-forest/40">
            <a href="{{ route('resources.show', $resource) }}" class="hover:text-forest transition-colors">Resource Details</a>
            <span>/</span>
            <span class="text-forest/60">New Assessment</span>
        </div>
        <h1 class="text-2xl font-black text-forest tracking-tight mt-1">
            {{ isset($assessment) ? 'Refine Quality Review' : 'Create Quality Assessment' }}
        </h1>
        <p class="text-xs text-forest/50 font-medium">Evaluate the educational resource across core pedagogical vectors to establish catalog credibility.</p>
    </div>

    {{-- Resource Profile Info --}}
    <div class="glass-card rounded-2xl p-6 flex items-start gap-5 border border-forest/10 relative overflow-hidden bg-forest/[0.02]">
        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-forest/5 blur-xl pointer-events-none"></div>
        @if($resource->cover_image_url)
            <img src="{{ $resource->cover_image_url }}" class="w-16 h-22 object-cover rounded-xl shadow-md border border-forest/10 shrink-0">
        @else
            <div class="w-16 h-22 rounded-xl bg-forest/5 flex items-center justify-center border border-forest/10 text-forest/30 shrink-0">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
        @endif
        <div class="min-w-0 flex-grow">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-widest bg-forest/10 text-forest">{{ $resource->type }}</span>
                <span class="text-[10px] font-bold text-forest/40 uppercase tracking-wider">{{ $resource->subject }}</span>
            </div>
            <h2 class="text-lg font-black text-forest leading-tight">{{ $resource->title }}</h2>
            <p class="text-xs text-forest/60 font-semibold mt-1">by <span class="text-forest/80 font-bold">{{ $resource->author }}</span></p>
        </div>
    </div>

    {{-- Assessment Form --}}
    <form id="assessmentForm" method="POST" action="{{ isset($assessment) ? route('assessments.update', $assessment) : route('assessments.store') }}" class="space-y-6">
        @csrf
        @if(isset($assessment)) @method('PUT') @endif
        <input type="hidden" name="resource_id" value="{{ $resource->id }}">

        <div class="space-y-6">
            @foreach($criteria as $criterion)
            @php $existingScore = isset($existingScores) ? ($existingScores[$criterion->id] ?? null) : null; @endphp
            
            <div class="glass-card rounded-2xl p-6 border border-forest/10 bg-white/40 hover:bg-white/60 transition-all duration-300 shadow-sm" x-data="{ val: {{ old("scores.{$criterion->id}.score", $existingScore?->score ?? 5) }} }">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-forest/10 text-forest text-[10px] font-black flex items-center justify-center">{{ $loop->iteration }}</span>
                            <h3 class="font-extrabold text-sm text-forest tracking-tight">{{ $criterion->name }}</h3>
                        </div>
                        <p class="text-xs text-forest/50 font-semibold mt-1.5 ml-8 leading-relaxed">{{ $criterion->description }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-md text-[9px] font-extrabold bg-forest/5 text-forest/60 border border-forest/10 whitespace-nowrap">Weight: {{ number_format($criterion->weight * 100) }}%</span>
                </div>
                
                <div class="space-y-4 ml-8">
                    {{-- Custom Slider --}}
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="text-[9px] font-extrabold text-forest/40 uppercase tracking-widest">Score Assessment</span>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-forest/60 uppercase tracking-wider" x-text="val < 4 ? 'Critical' : (val < 7 ? 'Satisfactory' : (val < 9 ? 'Strong' : 'Exceptional'))"></span>
                                <span class="text-xs font-black px-2 py-0.5 rounded bg-forest text-cream" x-text="val"></span>
                            </div>
                        </div>
                        <input type="range" name="scores[{{ $criterion->id }}][score]" min="1" max="10" x-model="val" class="w-full accent-forest cursor-pointer h-1.5 bg-forest/10 rounded-lg appearance-none">
                        <div class="flex justify-between text-[8px] font-extrabold text-forest/30 px-1 mt-1.5 uppercase tracking-wider">
                            <span>1 (Critical)</span>
                            <span>5 (Neutral)</span>
                            <span>10 (Mastery)</span>
                        </div>
                        @error("scores.{$criterion->id}.score")<p class="text-rose-500 font-bold text-[10px] mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    {{-- Justification --}}
                    <div>
                        <label class="block text-[9px] font-extrabold text-forest/40 uppercase tracking-widest mb-2">Justification & Comments</label>
                        <textarea name="scores[{{ $criterion->id }}][justification]" rows="3" class="w-full text-xs font-semibold text-forest/80 bg-white/60 border border-forest/10 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest transition-all placeholder-forest/30" required placeholder="Provide clear, analytical reasoning for this score...">{{ old("scores.{$criterion->id}.justification", $existingScore?->justification ?? '') }}</textarea>
                        @error("scores.{$criterion->id}.justification")<p class="text-rose-500 font-bold text-[10px] mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-4 pt-4">
            <button type="button" id="btn-submit-final" class="bg-forest text-cream hover:bg-forest-light text-xs font-black uppercase tracking-wider py-3 px-6 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg flex items-center gap-2" onclick="submitAssessment('submit')">
                <i data-lucide="check" class="w-4 h-4"></i>
                Submit Final Assessment
            </button>
            <button type="button" id="btn-save-draft" class="border border-forest/20 text-forest/70 hover:text-forest bg-white/20 hover:bg-forest/5 text-xs font-black uppercase tracking-wider py-3 px-6 rounded-xl transition-all duration-300 flex items-center gap-2" onclick="submitAssessment('draft')">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save as Draft
            </button>
            <a href="{{ route('resources.show', $resource) }}" class="text-forest/50 hover:text-forest text-xs font-black uppercase tracking-widest ml-auto transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function submitAssessment(action) {
    var form = document.getElementById('assessmentForm');

    // Validate required fields first
    if (!form.reportValidity()) {
        return; // Browser will show native validation messages
    }

    // For final submit, show confirmation dialog
    if (action === 'submit') {
        if (!confirm('Submit this assessment? Once submitted, it cannot be modified without administrator unlock.')) {
            return;
        }
    }

    // Inject hidden input for the action value
    var existing = form.querySelector('input[name="action"]');
    if (existing) existing.remove();
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'action';
    input.value = action;
    form.appendChild(input);

    // Submit the form
    form.submit();
}
</script>
@endsection
