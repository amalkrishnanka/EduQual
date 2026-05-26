@extends('layouts.app')
@section('header', isset($resource) ? 'Edit Resource' : 'Add Resource')
@section('content')
<div class="max-w-3xl mx-auto animate-fade-in text-forest">
    <div class="glass-card p-6 bg-[#fcf9f2] border-forest/15 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-forest/10">
            <h2 class="text-base font-extrabold font-heading tracking-tight flex items-center gap-2">
                <i data-lucide="{{ isset($resource) ? 'edit-3' : 'plus-circle' }}" class="w-5 h-5 text-forest"></i>
                <span>{{ isset($resource) ? 'Edit Resource Details' : 'Add New Book Resource' }}</span>
            </h2>
            <a href="{{ route('resources.index') }}" class="text-[10px] font-bold uppercase tracking-wider text-forest/50 hover:text-forest transition">
                &larr; Back to Catalogue
            </a>
        </div>

        <form method="POST" action="{{ isset($resource) ? route('resources.update', $resource) : route('resources.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($resource)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Smart Autofill from Google Books API (Only visible when creating new) --}}
                @if(!isset($resource))
                <div class="md:col-span-2 bg-forest/5 p-4 rounded-xl border border-forest/10">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5 flex items-center gap-1.5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-forest animate-pulse"></i>
                        <span>Autofill from Google Books</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="book-search" placeholder="Search by book title, author, or ISBN..." 
                               class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 pr-10 shadow-inner-sm">
                        <div id="search-loading" class="hidden absolute right-3.5 top-3">
                            <svg class="animate-spin h-3.5 w-3.5 text-forest" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Search Dropdown suggestions list --}}
                    <div id="search-suggestions" class="hidden mt-2 bg-white border border-forest/15 rounded-xl overflow-hidden max-h-64 overflow-y-auto shadow-[0_12px_32px_rgba(26,59,43,0.1)] z-50 divide-y divide-forest/5">
                        <!-- Dynamic options will be rendered here -->
                    </div>
                    <p class="text-[9px] text-forest/45 font-medium mt-1.5 leading-normal">
                        Type to query real books from Google. Selecting a result automatically fetches full title, authors, publisher, ISBN, and HD cover art.
                    </p>
                </div>
                @endif

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Book Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $resource->title ?? '') }}" 
                           class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm" required>
                    @error('title')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>

                {{-- Author --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Author *</label>
                    <input type="text" name="author" id="author" value="{{ old('author', $resource->author ?? '') }}" 
                           class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm" required>
                    @error('author')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>

                {{-- Publisher --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Publisher</label>
                    <input type="text" name="publisher" id="publisher" value="{{ old('publisher', $resource->publisher ?? '') }}" 
                           class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm">
                </div>

                {{-- ISBN --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">ISBN</label>
                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $resource->isbn ?? '') }}" 
                           class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm">
                    @error('isbn')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Resource Type *</label>
                    <select name="type" id="type" class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm" required>
                        <option value="">Select type</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $resource->type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>

                {{-- Subject --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Subject Field *</label>
                    <select name="subject" id="subject" class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm" required>
                        <option value="">Select subject</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s }}" {{ old('subject', $resource->subject ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('subject')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>

                {{-- Grade Level --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Grade Level *</label>
                    <select name="grade_level" id="grade_level" class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm" required>
                        <option value="">Select grade</option>
                        @foreach($gradeLevels as $g)
                            <option value="{{ $g }}" {{ old('grade_level', $resource->grade_level ?? '') == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                    @error('grade_level')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>

                {{-- Language --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Language</label>
                    <input type="text" name="language" id="language" value="{{ old('language', $resource->language ?? 'English') }}" 
                           class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm">
                    @error('language')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>

                {{-- Edition --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Edition</label>
                    <input type="text" name="edition" id="edition" value="{{ old('edition', $resource->edition ?? '') }}" 
                           class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm">
                    @error('edition')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>

                {{-- Cover Image Layout with Dynamic Preview Card --}}
                <div class="md:col-span-2">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 bg-forest/5 p-4 rounded-xl border border-forest/10">
                        
                        <div class="w-16 h-20 rounded-lg bg-white border border-forest/15 overflow-hidden flex items-center justify-center shrink-0 shadow-sm relative group">
                            <img id="cover-preview" src="{{ old('cover_image_url', $resource->cover_image_url ?? '') }}" 
                                 class="{{ old('cover_image_url', $resource->cover_image_url ?? '') ? '' : 'hidden' }} w-full h-full object-cover">
                            <div id="cover-placeholder" class="{{ old('cover_image_url', $resource->cover_image_url ?? '') ? 'hidden' : '' }} text-center flex flex-col items-center justify-center">
                                <i data-lucide="image" class="w-5 h-5 text-forest/35"></i>
                                <span class="text-[8px] font-bold uppercase tracking-wider text-forest/35 mt-1">Cover</span>
                            </div>
                        </div>

                        <div class="flex-grow">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Cover Image (Upload file or autofill via Search)</label>
                            <input type="file" name="cover_image" id="cover-file-input" accept="image/*" 
                                   class="w-full px-3.5 py-2 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300">
                            
                            {{-- Stores the autofilled external URL string --}}
                            <input type="hidden" name="cover_image_url" id="cover_image_url" value="{{ old('cover_image_url', $resource->cover_image ?? '') }}">
                            @error('cover_image')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-forest/75 mb-1.5">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-3.5 py-2.5 bg-white border border-forest/15 rounded-xl text-xs font-medium text-forest focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest focus:bg-white transition-all duration-300 shadow-inner-sm">{{ old('description', $resource->description ?? '') }}</textarea>
                    @error('description')<p class="text-rose-600 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-6 border-t border-forest/10">
                <button type="submit" class="bg-forest text-cream font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-xl hover:bg-forest/95 hover:-translate-y-0.5 active:translate-y-0 active:scale-98 transition-all duration-300 shadow-md">
                    {{ isset($resource) ? 'Update Resource' : 'Create Resource' }}
                </button>
                <a href="{{ route('resources.index') }}" class="border border-forest/20 text-forest/70 font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-xl hover:bg-forest/5 transition-all duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Google Books API Client-Side JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('book-search');
    if (!searchInput) return;

    const suggestionsBox = document.getElementById('search-suggestions');
    const loadingSpinner = document.getElementById('search-loading');
    
    // Inputs to autofill
    const titleInput = document.getElementById('title');
    const authorInput = document.getElementById('author');
    const publisherInput = document.getElementById('publisher');
    const isbnInput = document.getElementById('isbn');
    const langInput = document.getElementById('language');
    const descInput = document.getElementById('description');
    
    const coverUrlInput = document.getElementById('cover_image_url');
    const coverPreview = document.getElementById('cover-preview');
    const coverPlaceholder = document.getElementById('cover-placeholder');
    const fileInput = document.getElementById('cover-file-input');

    let debounceTimeout = null;

    // Handle search input events
    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();
        
        clearTimeout(debounceTimeout);
        if (query.length < 3) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.classList.add('hidden');
            return;
        }

        loadingSpinner.classList.remove('hidden');

        // Debounce external fetch calls to 350ms
        debounceTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=6`);
                
                // If Google Books API throws an error (e.g. 429 Quota Exceeded), fall back immediately to Open Library!
                if (!response.ok) {
                    throw new Error('Google Books status not OK');
                }
                
                const data = await response.json();
                
                if (data.error) {
                    throw new Error(data.error.message || 'Google Books error response');
                }
                
                loadingSpinner.classList.add('hidden');
                
                if (data.items && data.items.length > 0) {
                    renderGoogleSuggestions(data.items);
                } else {
                    // Failover to Open Library
                    fetchFromOpenLibrary(query);
                }
            } catch (err) {
                console.warn('Google Books API failed or quota limited. Falling back to Open Library:', err);
                fetchFromOpenLibrary(query);
            }
        }, 350);
    });

    // Fallback lookup using Open Library Search API (Free, high limits, no key required)
    async function fetchFromOpenLibrary(query) {
        try {
            const response = await fetch(`https://openlibrary.org/search.json?q=${encodeURIComponent(query)}&limit=6`);
            if (!response.ok) {
                throw new Error('Open Library search status not OK');
            }
            const data = await response.json();
            
            loadingSpinner.classList.add('hidden');
            
            if (data.docs && data.docs.length > 0) {
                renderOpenLibrarySuggestions(data.docs);
            } else {
                suggestionsBox.innerHTML = `<div class="p-4 text-xs font-semibold text-forest/40 text-center">No matching books found</div>`;
                suggestionsBox.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Open Library Fetch Error:', err);
            loadingSpinner.classList.add('hidden');
            suggestionsBox.innerHTML = `<div class="p-4 text-xs font-semibold text-rose-500/60 text-center">Search service currently offline</div>`;
            suggestionsBox.classList.remove('hidden');
        }
    }

    // Render suggestions from Google Books API
    function renderGoogleSuggestions(books) {
        suggestionsBox.innerHTML = '';
        suggestionsBox.classList.remove('hidden');

        books.forEach(book => {
            const info = book.volumeInfo;
            const title = info.title || 'Unknown Title';
            const authors = info.authors ? info.authors.join(', ') : 'Unknown Author';
            const thumbnail = info.imageLinks ? info.imageLinks.thumbnail : null;
            const publisher = info.publisher || '';
            const desc = info.description || '';
            const lang = info.language === 'en' ? 'English' : (info.language || 'English');
            
            // Get ISBN
            let isbn = '';
            if (info.industryIdentifiers) {
                const isbn13 = info.industryIdentifiers.find(id => id.type === 'ISBN_13');
                const isbn10 = info.industryIdentifiers.find(id => id.type === 'ISBN_10');
                isbn = isbn13 ? isbn13.identifier : (isbn10 ? isbn10.identifier : '');
            }

            // Create suggestion option item
            const item = document.createElement('div');
            item.className = 'flex items-center gap-3 p-3 hover:bg-forest/5 cursor-pointer transition-colors duration-200';
            
            item.innerHTML = `
                <div class="w-9 h-12 bg-forest/5 rounded overflow-hidden shrink-0 border border-forest/10">
                    ${thumbnail ? `<img src="${thumbnail}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center"><i data-lucide="book" class="w-4 h-4 text-forest/30"></i></div>`}
                </div>
                <div class="min-w-0 flex-grow">
                    <p class="text-xs font-extrabold text-forest truncate leading-tight">${title}</p>
                    <p class="text-[10px] text-forest/50 font-medium truncate mt-0.5">${authors}</p>
                </div>
            `;

            // Click listener to autofill all fields!
            item.addEventListener('click', function () {
                titleInput.value = title;
                authorInput.value = authors;
                publisherInput.value = publisher;
                isbnInput.value = isbn;
                langInput.value = lang.charAt(0).toUpperCase() + lang.slice(1);
                descInput.value = desc;

                // Handle Cover Image URL cache
                if (thumbnail) {
                    const secureUrl = thumbnail.replace('http://', 'https://');
                    coverUrlInput.value = secureUrl;
                    coverPreview.src = secureUrl;
                    coverPreview.classList.remove('hidden');
                    coverPlaceholder.classList.add('hidden');
                } else {
                    coverUrlInput.value = '';
                    coverPreview.src = '';
                    coverPreview.classList.add('hidden');
                    coverPlaceholder.classList.remove('hidden');
                }

                // Reset suggestions dropdown
                suggestionsBox.innerHTML = '';
                suggestionsBox.classList.add('hidden');
                searchInput.value = '';
            });

            suggestionsBox.appendChild(item);
        });
    }

    // Render suggestions from Open Library API
    function renderOpenLibrarySuggestions(docs) {
        suggestionsBox.innerHTML = '';
        suggestionsBox.classList.remove('hidden');

        docs.forEach(book => {
            const title = book.title || 'Unknown Title';
            const authors = book.author_name ? book.author_name.join(', ') : 'Unknown Author';
            const thumbnail = book.cover_i ? `https://covers.openlibrary.org/b/id/${book.cover_i}-M.jpg` : null;
            const publisher = book.publisher ? book.publisher[0] : '';
            const desc = book.first_publish_year ? `First published in ${book.first_publish_year}.` : '';
            
            let lang = 'English';
            if (book.language && book.language.length > 0) {
                const l = book.language[0];
                lang = l === 'eng' ? 'English' : (l === 'spa' ? 'Spanish' : (l === 'fre' ? 'French' : l));
            }

            const isbn = book.isbn ? book.isbn[0] : '';

            // Create suggestion option item
            const item = document.createElement('div');
            item.className = 'flex items-center gap-3 p-3 hover:bg-forest/5 cursor-pointer transition-colors duration-200';
            
            item.innerHTML = `
                <div class="w-9 h-12 bg-forest/5 rounded overflow-hidden shrink-0 border border-forest/10">
                    ${thumbnail ? `<img src="${thumbnail}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center"><i data-lucide="book" class="w-4 h-4 text-forest/30"></i></div>`}
                </div>
                <div class="min-w-0 flex-grow">
                    <p class="text-xs font-extrabold text-forest truncate leading-tight">${title}</p>
                    <p class="text-[10px] text-forest/50 font-medium truncate mt-0.5">${authors}</p>
                </div>
            `;

            // Click listener to autofill all fields!
            item.addEventListener('click', function () {
                titleInput.value = title;
                authorInput.value = authors;
                publisherInput.value = publisher;
                isbnInput.value = isbn;
                langInput.value = lang.charAt(0).toUpperCase() + lang.slice(1);
                descInput.value = desc;

                // Handle Cover Image URL cache
                if (thumbnail) {
                    const largeCover = `https://covers.openlibrary.org/b/id/${book.cover_i}-L.jpg`;
                    coverUrlInput.value = largeCover;
                    coverPreview.src = largeCover;
                    coverPreview.classList.remove('hidden');
                    coverPlaceholder.classList.add('hidden');
                } else {
                    coverUrlInput.value = '';
                    coverPreview.src = '';
                    coverPreview.classList.add('hidden');
                    coverPlaceholder.classList.remove('hidden');
                }

                // Reset suggestions dropdown
                suggestionsBox.innerHTML = '';
                suggestionsBox.classList.add('hidden');
                searchInput.value = '';
            });

            suggestionsBox.appendChild(item);
        });
    }

    // Close suggestions box if clicked outside
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.classList.add('hidden');
        }
    });

    // File input changes overrides URL preview
    fileInput.addEventListener('change', function () {
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                coverPreview.src = e.target.result;
                coverPreview.classList.remove('hidden');
                coverPlaceholder.classList.add('hidden');
                coverUrlInput.value = ''; // Reset external URL since user uploaded a file
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    });
});
</script>
@endsection
