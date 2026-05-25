@props(['type' => 'draft', 'text' => ''])

@php
    $typeClasses = [
        'draft'        => 'status-draft',
        'submitted'    => 'status-submitted',
        'open'         => 'status-open',
        'under_review' => 'status-under-review',
        'resolved'     => 'status-resolved',
        'dismissed'    => 'status-dismissed',

        // Role badges
        'super_admin'  => 'bg-rose-500/15 text-rose-300 border border-rose-500/30',
        'reviewer'     => 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/30',
        'viewer'       => 'bg-slate-500/15 text-slate-300 border border-slate-500/30',
    ];
    $class = $typeClasses[$type] ?? 'status-draft';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {$class}"]) }}>
    {{ $text ?: ucfirst(str_replace('_', ' ', $type)) }}
</span>
