<?php

namespace App\Http\Controllers;

use App\Models\Flag;
use App\Models\FlagComment;
use App\Models\Resource;
use App\Notifications\FlagStatusChanged;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FlagController extends Controller
{
    /**
     * Display a paginated listing of flags with optional status filter.
     */
    public function index(Request $request): View
    {
        $query = Flag::with('resource', 'raisedBy');

        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        $flags = $query->latest()->paginate(15)->withQueryString();

        return view('flags.index', [
            'flags' => $flags,
            'statuses' => Flag::STATUSES,
            'filters' => $request->only('status'),
        ]);
    }

    /**
     * Display the specified flag with all related data.
     */
    public function show(Flag $flag): View
    {
        $flag->load([
            'resource',
            'raisedBy',
            'resolvedBy',
            'comments.user',
        ]);

        return view('flags.show', compact('flag'));
    }

    /**
     * Show the form for creating a new flag for a given resource.
     */
    public function create(Resource $resource): View
    {
        $this->authorize('create', Flag::class);

        return view('flags.create', [
            'resource' => $resource,
            'categories' => Flag::CATEGORIES,
        ]);
    }

    /**
     * Store a newly created flag.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Flag::class);

        $validated = $request->validate([
            'resource_id' => ['required', 'exists:resources,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category' => ['required', Rule::in(array_keys(Flag::CATEGORIES))],
        ]);

        $flag = Flag::create([
            ...$validated,
            'raised_by' => Auth::id(),
            'status' => 'open',
        ]);

        return redirect()
            ->route('flags.show', $flag)
            ->with('success', 'Flag raised successfully.');
    }

    /**
     * Update the status of a flag (admin only).
     */
    public function updateStatus(Request $request, Flag $flag): RedirectResponse
    {
        $this->authorize('updateStatus', $flag);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Flag::STATUSES))],
            'resolution_notes' => ['nullable', 'required_if:status,resolved,dismissed', 'string', 'max:5000'],
        ]);

        $oldStatus = $flag->status;
        $updateData = [
            'status' => $validated['status'],
            'resolution_notes' => $validated['resolution_notes'] ?? $flag->resolution_notes,
        ];

        // If resolving or dismissing, set resolver info
        if (in_array($validated['status'], ['resolved', 'dismissed'])) {
            $updateData['resolved_by'] = Auth::id();
            $updateData['resolved_at'] = now();
        }

        $flag->update($updateData);

        // Notify the user who raised the flag
        $flag->raisedBy->notify(new FlagStatusChanged($flag, $oldStatus, $validated['status']));

        return redirect()
            ->back()
            ->with('success', 'Flag status updated successfully.');
    }

    /**
     * Add a comment to a flag.
     */
    public function addComment(Request $request, Flag $flag): RedirectResponse
    {
        $this->authorize('addComment', $flag);

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        FlagComment::create([
            'flag_id' => $flag->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Comment added successfully.');
    }
}
