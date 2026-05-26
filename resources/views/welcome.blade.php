<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bookly - Find your next favorite book here.">
    <title>Bookly | Your Next Favorite Book</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-cream text-forest min-h-screen overflow-x-hidden antialiased font-sans">
    
    {{-- ═══════════════════════════════════════════
         IPHONE-STYLE FLOATING PILL NAVBAR (STICKY)
         ═══════════════════════════════════════════ --}}
    <div class="fixed top-5 left-0 right-0 z-50 px-4 sm:px-6">
        <nav x-data="{ 
            activeSection: 'home',
            init() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            let id = entry.target.id;
                            if (entry.target.tagName === 'HEADER') id = 'home';
                            if (id) {
                                this.activeSection = id;
                            }
                        }
                    });
                }, { threshold: 0.15, rootMargin: '-20% 0px -40% 0px' });
                
                document.querySelectorAll('header, section[id]').forEach(el => observer.observe(el));
            }
        }" class="max-w-4xl mx-auto rounded-full bg-white/75 backdrop-blur-xl border border-forest/10 px-6 py-3 flex items-center justify-between shadow-[0_20px_50px_-12px_rgba(26,59,43,0.1)]">
            
            {{-- Brand Logo --}}
            <a href="#" class="flex items-center gap-2.5 shrink-0 group">
                <div class="w-8 h-8 rounded-xl bg-forest text-cream flex items-center justify-center font-heading font-black text-sm shadow-[0_4px_12px_rgba(26,59,43,0.15)] group-hover:scale-105 transition-all duration-300">
                    B.
                </div>
                <span class="text-sm font-extrabold font-heading text-forest tracking-tight group-hover:text-forest/90 transition-colors">Bookly</span>
            </a>
            
            {{-- Navigation Links (Clean, Spacious, Title Case) --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="#" 
                   @click="activeSection = 'home'"
                   :class="activeSection === 'home' ? 'text-forest font-bold after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-1 after:h-1 after:bg-forest after:rounded-full' : 'text-forest/60 font-medium hover:text-forest'"
                   class="text-xs tracking-wide relative py-1 transition-all duration-300">
                    Home
                </a>
                <a href="#featured-books" 
                   @click="activeSection = 'featured-books'"
                   :class="activeSection === 'featured-books' ? 'text-forest font-bold after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-1 after:h-1 after:bg-forest after:rounded-full' : 'text-forest/60 font-medium hover:text-forest'"
                   class="text-xs tracking-wide relative py-1 transition-all duration-300">
                    Books & Categories
                </a>
                <a href="#blog" 
                   @click="activeSection = 'blog'"
                   :class="activeSection === 'blog' ? 'text-forest font-bold after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-1 after:h-1 after:bg-forest after:rounded-full' : 'text-forest/60 font-medium hover:text-forest'"
                   class="text-xs tracking-wide relative py-1 transition-all duration-300">
                    Blog
                </a>
                <a href="#faqs" 
                   @click="activeSection = 'faqs'"
                   :class="activeSection === 'faqs' ? 'text-forest font-bold after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-1 after:h-1 after:bg-forest after:rounded-full' : 'text-forest/60 font-medium hover:text-forest'"
                   class="text-xs tracking-wide relative py-1 transition-all duration-300">
                    FAQs
                </a>
            </div>
            
            {{-- Authentication Actions --}}
            <div class="flex items-center gap-5 shrink-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary text-xs py-2 px-5 rounded-full flex items-center gap-1.5 shadow-sm">
                        <span>Dashboard</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-forest/75 hover:text-forest transition-colors py-1">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-xs py-2 px-5 rounded-full shadow-sm hover:shadow-[0_4px_12px_rgba(26,59,43,0.15)] active:scale-98 transition-all">
                        Register
                    </a>
                @endauth
            </div>
            
        </nav>
    </div>

    {{-- ═══════════════════════════════════════════
         HERO SECTION WITH BOOKS SHELF
         ═══════════════════════════════════════════ --}}
    <header id="home" class="relative pt-32 pb-0 px-6 flex flex-col items-center text-center">
        <div class="max-w-3xl mx-auto z-10 space-y-4 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-extrabold font-heading leading-[1.1] tracking-tight">
                Find your next <br/>
                <span class="text-forest">favorite book</span> here.
            </h1>
            
            <p class="text-xs md:text-sm text-forest/75 max-w-xl mx-auto leading-relaxed">
                Join thousands of book lovers and browse our curated genres, discover new authors, and get lost in a great story.
            </p>
            
            <div class="pt-2">
                <a href="{{ route('register') }}" class="btn btn-primary px-5 py-2 rounded-full text-xs font-semibold shadow-md inline-flex items-center gap-1.5">
                    <span>Start now</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
        
        {{-- Elegant Book Shelf Image (Original) --}}
        <div class="w-full max-w-3xl mx-auto mt-6 relative z-0 h-[140px] md:h-[180px] overflow-hidden rounded-xl">
            <img src="{{ asset('images/hero_books.png') }}" alt="Row of colorful books" class="w-full h-full object-cover object-[center_50%]" />
        </div>
    </header>

    {{-- ═══════════════════════════════════════════
         BENTO FEATURED BOOKS GRID (Original structure but minimal)
         ═══════════════════════════════════════════ --}}
    <section id="featured-books" class="py-16 px-6 bg-[#f2ebd9] border-y border-forest/10">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                {{-- Left Text Side --}}
                <div class="lg:col-span-5 space-y-5 animate-fade-in">
                    <h2 class="text-2xl md:text-3xl font-extrabold font-heading leading-tight text-forest">
                        The Best <span class="italic text-forest-light">Books</span> for Every Chapter of Your Life, Bringing New Meaning to Your <span class="underline decoration-forest-light decoration-2 underline-offset-4 font-extrabold">Journey.</span>
                    </h2>
                    <p class="text-xs text-forest/75 leading-relaxed font-light">
                        Explore thousands of books ready to accompany your every step, and find the perfect story for you.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-primary text-xs py-2 px-4 shadow-sm inline-flex items-center gap-1.5">
                        <span>Start Your Journey</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                {{-- Right Grid Side (Original books grid with compact glass containers) --}}
                <div class="lg:col-span-7 grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade-in">
                    
                    {{-- Column 1 --}}
                    <div class="flex flex-col gap-3">
                        <div class="bg-white/50 backdrop-blur-md p-3 border border-forest/10 rounded-xl">
                            <img src="{{ asset('images/book_1.png') }}" alt="The Midnight Garden" class="rounded-lg shadow-md w-full h-auto transform transition duration-500 hover:scale-105 hover:-rotate-1" />
                        </div>
                        <div class="bg-white/60 backdrop-blur-sm p-3 border border-forest/5 rounded-xl">
                            <h4 class="font-extrabold font-heading text-[10px] uppercase tracking-wider text-forest">Top Seller</h4>
                            <p class="text-[9px] text-forest/50 font-bold uppercase mt-0.5">Fantasy</p>
                        </div>
                    </div>
                    
                    {{-- Column 2 --}}
                    <div class="flex flex-col gap-3 md:mt-6">
                        <div class="bg-white/60 backdrop-blur-sm p-3 border border-forest/5 rounded-xl">
                            <h4 class="font-extrabold font-heading text-[10px] uppercase tracking-wider text-forest">New Release</h4>
                            <p class="text-[9px] text-forest/50 font-bold uppercase mt-0.5">Fiction</p>
                        </div>
                        <div class="bg-white/50 backdrop-blur-md p-3 border border-forest/10 rounded-xl">
                            <img src="{{ asset('images/book_2.png') }}" alt="Salt and Sea" class="rounded-lg shadow-md w-full h-auto transform transition duration-500 hover:scale-105 hover:rotate-1" />
                        </div>
                    </div>
                    
                    {{-- Column 3 --}}
                    <div class="flex flex-col gap-3 md:-mt-3">
                        <div class="bg-white/50 backdrop-blur-md p-3 border border-forest/10 rounded-xl">
                            <img src="{{ asset('images/book_3.png') }}" alt="Orbital Drift" class="rounded-lg shadow-md w-full h-auto transform transition duration-500 hover:scale-105 hover:-rotate-2" />
                        </div>
                        <div class="bg-white/60 backdrop-blur-sm p-3 border border-forest/5 rounded-xl">
                            <h4 class="font-extrabold font-heading text-[10px] uppercase tracking-wider text-forest">Trending</h4>
                            <p class="text-[9px] text-forest/50 font-bold uppercase mt-0.5">Sci-Fi</p>
                        </div>
                    </div>
                    
                    {{-- Column 4 --}}
                    <div class="flex flex-col gap-3 md:mt-8">
                        <div class="bg-white/50 backdrop-blur-md p-3 border border-forest/10 rounded-xl">
                            <img src="{{ asset('images/book_4.png') }}" alt="The Silent Witness" class="rounded-lg shadow-md w-full h-auto transform transition duration-500 hover:scale-105 hover:rotate-2" />
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         INTERACTIVE / READING ILLUSTRATION SECTION (Original)
         ═══════════════════════════════════════════ --}}
    <section class="relative py-16 px-6 overflow-hidden">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-10 relative z-10">
            
            <div class="md:w-1/2 space-y-6 animate-fade-in">
                <h2 class="text-2xl md:text-3xl font-extrabold font-heading text-forest">Welcome to the new Generation of Readers</h2>
                <p class="text-xs md:text-sm text-forest/75 leading-relaxed font-light max-w-sm">
                    Join a community that cares about culture, stories, and the magic of physical books. Discover exclusive editions and connect with authors.
                </p>
                <div class="space-y-3 text-[10px] font-bold tracking-widest uppercase text-forest">
                    <p class="border-b border-forest/15 pb-1.5 w-max cursor-pointer hover:text-forest/70 transition flex items-center gap-1">
                        <span>Explore Genres</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </p>
                    <p class="border-b border-forest/15 pb-1.5 w-max cursor-pointer hover:text-forest/70 transition flex items-center gap-1">
                        <span>Join Book Club</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </p>
                    <p class="border-b border-forest/15 pb-1.5 w-max cursor-pointer hover:text-forest/70 transition flex items-center gap-1">
                        <span>Our Mission</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </p>
                </div>
            </div>
            
            <div class="md:w-1/2 flex justify-end animate-fade-in">
                <div class="p-3 bg-white/50 backdrop-blur-sm border border-forest/10 rounded-2xl max-w-sm shadow-md">
                    <img src="{{ asset('images/reading.png') }}" alt="Reading illustration" class="w-full h-auto object-contain rounded-xl" />
                </div>
            </div>
            
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         BLOG SECTION (MINIMALIST & EDITORIAL)
         ═══════════════════════════════════════════ --}}
    <section id="blog" class="pt-20 pb-32 px-6 border-b border-forest/10">
        <div class="max-w-5xl mx-auto space-y-12">
            <div class="text-center space-y-3">
                <h2 class="text-3xl md:text-4xl font-extrabold font-heading text-forest tracking-tight">Insights & Stories</h2>
                <p class="text-xs md:text-sm text-forest/60 max-w-md mx-auto leading-relaxed">Stay updated with our latest pedagogical breakthroughs, educational analyses, and guides.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-10">
                {{-- Article 1 --}}
                <article class="flex flex-col space-y-4 group cursor-pointer">
                    <div class="h-44 md:h-48 overflow-hidden rounded-xl bg-forest/5 border border-forest/10 relative">
                        <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=600&q=80" alt="Pedagogy guide" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300">
                    </div>
                    <div class="space-y-2">
                        <span class="text-[9px] font-bold tracking-widest uppercase text-forest/50">Pedagogy</span>
                        <h3 class="font-heading font-extrabold text-base text-forest leading-snug group-hover:text-forest/75 transition-colors">Aligning Textbooks with Modern K-12 Curriculums</h3>
                        <p class="text-xs text-forest/70 font-light leading-relaxed">Essential frameworks for grading educational materials to align perfectly with cognitive developmental milestones and learning guidelines.</p>
                        <div class="text-[10px] font-medium uppercase tracking-wider text-forest/50 pt-1">
                            <span>May 24, 2026</span>
                            <span class="mx-1.5">•</span>
                            <span>5 min read</span>
                        </div>
                    </div>
                </article>
                
                {{-- Article 2 --}}
                <article class="flex flex-col space-y-4 group cursor-pointer">
                    <div class="h-44 md:h-48 overflow-hidden rounded-xl bg-forest/5 border border-forest/10 relative">
                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=600&q=80" alt="Reading room" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300">
                    </div>
                    <div class="space-y-2">
                        <span class="text-[9px] font-bold tracking-widest uppercase text-forest/50">EdTech</span>
                        <h3 class="font-heading font-extrabold text-base text-forest leading-snug group-hover:text-forest/75 transition-colors">The Shift to Digital: Assessing E-Books vs. Print</h3>
                        <p class="text-xs text-forest/70 font-light leading-relaxed">Print is tangible, but e-books offer interactivity. Learn how we adapt our Readability and Engagement metrics to assess digital learning mediums.</p>
                        <div class="text-[10px] font-medium uppercase tracking-wider text-forest/50 pt-1">
                            <span>May 18, 2026</span>
                            <span class="mx-1.5">•</span>
                            <span>7 min read</span>
                        </div>
                    </div>
                </article>
                
                {{-- Article 3 --}}
                <article class="flex flex-col space-y-4 group cursor-pointer">
                    <div class="h-44 md:h-48 overflow-hidden rounded-xl bg-forest/5 border border-forest/10 relative">
                        <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80" alt="Review process" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300">
                    </div>
                    <div class="space-y-2">
                        <span class="text-[9px] font-bold tracking-widest uppercase text-forest/50">Bookly News</span>
                        <h3 class="font-heading font-extrabold text-base text-forest leading-snug group-hover:text-forest/75 transition-colors">Introducing Weighted Criteria for Deeper Analysis</h3>
                        <p class="text-xs text-forest/70 font-light leading-relaxed">We have rolled out our new custom weight engine, allowing admins to prioritize Accuracy and Pedagogical values for specific subjects.</p>
                        <div class="text-[10px] font-medium uppercase tracking-wider text-forest/50 pt-1">
                            <span>May 12, 2026</span>
                            <span class="mx-1.5">•</span>
                            <span>4 min read</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         FAQS SECTION (MINIMALIST ACCORDION)
         ═══════════════════════════════════════════ --}}
    <section id="faqs" class="pt-32 pb-20 px-6">
        <div class="max-w-3xl mx-auto space-y-12">
            <div class="text-center space-y-3">
                <h2 class="text-3xl md:text-4xl font-extrabold font-heading text-forest tracking-tight">Frequently Asked Questions</h2>
                <p class="text-xs md:text-sm text-forest/60 max-w-md mx-auto leading-relaxed">Everything you need to know about the Bookly quality assessment system.</p>
            </div>
            
            {{-- Minimalist Dividers Accordion --}}
            <div class="border-t border-forest/15 divide-y divide-forest/15 animate-fade-in" x-data="{ activeFaq: null }">
                
                {{-- FAQ 1 --}}
                <div class="py-5 transition-all">
                    <button @click="activeFaq = activeFaq === 1 ? null : 1" class="w-full flex items-center justify-between text-left font-heading font-extrabold text-sm md:text-base text-forest select-none outline-none group">
                        <span class="group-hover:text-forest/80 transition-colors">What is Bookly?</span>
                        <span class="text-xl font-light text-forest/40 transition-transform duration-300" :class="activeFaq === 1 ? 'rotate-45 text-forest/80' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 1" x-cloak x-transition class="mt-4 text-xs md:text-sm text-forest/75 leading-relaxed font-light break-words">
                        Bookly is an advanced quality assessment system for textbooks, literature, and educational materials. It helps educators and institutions grade resources against rigorous academic criteria.
                    </div>
                </div>
                
                {{-- FAQ 2 --}}
                <div class="py-5 transition-all">
                    <button @click="activeFaq = activeFaq === 2 ? null : 2" class="w-full flex items-center justify-between text-left font-heading font-extrabold text-sm md:text-base text-forest select-none outline-none group">
                        <span class="group-hover:text-forest/80 transition-colors">Who can submit assessments?</span>
                        <span class="text-xl font-light text-forest/40 transition-transform duration-300" :class="activeFaq === 2 ? 'rotate-45 text-forest/80' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 2" x-cloak x-transition class="mt-4 text-xs md:text-sm text-forest/75 leading-relaxed font-light break-words">
                        Only verified <strong>Reviewers</strong> and <strong>Super Admins</strong> can submit structured quality assessments. Standard users have read-only access to published ratings.
                    </div>
                </div>
                
                {{-- FAQ 3 --}}
                <div class="py-5 transition-all">
                    <button @click="activeFaq = activeFaq === 3 ? null : 3" class="w-full flex items-center justify-between text-left font-heading font-extrabold text-sm md:text-base text-forest select-none outline-none group">
                        <span class="group-hover:text-forest/80 transition-colors">What are the assessment criteria?</span>
                        <span class="text-xl font-light text-forest/40 transition-transform duration-300" :class="activeFaq === 3 ? 'rotate-45 text-forest/80' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 3" x-cloak x-transition class="mt-4 text-xs md:text-sm text-forest/75 leading-relaxed font-light break-words">
                        Resources are scored out of 10 across 5 core academic pillars: Accuracy, Relevance, Readability, Engagement, and Pedagogical Value, producing an expert-reviewed weighted score.
                    </div>
                </div>
                
                {{-- FAQ 4 --}}
                <div class="py-5 transition-all">
                    <button @click="activeFaq = activeFaq === 4 ? null : 4" class="w-full flex items-center justify-between text-left font-heading font-extrabold text-sm md:text-base text-forest select-none outline-none group">
                        <span class="group-hover:text-forest/80 transition-colors">How can I raise a content flag?</span>
                        <span class="text-xl font-light text-forest/40 transition-transform duration-300" :class="activeFaq === 4 ? 'rotate-45 text-forest/80' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 4" x-cloak x-transition class="mt-4 text-xs md:text-sm text-forest/75 leading-relaxed font-light break-words">
                        If a reviewer spots factual errors, typos, or outdated curriculum in a book, they can raise an active "Flag". Admins then work with reviewers to track, comment, and resolve the issues.
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         CURVED FOOTER (Original content with refined compact styles)
         ═══════════════════════════════════════════ --}}
    <footer class="bg-forest text-cream pt-20 pb-10 px-6 lg:px-16 relative mt-16">
        <!-- Curved top shape for the footer -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-[0] transform rotate-180 -translate-y-[99%]">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-[calc(130%+1.3px)] h-[50px] block lg:h-[70px]">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="var(--color-cream)"></path>
            </svg>
        </div>

        <div class="max-w-4xl mx-auto space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-10">
                
                {{-- Links --}}
                <div class="md:col-span-3 flex flex-col gap-2">
                    <h4 class="font-heading font-extrabold text-base mb-1.5">Bookly</h4>
                    <a href="#" class="opacity-80 hover:opacity-100 transition text-[11px]">About Us</a>
                    <a href="#" class="opacity-80 hover:opacity-100 transition text-[11px]">Programs</a>
                    <a href="#" class="opacity-80 hover:opacity-100 transition text-[11px]">Events</a>
                    <a href="#" class="opacity-80 hover:opacity-100 transition text-[11px]">Blog</a>
                    <a href="#" class="opacity-80 hover:opacity-100 transition text-[11px]">Join Our Team</a>
                </div>

                {{-- Newsletter (Curated Input boxes) --}}
                <div class="md:col-span-6 flex flex-col items-center text-center border-y md:border-y-0 md:border-x border-white/10 py-6 md:py-0 md:px-8">
                    <h3 class="font-heading font-bold text-lg mb-1">Get Updates</h3>
                    <p class="opacity-70 text-[11px] mb-4 max-w-xs italic font-light">
                        Subscribe to our newsletter to receive updates and special announcements.
                    </p>
                    <form class="w-full max-w-xs flex flex-col gap-2.5">
                        <input type="email" placeholder="*Email" class="form-input-invert py-1.5 px-3 text-xs" required />
                        <div class="flex gap-2.5">
                            <input type="text" placeholder="*First Name" class="form-input-invert py-1.5 px-3 text-xs" required />
                            <button type="submit" class="bg-cream text-forest font-bold px-4 py-1.5 rounded hover:bg-white transition uppercase text-[10px] tracking-wider">Sign Up</button>
                        </div>
                    </form>
                </div>

                {{-- Contact --}}
                <div class="md:col-span-3 flex flex-col md:items-end md:text-right gap-3">
                    <a href="#" class="font-bold text-xs hover:opacity-80 transition inline-flex items-center gap-1">
                        <span>Send Us A Message</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                    <div class="opacity-70 text-[11px] mt-2">
                        <p class="font-semibold text-cream">(845)-356-1234</p>
                        <p class="mt-2 leading-relaxed">285 Hungry Hallow Road<br/>Chestnut Ridge, NY 10977</p>
                    </div>
                </div>
                
            </div>
            
            {{-- Bottom Bar --}}
            <div class="flex flex-col md:flex-row items-center justify-between pt-6 border-t border-white/10 opacity-50 text-[10px]">
                <p>&copy; {{ date('Y') }} Bookly. All rights reserved.</p>
                <div class="flex gap-4 mt-3 md:mt-0">
                    <a href="#" class="hover:opacity-100 transition">Privacy Policy</a>
                    <a href="#" class="hover:opacity-100 transition">Website by Bookly Studios</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</body>
</html>
