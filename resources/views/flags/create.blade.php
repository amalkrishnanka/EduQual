@extends('layouts.app')
@section('header', 'Flag Content')
@section('content')
<div class="max-w-2xl mx-auto animate-fade-in">
    <div class="glass rounded-xl p-5 mb-6">
        <p class="text-xs text-slate-400">Flagging</p>
        <p class="font-semibold mt-0.5">{{ $resource->title }}</p>
        <p class="text-sm text-slate-400">{{ $resource->author }}</p>
    </div>
    <div class="glass rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-4">Raise a Content Flag</h2>
        <form method="POST" action="{{ route('flags.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="resource_id" value="{{ $resource->id }}">
            <div><label class="block text-sm text-slate-300 mb-1">Title *</label><input type="text" name="title" value="{{ old('title') }}" class="form-input" required placeholder="Brief summary of the issue">@error('title')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm text-slate-300 mb-1">Category *</label><select name="category" class="form-input" required><option value="">Select category</option><option value="inaccurate">Inaccurate Content</option><option value="outdated">Outdated Information</option><option value="inappropriate">Inappropriate Material</option><option value="other">Other</option></select>@error('category')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm text-slate-300 mb-1">Description *</label><textarea name="description" rows="5" class="form-input" required placeholder="Describe the issue in detail...">{{ old('description') }}</textarea>@error('description')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div class="flex gap-3"><button type="submit" class="btn btn-primary">Submit Flag</button><a href="{{ route('resources.show', $resource) }}" class="btn btn-secondary">Cancel</a></div>
        </form>
    </div>
</div>
@endsection
