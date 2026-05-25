@extends('layouts.app')
@section('header', isset($resource) ? 'Edit Resource' : 'Add Resource')
@section('content')
<div class="max-w-3xl mx-auto animate-fade-in">
    <div class="glass rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-6">{{ isset($resource) ? 'Edit Resource' : 'Add New Resource' }}</h2>
        <form method="POST" action="{{ isset($resource) ? route('resources.update', $resource) : route('resources.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if(isset($resource)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-1">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $resource->title ?? '') }}" class="form-input" required>
                    @error('title')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Author *</label>
                    <input type="text" name="author" value="{{ old('author', $resource->author ?? '') }}" class="form-input" required>
                    @error('author')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Publisher</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $resource->publisher ?? '') }}" class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $resource->isbn ?? '') }}" class="form-input">
                    @error('isbn')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Type *</label>
                    <select name="type" class="form-input" required>
                        <option value="">Select type</option>
                        @foreach($types as $key => $label)<option value="{{ $key }}" {{ old('type', $resource->type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>@endforeach
                    </select>
                    @error('type')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Subject *</label>
                    <select name="subject" class="form-input" required>
                        <option value="">Select subject</option>
                        @foreach($subjects as $s)<option value="{{ $s }}" {{ old('subject', $resource->subject ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach
                    </select>
                    @error('subject')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Grade Level *</label>
                    <select name="grade_level" class="form-input" required>
                        <option value="">Select grade</option>
                        @foreach($gradeLevels as $g)<option value="{{ $g }}" {{ old('grade_level', $resource->grade_level ?? '') == $g ? 'selected' : '' }}>{{ $g }}</option>@endforeach
                    </select>
                    @error('grade_level')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Language</label>
                    <input type="text" name="language" value="{{ old('language', $resource->language ?? 'English') }}" class="form-input">
                    @error('language')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Edition</label>
                    <input type="text" name="edition" value="{{ old('edition', $resource->edition ?? '') }}" class="form-input">
                    @error('edition')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Cover Image</label>
                    <input type="file" name="cover_image" accept="image/*" class="form-input">
                    @error('cover_image')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-1">Description</label>
                    <textarea name="description" rows="4" class="form-input">{{ old('description', $resource->description ?? '') }}</textarea>
                    @error('description')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-700/50">
                <button type="submit" class="btn btn-primary">{{ isset($resource) ? 'Update Resource' : 'Create Resource' }}</button>
                <a href="{{ route('resources.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
