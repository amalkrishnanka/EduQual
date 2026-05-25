<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    /**
     * Display top-rated resources and resources needing assessment.
     */
    public function index(): View
    {
        // Top rated: resources with avg overall_score >= 7, grouped by subject
        $topRated = Resource::select('resources.*')
            ->join('assessments', 'resources.id', '=', 'assessments.resource_id')
            ->where('assessments.status', 'submitted')
            ->groupBy('resources.id')
            ->havingRaw('AVG(assessments.overall_score) >= 7')
            ->orderByRaw('AVG(assessments.overall_score) DESC')
            ->limit(10)
            ->get()
            ->each(function ($resource) {
                // Eager-load average score for display
                $resource->computed_avg_score = Assessment::where('resource_id', $resource->id)
                    ->submitted()
                    ->avg('overall_score');
            })
            ->groupBy('subject');

        // Resources with 0 submitted assessments
        $needsAssessment = Resource::whereDoesntHave('assessments', function ($query) {
            $query->where('status', 'submitted');
        })->latest()->get();

        return view('recommendations.index', [
            'topRated' => $topRated,
            'needsAssessment' => $needsAssessment,
        ]);
    }
}
