@extends('layouts.app')
@section('header')
<div class="flex items-center gap-2 text-sm text-forest/60">
    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
    <span class="font-medium">Dashboard</span>
</div>
@endsection

@section('content')
<div class="space-y-5 animate-fade-in text-forest">

    {{-- ═══════════════════════════════════════════
         ASYMMETRICAL BENTO GRID - ROW 1
         ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        
        {{-- BENTO ITEM 1: THE ACCENT HERO BANNER (Spans 3 Columns on Large Screens) --}}
        <div class="lg:col-span-3 hero-banner-custom p-6 text-cream rounded-2xl relative overflow-hidden flex flex-col justify-between min-h-[160px] shadow-md">
            {{-- Aesthetic background glow --}}
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-10 bottom-0 w-32 h-32 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 z-10 w-full">
                <div class="space-y-1">
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] bg-white/10 text-cream/90 px-2 py-0.5 rounded-full">
                        {{ str_replace('_', ' ', auth()->user()->role ?? 'viewer') }} Portal
                    </span>
                    <h2 class="text-xl md:text-2xl font-extrabold font-heading tracking-tight mt-2 leading-tight">
                        Welcome back, {{ auth()->user()->name }} 👋
                    </h2>
                    <p class="text-xs text-cream/75 max-w-md font-light leading-relaxed mt-1">
                        Your custom literary desk is ready. Explore curated book assessments, manage reading shelves, and see top-rated choices.
                    </p>
                </div>
                
                {{-- Cozy Study Workspace Illustration --}}
                <div class="hidden sm:block shrink-0">
                    <div class="w-24 h-24 relative select-none hover:scale-[1.04] transition-all duration-500 rounded-xl overflow-hidden border border-white/20 shadow-[0_8px_20px_rgba(26,59,43,0.15)]">
                        <img src="{{ asset('images/dashboard_illustration.png') }}" alt="Literary Desk" class="w-full h-full object-cover" />
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4 mt-4 pt-4 border-t border-white/10 z-10 text-[11px] text-cream/60">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span>{{ now()->format('l, M d') }}</span>
                </div>
                <div class="w-1 h-1 bg-white/30 rounded-full"></div>
                <div class="flex items-center gap-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    <span>System active</span>
                </div>
            </div>
        </div>

        {{-- BENTO ITEM 2: QUICK ACTION PANEL (Spans 1 Column on Large Screens) --}}
        <div class="lg:col-span-1 glass-card p-5 bg-[#fcf9f2] border-forest/15 rounded-2xl flex flex-col justify-between min-h-[160px] hover:border-forest/30 transition-all duration-300">
            <div>
                <div class="w-8 h-8 rounded-lg bg-forest/5 flex items-center justify-center shrink-0 mb-3">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-forest"></i>
                </div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-forest/70 font-heading">Literary Tools</h3>
                <p class="text-[11px] text-forest/50 mt-1 font-light leading-snug">
                    Access our dynamic assessments and resource review catalogue.
                </p>
            </div>
            
            <a href="{{ route('resources.index') }}" class="btn btn-primary text-xs py-2 px-3 w-full flex items-center justify-center gap-1.5 shadow-sm mt-4">
                <span>Browse Catalogue</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════
         ASYMMETRICAL BENTO GRID - ROW 2 (UNIFIED SYSTEM METRICS)
         ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Stat 1: Total Catalogued Books --}}
        <div class="glass-card p-4 card-hover flex items-center justify-between border-forest/10">
            <div class="space-y-1">
                <p class="text-[9px] font-bold text-forest/40 uppercase tracking-widest">Total Books</p>
                <p class="text-2xl font-extrabold font-heading text-forest leading-none">{{ $totalResources }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-forest/5 flex items-center justify-center border border-forest/5">
                <i data-lucide="book" class="w-5 h-5 text-forest"></i>
            </div>
        </div>
        
        {{-- Stat 2: Completed Assessments --}}
        <div class="glass-card p-4 card-hover flex items-center justify-between border-forest/10">
            <div class="space-y-1">
                <p class="text-[9px] font-bold text-forest/40 uppercase tracking-widest">Completed Reviews</p>
                <p class="text-2xl font-extrabold font-heading text-forest leading-none">{{ $totalAssessments }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-forest/5 flex items-center justify-center border border-forest/5">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-forest"></i>
            </div>
        </div>

        {{-- Stat 3: Registered Platform Users --}}
        <div class="glass-card p-4 card-hover flex items-center justify-between border-forest/10">
            <div class="space-y-1">
                <p class="text-[9px] font-bold text-forest/40 uppercase tracking-widest">Active Accounts</p>
                <p class="text-2xl font-extrabold font-heading text-forest leading-none">{{ $totalUsers }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-forest/5 flex items-center justify-center border border-forest/5">
                <i data-lucide="users" class="w-5 h-5 text-forest"></i>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ASYMMETRICAL BENTO GRID - ROW 3 (UNIFIED DETAILS & DATA)
         ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        {{-- BENTO MAIN COLUMN (Spans 2 Columns on Large Screens) --}}
        <div class="lg:col-span-2 glass-card p-5 flex flex-col justify-between min-h-[300px]">
            <div class="flex flex-col h-full justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-forest/5">
                        <h3 class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                            <i data-lucide="clipboard-list" class="w-4 h-4 text-forest/70"></i>
                            <span>Recent System Assessments</span>
                        </h3>
                        <a href="{{ route('assessments.index') }}" class="text-[10px] font-bold text-forest/60 hover:text-forest flex items-center gap-0.5">
                            <span>Show log</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                    
                    <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                        @forelse($recentAssessments as $assessment)
                        <a href="{{ route('assessments.show', $assessment) }}" class="block glass-light rounded-xl p-3 border border-forest/5 card-hover">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-bold text-xs truncate text-forest">{{ $assessment->resource->title }}</p>
                                    <p class="text-[10px] text-forest/50 mt-0.5">Assessed by {{ $assessment->reviewer->name }}</p>
                                </div>
                                <span class="status-{{ $assessment->status }} px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wide uppercase shrink-0">
                                    {{ $assessment->status }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <i data-lucide="clipboard" class="w-8 h-8 text-forest/20 mb-2"></i>
                            <p class="text-xs text-forest/50">No assessments gathered yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- BENTO SECONDARY COLUMN (Spans 1 Column on Large Screens) --}}
        <div class="lg:col-span-1 glass-card p-5 flex flex-col justify-between min-h-[300px]">
            <div class="flex flex-col h-full justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-forest/5">
                        <h3 class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-500/80"></i>
                            <span>Recent System Flags</span>
                        </h3>
                        <a href="{{ route('flags.index') }}" class="text-[10px] font-bold text-rose-500/70 hover:text-rose-600 flex items-center gap-0.5">
                            <span>Resolve</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                    
                    <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                        @forelse($recentFlags as $flag)
                        <a href="{{ route('flags.show', $flag) }}" class="block glass-light rounded-xl p-2.5 border border-forest/5 card-hover">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-bold text-[11px] truncate text-forest leading-tight">${{ $flag->title }}</p>
                                    <p class="text-[9px] text-forest/45 mt-0.5 truncate">{{ $flag->resource->title }}</p>
                                </div>
                                <span class="status-{{ str_replace('_', '-', $flag->status) }} px-1.5 py-0.5 rounded-full text-[8px] font-bold tracking-wide uppercase shrink-0">
                                    {{ str_replace('_', ' ', $flag->status) }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <i data-lucide="shield-check" class="w-6 h-6 text-forest/20 mb-1"></i>
                            <p class="text-[10px] text-forest/45">System fully secured</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Cicero Literary Quote widget --}}
                <div class="pt-3 border-t border-forest/5">
                    <div class="p-2.5 bg-forest/5 rounded-xl border border-forest/5">
                        <p class="text-[11px] italic text-forest/70 leading-relaxed">
                            "A room without books is like a body without a soul."
                        </p>
                        <p class="text-[9px] font-bold text-forest/50 mt-1.5 text-right">— Marcus Tullius Cicero</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
