@props(['score'])
@php
$colorClass = $score >= 8 ? 'score-excellent' : ($score >= 6 ? 'score-good' : ($score >= 4 ? 'score-average' : 'score-poor'));
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold text-white {{ $colorClass }}">
    {{ number_format($score, 1) }}
</span>
