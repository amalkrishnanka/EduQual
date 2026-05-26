<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - Bookly</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Lucide CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#fbfbf8] text-forest font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-x-hidden">

    {{-- Subtle Ambient Radial Glows --}}
    <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-forest/5 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-forest/5 blur-3xl pointer-events-none"></div>

    {{-- Floating Back to Home Tag --}}
    <a href="{{ url('/') }}" class="absolute top-6 left-6 inline-flex items-center gap-2 text-forest/70 hover:text-forest transition font-bold text-xs bg-white/80 backdrop-blur-md border border-forest/10 px-4 py-2 rounded-full shadow-sm">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        <span>Back to home</span>
    </a>

    {{-- The Bento Auth Pod --}}
    <div class="w-full max-w-4xl bg-white/40 backdrop-blur-xl border border-forest/8 rounded-[32px] shadow-[0_32px_80px_rgba(26,59,43,0.06),0_1px_2px_rgba(26,59,43,0.01)] overflow-hidden p-2 grid grid-cols-1 md:grid-cols-12 gap-2 animate-fade-in-up">
        
        {{-- Left: Form Column --}}
        <div class="md:col-span-5 bg-[#fbfbf8] rounded-[24px] border border-forest/5 p-6 sm:p-8 flex flex-col justify-between shadow-inner-sm">
            
            {{-- Logo --}}
            <div class="flex items-center gap-2 mb-8 shrink-0">
                <div class="w-6.5 h-6.5 rounded-xl bg-forest text-cream flex items-center justify-center font-heading font-black text-xs shadow-[0_3px_8px_rgba(26,59,43,0.12)]">
                    B.
                </div>
                <span class="text-sm font-black font-heading tracking-tight text-forest">Bookly</span>
            </div>

            {{-- Form Fields Block --}}
            <div class="flex-grow flex flex-col justify-center">
                <div class="mb-6">
                    <h1 class="text-xl font-black font-heading tracking-tight text-forest mb-1">Welcome Back</h1>
                    <p class="text-forest/60 text-[11px] font-medium leading-relaxed">Sign in to resume cataloging your personal library.</p>
                </div>

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-red-50/70 border border-red-200/50 backdrop-blur-sm">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-red-600 shrink-0"></i>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-red-800">Errors found</p>
                        </div>
                        <ul class="space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li class="text-[10px] font-semibold text-red-700 pl-4 list-disc">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-[9px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               class="w-full px-3.5 py-2 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest transition-all duration-300 shadow-inner-sm placeholder:text-forest/30" 
                               placeholder="you@example.com">
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-[9px] font-bold uppercase tracking-wider text-forest/75">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[9px] font-bold uppercase tracking-wider text-forest/50 hover:text-forest transition-colors">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full px-3.5 py-2 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest transition-all duration-300 shadow-inner-sm placeholder:text-forest/30" 
                               placeholder="••••••••">
                    </div>

                    {{-- Remember Checkbox --}}
                    <div class="flex items-center py-0.5">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-forest/20 text-forest focus:ring-forest cursor-pointer bg-white">
                            <span class="text-[11px] font-medium text-forest/60 group-hover:text-forest transition-colors select-none">Keep me signed in</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full bg-forest text-cream font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl hover:bg-forest/95 hover:-translate-y-0.5 active:translate-y-0 active:scale-98 transition-all duration-300 flex items-center justify-center gap-1.5 group shadow-sm mt-1">
                        <span>Sign In</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5"></i>
                    </button>
                </form>

                {{-- Sign up Link --}}
                <p class="text-center text-[11px] font-semibold text-forest/55 mt-5">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-extrabold text-forest hover:text-forest/80 underline decoration-forest/30 hover:decoration-forest transition-all pl-0.5">Register here</a>
                </p>
            </div>

            {{-- Footer --}}
            <div class="mt-8 text-center text-[9px] font-bold uppercase tracking-wider text-forest/35 shrink-0">
                &copy; {{ date('Y') }} Bookly.
            </div>

        </div>

        {{-- Right: Illustration Column --}}
        <div class="hidden md:flex md:col-span-7 bg-[#ece5d4]/40 rounded-[24px] border border-forest/5 p-8 flex-col items-center justify-center relative overflow-hidden">
            
            {{-- Floating Ambient Light --}}
            <div class="absolute w-64 h-64 rounded-full bg-yellow-100/20 blur-3xl -top-20 -right-20 pointer-events-none"></div>
            
            {{-- Clean Vector Illustration Frame --}}
            <div class="w-full max-w-[350px] aspect-square relative hover:scale-[1.02] transition-transform duration-500">
                <img src="{{ asset('images/login_reading.png') }}" alt="Cozy Reading Nook Illustration" class="w-full h-full object-contain rounded-2xl shadow-[0_12px_30px_rgba(26,59,43,0.08)]" />
            </div>

            {{-- Caption / Welcome Quote --}}
            <div class="text-center max-w-[320px] mt-6">
                <h3 class="font-heading text-sm font-extrabold text-forest tracking-tight mb-1">Explore Endless Stories</h3>
                <p class="text-forest/60 text-[10px] leading-relaxed font-medium">"A reader lives a thousand lives before he dies. The man who never reads lives only one."</p>
            </div>

        </div>

    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
</body>
</html>
