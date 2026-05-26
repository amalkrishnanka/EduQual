<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentScore;
use App\Models\Criterion;
use App\Models\Resource;
use App\Notifications\AssessmentSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    /**
     * Display a paginated listing of assessments with filters.
     */
    public function index(Request $request): View
    {
        $query = Assessment::with('resource', 'reviewer');

        if ($resourceId = $request->input('resource_id')) {
            $query->where('resource_id', $resourceId);
        }

        if ($reviewerId = $request->input('reviewer_id')) {
            $query->where('reviewer_id', $reviewerId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $assessments = $query->latest()->paginate(15)->withQueryString();

        return view('assessments.index', [
            'assessments' => $assessments,
            'filters' => $request->only(['resource_id', 'reviewer_id', 'status']),
        ]);
    }

    /**
     * Display the specified assessment with scores, resource, and reviewer.
     */
    public function show(Assessment $assessment): View
    {
        $assessment->load(['scores.criterion', 'resource', 'reviewer']);

        return view('assessments.show', compact('assessment'));
    }

    /**
     * Show the form for creating a new assessment for a given resource.
     */
    public function create(Resource $resource): View|RedirectResponse
    {
        $this->authorize('create', Assessment::class);

        // Check if this reviewer already has a draft for this resource
        $existingDraft = Assessment::where('resource_id', $resource->id)
            ->where('reviewer_id', Auth::id())
            ->where('status', 'draft')
            ->first();

        if ($existingDraft) {
            return redirect()
                ->route('assessments.edit', $existingDraft)
                ->with('info', 'You already have a draft assessment for this resource.');
        }

        $criteria = Criterion::active()->orderBy('sort_order')->get();

        return view('assessments.create', [
            'resource' => $resource,
            'criteria' => $criteria,
            'assessment' => null,
        ]);
    }

    /**
     * Store a newly created assessment with scores.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Assessment::class);

        $activeCriteria = Criterion::active()->pluck('id')->toArray();

        $rules = [
            'resource_id' => ['required', 'exists:resources,id'],
        ];

        foreach ($activeCriteria as $criterionId) {
            $rules["scores.{$criterionId}.score"] = ['required', 'integer', 'min:1', 'max:10'];
            $rules["scores.{$criterionId}.justification"] = ['required', 'string', 'max:2000'];
        }

        $validated = $request->validate($rules);

        $assessment = DB::transaction(function () use ($request, $validated, $activeCriteria) {
            $assessment = Assessment::create([
                'resource_id' => $validated['resource_id'],
                'reviewer_id' => Auth::id(),
                'status' => 'draft',
                'overall_score' => 0,
            ]);

            foreach ($activeCriteria as $criterionId) {
                AssessmentScore::create([
                    'assessment_id' => $assessment->id,
                    'criterion_id' => $criterionId,
                    'score' => $validated['scores'][$criterionId]['score'],
                    'justification' => $validated['scores'][$criterionId]['justification'],
                ]);
            }

            $assessment->computeOverallScore();
            $assessment->save();

            // If the submit flag is present, mark as submitted
            if ($request->input('action') === 'submit') {
                $assessment->update([
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);

                $this->notifyAssessmentSubmitted($assessment);
            }

            return $assessment;
        });

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('success', 'Assessment created successfully.');
    }

    /**
     * Show the form for editing an assessment.
     */
    public function edit(Assessment $assessment): View
    {
        $this->authorize('update', $assessment);

        $assessment->load('scores.criterion');
        $criteria = Criterion::active()->orderBy('sort_order')->get();
        $resource = $assessment->resource;

        // Key existing scores by criterion_id for pre-populating the edit form
        $existingScores = $assessment->scores->keyBy('criterion_id');

        return view('assessments.create', [
            'resource' => $resource,
            'criteria' => $criteria,
            'assessment' => $assessment,
            'existingScores' => $existingScores,
        ]);
    }

    /**
     * Update the specified assessment and its scores.
     */
    public function update(Request $request, Assessment $assessment): RedirectResponse
    {
        $this->authorize('update', $assessment);

        $activeCriteria = Criterion::active()->pluck('id')->toArray();

        $rules = [];
        foreach ($activeCriteria as $criterionId) {
            $rules["scores.{$criterionId}.score"] = ['required', 'integer', 'min:1', 'max:10'];
            $rules["scores.{$criterionId}.justification"] = ['required', 'string', 'max:2000'];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $assessment, $validated, $activeCriteria) {
            foreach ($activeCriteria as $criterionId) {
                AssessmentScore::updateOrCreate(
                    [
                        'assessment_id' => $assessment->id,
                        'criterion_id' => $criterionId,
                    ],
                    [
                        'score' => $validated['scores'][$criterionId]['score'],
                        'justification' => $validated['scores'][$criterionId]['justification'],
                    ]
                );
            }

            $assessment->computeOverallScore();
            $assessment->save();

            if ($request->input('action') === 'submit') {
                $assessment->update([
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);

                $this->notifyAssessmentSubmitted($assessment);
            }
        });

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('success', 'Assessment updated successfully.');
    }

    /**
     * Submit a draft assessment.
     */
    public function submit(Assessment $assessment): RedirectResponse
    {
        $this->authorize('submit', $assessment);

        $assessment->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $this->notifyAssessmentSubmitted($assessment);

        return redirect()
            ->back()
            ->with('success', 'Assessment submitted successfully.');
    }

    /**
     * Unlock a submitted/locked assessment (admin only).
     */
    public function unlock(Assessment $assessment): RedirectResponse
    {
        $this->authorize('unlock', $assessment);

        $assessment->update([
            'status' => 'draft',
            'submitted_at' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Assessment unlocked and reverted to draft.');
    }

    /**
     * Send notification when an assessment is submitted.
     */
    private function notifyAssessmentSubmitted(Assessment $assessment): void
    {
        $assessment->load('resource', 'reviewer');

        // Notify all super admins about the submission
        $admins = \App\Models\User::where('role', 'super_admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AssessmentSubmitted($assessment));
        }
    }
}
