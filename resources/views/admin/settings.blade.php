@extends('layouts.app')
@section('header', 'Settings')
@section('content')
<div class="max-w-2xl mx-auto animate-fade-in">
    <div class="glass rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-2">Criterion Weights</h2>
        <p class="text-sm text-slate-400 mb-6">Configure the weight for each assessment criterion. Weights must sum to 1.00.</p>
        <form method="POST" action="{{ route('admin.settings.weights') }}" x-data="{ weights: { @foreach($criteria as $c) {{ $c->id }}: {{ $c->weight }}, @endforeach }, get total() { return Object.values(this.weights).reduce((a,b) => a + parseFloat(b || 0), 0); } }">
            @csrf @method('PUT')
            <div class="space-y-5">
                @foreach($criteria as $criterion)
                <div class="glass-light rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div><h4 class="font-medium text-sm">{{ $criterion->name }}</h4><p class="text-xs text-slate-400">{{ $criterion->description }}</p></div>
                        <span class="text-sm font-mono font-bold text-indigo-400" x-text="parseFloat(weights[{{ $criterion->id }}]).toFixed(2)"></span>
                    </div>
                    <input type="range" name="weights[{{ $criterion->id }}]" min="0" max="1" step="0.05" x-model="weights[{{ $criterion->id }}]" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                </div>
                @endforeach
            </div>
            <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-700/50">
                <div>
                    <span class="text-sm text-slate-400">Total Weight: </span>
                    <span class="font-bold font-mono" :class="Math.abs(total - 1) < 0.01 ? 'text-emerald-400' : 'text-rose-400'" x-text="total.toFixed(2)"></span>
                    <span class="text-xs ml-2" :class="Math.abs(total - 1) < 0.01 ? 'text-emerald-400' : 'text-rose-400'" x-text="Math.abs(total - 1) < 0.01 ? '✓ Valid' : '✗ Must equal 1.00'"></span>
                </div>
                <button type="submit" class="btn btn-primary" :disabled="Math.abs(total - 1) >= 0.01">Save Weights</button>
            </div>
        </form>
    </div>
</div>
@endsection
