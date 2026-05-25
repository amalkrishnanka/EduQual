@extends('layouts.app')
@section('header', 'User Management')
@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold">Users</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add User</a>
    </div>
    <div class="glass rounded-xl overflow-hidden">
        <div class="table-container">
            <table class="data-table"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Assessments</th><th>Actions</th></tr></thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td class="font-medium">{{ $user->name }}</td>
                <td class="text-slate-400">{{ $user->email }}</td>
                <td><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $user->role == 'super_admin' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : ($user->role == 'reviewer' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'bg-slate-500/20 text-slate-300 border border-slate-500/30') }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span></td>
                <td><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-500/20 text-slate-400 border border-slate-500/30' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td class="text-slate-400">{{ $user->assessments->count() }}</td>
                <td class="flex items-center gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-400 hover:text-indigo-300 text-sm">Edit</a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">@csrf<button type="submit" class="text-xs {{ $user->is_active ? 'text-amber-400 hover:text-amber-300' : 'text-emerald-400 hover:text-emerald-300' }}">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button></form>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody></table>
        </div>
    </div>
    @if($users->hasPages())<div class="pagination-wrapper">{{ $users->links() }}</div>@endif
</div>
@endsection
