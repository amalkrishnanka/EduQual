<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Bookly</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-cream text-forest font-sans min-h-screen antialiased" x-data="{ sidebarOpen: false }">

    {{-- ═══════════════════════════════════════════
         MOBILE OVERLAY
         ═══════════════════════════════════════════ --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
         style="display: none;">
    </div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 transition-transform duration-300 ease-in-out lg:translate-x-0 capsule-sidebar">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 h-16 border-b border-forest/10 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-forest text-cream flex items-center justify-center font-heading font-bold text-lg leading-none pt-0.5 shadow-sm">
                B.
            </div>
            <div>
                <h1 class="text-base font-bold font-heading text-forest tracking-tight leading-tight">Bookly</h1>
                <p class="text-[9px] text-forest/50 font-bold uppercase tracking-[0.2em]">Platform</p>
            </div>
            {{-- Close button (mobile) --}}
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-forest/60 hover:text-forest transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>

            {{-- Resources --}}
            <div class="pt-4 pb-1 px-3">
                <p class="text-[9px] font-semibold text-forest/40 uppercase tracking-widest">Resources</p>
            </div>
            <a href="{{ route('resources.index') }}" class="sidebar-link {{ request()->routeIs('resources.*') ? 'active' : '' }}">
                <i data-lucide="book-open"></i>
                <span>Browse Resources</span>
            </a>

            {{-- Assessments --}}
            <div class="pt-4 pb-1 px-3">
                <p class="text-[9px] font-semibold text-forest/40 uppercase tracking-widest">Assessments</p>
            </div>
            <a href="{{ route('assessments.index') }}" class="sidebar-link {{ request()->routeIs('assessments.*') ? 'active' : '' }}">
                <i data-lucide="clipboard-check"></i>
                <span>All Assessments</span>
            </a>

            {{-- Analytics & Insights --}}
            <div class="pt-4 pb-1 px-3">
                <p class="text-[9px] font-semibold text-forest/40 uppercase tracking-widest">Insights</p>
            </div>
            <a href="{{ route('analytics.index') }}" class="sidebar-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                <i data-lucide="line-chart"></i>
                <span>Analytics</span>
            </a>
            <a href="{{ route('recommendations.index') }}" class="sidebar-link {{ request()->routeIs('recommendations.*') ? 'active' : '' }}">
                <i data-lucide="sparkles"></i>
                <span>Recommendations</span>
            </a>

            {{-- Flags & Reviews --}}
            <div class="pt-4 pb-1 px-3">
                <p class="text-[9px] font-semibold text-forest/40 uppercase tracking-widest">Reviews</p>
            </div>
            <a href="{{ route('flags.index') }}" class="sidebar-link {{ request()->routeIs('flags.*') ? 'active' : '' }}">
                <i data-lucide="flag"></i>
                <span>Flags & Reviews</span>
            </a>

            {{-- Reports --}}
            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i data-lucide="file-text"></i>
                <span>Reports</span>
            </a>

            {{-- Admin Section --}}
            @if(auth()->user() && auth()->user()->isSuperAdmin())
                <div class="pt-4 pb-1 px-3">
                    <p class="text-[9px] font-semibold text-rose-500/50 uppercase tracking-widest">Administration</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i data-lucide="settings"></i>
                    <span>Settings</span>
                </a>
            @endif
        </nav>

        <div class="p-3 border-t border-forest/10 shrink-0">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-8 h-8 rounded-full bg-forest text-cream flex items-center justify-center text-sm font-bold shadow-sm">
                    {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-forest truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[9px] text-forest/50 uppercase tracking-wider font-bold capitalize">{{ str_replace('_', ' ', auth()->user()->role ?? 'viewer') }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT AREA
         ═══════════════════════════════════════════ --}}
    <div class="lg:pl-[272px] min-h-screen flex flex-col">

        <header class="sticky top-0 z-30 h-16 bg-cream/90 backdrop-blur-xl border-b border-forest/10 flex items-center px-4 sm:px-6 lg:px-8 gap-4 shrink-0">
            {{-- Mobile menu button --}}
            <button @click="sidebarOpen = true" class="lg:hidden text-forest/60 hover:text-forest transition-colors -ml-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>

            {{-- Header / Breadcrumb --}}
            <div class="flex-1">
                @yield('header')
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                        class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-black/5 transition-all group">
                    <div class="w-7 h-7 rounded-full bg-forest text-cream flex items-center justify-center text-xs font-bold shadow-sm">
                        {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-semibold text-forest group-hover:text-forest-dark leading-none">{{ auth()->user()->name ?? 'User' }}</p>
                    </div>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-forest/40 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl overflow-hidden border border-forest/5"
                     style="display: none;">
                    <div class="p-2.5 border-b border-forest/5">
                        <p class="text-xs font-bold text-forest">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-[10px] text-forest/50 mt-0.5 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <div class="p-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-xs text-forest/80 hover:text-forest hover:bg-forest/5 transition-colors">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-forest/50"></i>
                            <span>Profile Settings</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-xs text-red-600 hover:text-red-700 hover:bg-red-50 transition-colors">
                                <i data-lucide="log-out" class="w-3.5 h-3.5 text-red-500/50"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif
            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif
            @if(session('warning'))
                <x-alert type="warning" :message="session('warning')" />
            @endif
            @if(session('info'))
                <x-alert type="info" :message="session('info')" />
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</body>
</html>
