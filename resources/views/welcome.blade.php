<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EduQual — Assess, analyze, and elevate educational resource quality with structured scoring, analytics, and collaborative review.">
    <title>EduQual — Educational Resource Quality Assessment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white min-h-screen overflow-x-hidden">
    {{-- Animated background --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/3 -right-20 w-80 h-80 bg-violet-600/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
        <div class="absolute -bottom-32 left-1/3 w-72 h-72 bg-fuchsia-600/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
    </div>

    {{-- Navigation --}}
    <nav class="relative z-10 flex items-center justify-between px-6 lg:px-12 py-5">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xl font-bold gradient-text">EduQual</span>
        </div>
        <div class="hidden md:flex items-center gap-8">
            <a href="#features" class="text-sm text-slate-300 hover:text-white transition">Features</a>
            <a href="#stats" class="text-sm text-slate-300 hover:text-white transition">About</a>
        </div>
        <div class="flex items-center gap-3">
            @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="text-sm text-slate-300 hover:text-white transition px-4 py-2">Sign In</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Get Started</a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative z-10 flex flex-col items-center text-center px-6 pt-20 pb-28">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-sm text-indigo-300 mb-6 animate-fade-in">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            Quality Assessment Platform
        </div>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold leading-tight max-w-4xl animate-fade-in delay-100">
            Elevate <span class="gradient-text">Educational</span> Excellence
        </h1>
        <p class="text-lg md:text-xl text-slate-400 mt-6 max-w-2xl animate-fade-in delay-200">
            A comprehensive platform for assessing, analyzing, and improving the quality of textbooks, reference books, and e-books across your institution.
        </p>
        <div class="flex flex-col sm:flex-row items-center gap-4 mt-10 animate-fade-in delay-300">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                Get Started Free
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="#features" class="btn btn-secondary btn-lg">Learn More</a>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="relative z-10 px-6 lg:px-12 pb-24">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-4">Everything You Need</h2>
            <p class="text-slate-400 text-center mb-12 max-w-xl mx-auto">Powerful tools for evaluating educational resources with data-driven precision</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>', 'color' => 'indigo', 'title' => 'Structured Assessment', 'desc' => 'Score resources across 5 key criteria — Accuracy, Relevance, Readability, Engagement, and Pedagogical Value — with configurable weights.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>', 'color' => 'violet', 'title' => 'Analytics & Insights', 'desc' => 'Interactive charts, score distributions, reviewer activity tracking, and side-by-side comparisons to make data-driven curriculum decisions.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>', 'color' => 'emerald', 'title' => 'Collaborative Review', 'desc' => 'Multi-role workflow with reviewers, admins, and viewers. Flag inaccurate content, track resolutions, and export reports in PDF/CSV.']
                ] as $feature)
                <div class="glass rounded-xl p-6 card-hover animate-fade-in" style="animation-delay: {{ $loop->index * 0.15 }}s">
                    <div class="w-12 h-12 rounded-xl bg-{{ $feature['color'] }}-500/20 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-{{ $feature['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $feature['icon'] !!}</svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section id="stats" class="relative z-10 px-6 lg:px-12 pb-24">
        <div class="max-w-4xl mx-auto">
            <div class="glass rounded-2xl p-8 md:p-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center" x-data="{ shown: false }" x-intersect="shown = true">
                    @foreach([['num' => '500+', 'label' => 'Resources Catalogued'], ['num' => '2,000+', 'label' => 'Assessments Completed'], ['num' => '50+', 'label' => 'Institutions']] as $stat)
                    <div class="animate-fade-in" style="animation-delay: {{ $loop->index * 0.2 }}s">
                        <p class="text-4xl md:text-5xl font-extrabold gradient-text">{{ $stat['num'] }}</p>
                        <p class="text-sm text-slate-400 mt-2">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="relative z-10 border-t border-slate-800 px-6 py-8">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-md bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="font-bold">EduQual</span>
            </div>
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} EduQual. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
