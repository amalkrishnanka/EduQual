@extends('layouts.app')

@section('title', 'Profile Settings')
@section('header')
<div class="flex items-center gap-2 text-sm text-forest/60">
    <i data-lucide="settings" class="w-4 h-4"></i>
    <span class="font-medium">Profile Settings</span>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in text-forest">
    
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- ═══════════════════════════════════════════
             2-COLUMN BENTO GRID FORM
             ═══════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            {{-- BENTO CARD 1: PERSONAL DETAILS --}}
            <div class="glass-card p-5 flex flex-col justify-between space-y-4 min-h-[300px]">
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-2 border-b border-forest/5">
                        <div class="w-7 h-7 rounded-lg bg-forest/5 flex items-center justify-center shrink-0">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-forest"></i>
                        </div>
                        <h3 class="text-xs font-bold font-heading uppercase tracking-wider">Account Credentials</h3>
                    </div>
                    <p class="text-[11px] text-forest/50 font-light mb-4">
                        Update your public profile name and system email address.
                    </p>

                    <div class="space-y-3">
                        <div>
                            <label for="name" class="block text-[9px] font-bold text-forest/50 uppercase tracking-widest mb-1.5">Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                   class="w-full px-3 py-1.5 bg-white/60 border border-forest/15 rounded-lg text-xs text-forest focus:outline-none focus:border-forest/50 focus:ring-1 focus:ring-forest/50 transition-all placeholder-forest/30" />
                            @error('name')
                                <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-[9px] font-bold text-forest/50 uppercase tracking-widest mb-1.5">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                   class="w-full px-3 py-1.5 bg-white/60 border border-forest/15 rounded-lg text-xs text-forest focus:outline-none focus:border-forest/50 focus:ring-1 focus:ring-forest/50 transition-all placeholder-forest/30" />
                            @error('email')
                                <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-forest/5 flex items-center justify-between text-[10px] text-forest/40">
                    <span>Role: <strong class="capitalize text-forest/60">{{ auth()->user()->role }}</strong></span>
                </div>
            </div>

            {{-- BENTO CARD 2: PASSWORD SETTINGS --}}
            <div class="glass-card p-5 flex flex-col justify-between space-y-4 min-h-[300px]">
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-2 border-b border-forest/5">
                        <div class="w-7 h-7 rounded-lg bg-forest/5 flex items-center justify-center shrink-0">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-forest"></i>
                        </div>
                        <h3 class="text-xs font-bold font-heading uppercase tracking-wider">Access Settings</h3>
                    </div>
                    <p class="text-[11px] text-forest/50 font-light mb-4">
                        Ensure your account uses a secure password. Leave blank to keep current.
                    </p>

                    <div class="space-y-3">
                        <div>
                            <label for="current_password" class="block text-[9px] font-bold text-forest/50 uppercase tracking-widest mb-1.5">Current Password</label>
                            <input id="current_password" type="password" name="current_password"
                                   class="w-full px-3 py-1.5 bg-white/60 border border-forest/15 rounded-lg text-xs text-forest focus:outline-none focus:border-forest/50 focus:ring-1 focus:ring-forest/50 transition-all" />
                            @error('current_password')
                                <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="password" class="block text-[9px] font-bold text-forest/50 uppercase tracking-widest mb-1.5">New Password</label>
                                <input id="password" type="password" name="password"
                                       class="w-full px-3 py-1.5 bg-white/60 border border-forest/15 rounded-lg text-xs text-forest focus:outline-none focus:border-forest/50 focus:ring-1 focus:ring-forest/50 transition-all" />
                                @error('password')
                                    <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-[9px] font-bold text-forest/50 uppercase tracking-widest mb-1.5">Confirm</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                       class="w-full px-3 py-1.5 bg-white/60 border border-forest/15 rounded-lg text-xs text-forest focus:outline-none focus:border-forest/50 focus:ring-1 focus:ring-forest/50 transition-all" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-forest/5 flex items-center justify-end">
                    <button type="submit" class="btn btn-primary text-xs py-1.5 px-4 flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
