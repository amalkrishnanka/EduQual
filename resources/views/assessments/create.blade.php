@extends('layouts.app')
@section('header', isset($assessment) ? 'Edit Assessment' : 'New Assessment')
@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in">
    {{-- Resource Info --}}
    <div class="glass rounded-xl p-5">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-indigo-600/30 to-violet-600/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-indigo-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <h2 class="font-semibold">{{ $resource->title }}</h2>
                <p class="text-sm text-slate-400">{{ $resource->author }} · {{ $resource->subject }}</p>
            </div>
        </div>
    </div>

    {{-- Assessment Form --}}
    <form method="POST" action="{{ isset($assessment) ? route('assessments.update', $assessment) : route('assessments.store') }}" x-data="{ scores: {} }">
        @csrf
        @if(isset($assessment)) @method('PUT') @endif
        <input type="hidden" name="resource_id" value="{{ $resource->id }}">

        <div class="space-y-4">
            @foreach($criteria as $criterion)
            @php $existingScore = isset($existingScores) ? ($existingScores[$criterion->id] ?? null) : null; @endphp
            <div class="glass rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-sm">{{ $criterion->name }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $criterion->description }}</p>
                    </div>
                    <span class="text-xs text-slate-500">Weight: {{ number_format($criterion->weight * 100) }}%</span>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Score (1-10) *</label>
                        <div class="flex items-center gap-3" x-data="{ val: {{ old("scores.{$criterion->id}.score", $existingScore?->score ?? 5) }} }">
                            <input type="range" name="scores[{{ $criterion->id }}][score]" min="1" max="10" x-model="val" class="flex-1 h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                            <span class="text-lg font-bold w-8 text-center" :class="val >= 8 ? 'text-emerald-400' : (val >= 6 ? 'text-cyan-400' : (val >= 4 ? 'text-amber-400' : 'text-rose-400'))" x-text="val"></span>
                        </div>
                        @error("scores.{$criterion->id}.score")<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Justification *</label>
                        <textarea name="scores[{{ $criterion->id }}][justification]" rows="3" class="form-input text-sm" required placeholder="Provide your reasoning for this score...">{{ old("scores.{$criterion->id}.justification", $existingScore?->justification ?? '') }}</textarea>
                        @error("scores.{$criterion->id}.justification")<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" name="action" value="draft" class="btn btn-secondary">Save as Draft</button>
            <button type="submit" name="action" value="submit" class="btn btn-primary" onclick="return confirm('Submit this assessment? Once submitted, it cannot be edited without admin approval.')">Submit Final Assessment</button>
            <a href="{{ route('resources.show', $resource) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
