<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ResourceController extends Controller
{
    /**
     * Display a paginated listing of resources with search and filters.
     */
    public function index(Request $request): View
    {
        $query = Resource::query();

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        if ($type = $request->input('type')) {
            $query->ofType($type);
        }

        if ($subject = $request->input('subject')) {
            $query->ofSubject($subject);
        }

        if ($gradeLevel = $request->input('grade_level')) {
            $query->where('grade_level', $gradeLevel);
        }

        $resources = $query->latest()->paginate(15)->withQueryString();

        return view('resources.index', [
            'resources' => $resources,
            'types' => Resource::TYPES,
            'subjects' => Resource::SUBJECTS,
            'gradeLevels' => Resource::GRADE_LEVELS,
            'filters' => $request->only(['search', 'type', 'subject', 'grade_level']),
        ]);
    }

    /**
     * Display the specified resource with its assessments and flags.
     */
    public function show(Resource $resource): View
    {
        $resource->load([
            'assessments.reviewer',
            'flags',
        ]);

        return view('resources.show', compact('resource'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Resource::class);

        return view('resources.create', [
            'types' => Resource::TYPES,
            'subjects' => Resource::SUBJECTS,
            'gradeLevels' => Resource::GRADE_LEVELS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Resource::class);

        $validated = $request->validate($this->validationRules());

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $validated['created_by'] = Auth::id();

        $resource = Resource::create($validated);

        return redirect()
            ->route('resources.show', $resource)
            ->with('success', 'Resource created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource): View
    {
        $this->authorize('update', $resource);

        return view('resources.edit', [
            'resource' => $resource,
            'types' => Resource::TYPES,
            'subjects' => Resource::SUBJECTS,
            'gradeLevels' => Resource::GRADE_LEVELS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource): RedirectResponse
    {
        $this->authorize('update', $resource);

        $validated = $request->validate($this->validationRules($resource->id));

        if ($request->hasFile('cover_image')) {
            // Delete old cover image if it exists
            if ($resource->cover_image) {
                Storage::disk('public')->delete($resource->cover_image);
            }

            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $resource->update($validated);

        return redirect()
            ->route('resources.show', $resource)
            ->with('success', 'Resource updated successfully.');
    }

    /**
     * Soft-delete the specified resource.
     */
    public function destroy(Resource $resource): RedirectResponse
    {
        $this->authorize('delete', $resource);

        $resource->delete();

        return redirect()
            ->route('resources.index')
            ->with('success', 'Resource deleted successfully.');
    }

    /**
     * Get validation rules for store/update operations.
     *
     * @return array<string, mixed>
     */
    private function validationRules(?int $resourceId = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('resources', 'isbn')->ignore($resourceId),
            ],
            'type' => ['required', Rule::in(array_keys(Resource::TYPES))],
            'subject' => ['required', 'string', 'max:100'],
            'grade_level' => ['required', 'string', 'max:50'],
            'language' => ['sometimes', 'string', 'max:50'],
            'edition' => ['nullable', 'string', 'max:50'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
