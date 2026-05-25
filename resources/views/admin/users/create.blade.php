@extends('layouts.app')
@section('header', isset($user) ? 'Edit User' : 'Add User')
@section('content')
<div class="max-w-xl mx-auto animate-fade-in">
    <div class="glass rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-6">{{ isset($user) ? 'Edit User' : 'Create New User' }}</h2>
        <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-5">
            @csrf
            @if(isset($user)) @method('PUT') @endif
            <div><label class="block text-sm text-slate-300 mb-1">Name *</label><input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-input" required>@error('name')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm text-slate-300 mb-1">Email *</label><input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-input" required>@error('email')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
            @if(!isset($user))
            <div><label class="block text-sm text-slate-300 mb-1">Password *</label><input type="password" name="password" class="form-input" required>@error('password')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm text-slate-300 mb-1">Confirm Password *</label><input type="password" name="password_confirmation" class="form-input" required></div>
            @endif
            <div><label class="block text-sm text-slate-300 mb-1">Role *</label><select name="role" class="form-input" required><option value="viewer" {{ old('role', $user->role ?? '') == 'viewer' ? 'selected' : '' }}>Viewer</option><option value="reviewer" {{ old('role', $user->role ?? '') == 'reviewer' ? 'selected' : '' }}>Reviewer</option><option value="super_admin" {{ old('role', $user->role ?? '') == 'super_admin' ? 'selected' : '' }}>Super Admin</option></select></div>
            @if(isset($user))
            <div class="flex items-center gap-3"><label class="text-sm text-slate-300">Active</label><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-slate-600 text-indigo-500 focus:ring-indigo-500"></div>
            @endif
            <div class="flex gap-3 pt-4 border-t border-slate-700/50">
                <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update User' : 'Create User' }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
