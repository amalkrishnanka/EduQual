<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Criterion;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard with summary stats.
     */
    public function index(): View
    {
        $totalResources = Resource::count();
        $totalAssessments = Assessment::submitted()->count();
        $totalReviewers = User::where('role', 'reviewer')->count();
        $averageScore = Assessment::submitted()->avg('overall_score');

        return view('analytics.index', compact('totalResources', 'totalAssessments', 'totalReviewers', 'averageScore'));
    }

    /**
     * Return average scores grouped by subject for Chart.js.
     */
    public function scoresBySubject(): JsonResponse
    {
        $data = Assessment::submitted()
            ->join('resources', 'assessments.resource_id', '=', 'resources.id')
            ->select('resources.subject')
            ->selectRaw('AVG(assessments.overall_score) as avg_score')
            ->selectRaw('COUNT(assessments.id) as assessment_count')
            ->groupBy('resources.subject')
            ->orderByDesc('avg_score')
            ->get();

        return response()->json([
            'labels' => $data->pluck('subject'),
            'datasets' => [
                [
                    'label' => 'Average Score',
                    'data' => $data->pluck('avg_score')->map(fn ($v) => round((float) $v, 2)),
                    'counts' => $data->pluck('assessment_count'),
                ],
            ],
        ]);
    }

    /**
     * Return score distribution for each active criterion.
     */
    public function scoreDistribution(): JsonResponse
    {
        $criteria = Criterion::active()->orderBy('sort_order')->get();
        $datasets = [];

        foreach ($criteria as $criterion) {
            $distribution = DB::table('assessment_scores')
                ->join('assessments', 'assessment_scores.assessment_id', '=', 'assessments.id')
                ->where('assessments.status', 'submitted')
                ->where('assessment_scores.criterion_id', $criterion->id)
                ->select('assessment_scores.score')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('assessment_scores.score')
                ->orderBy('assessment_scores.score')
                ->pluck('count', 'score');

            // Build a full 1-10 distribution array
            $counts = [];
            for ($i = 1; $i <= 10; $i++) {
                $counts[] = $distribution->get($i, 0);
            }

            $datasets[] = [
                'label' => $criterion->name,
                'data' => $counts,
            ];
        }

        return response()->json([
            'labels' => range(1, 10),
            'datasets' => $datasets,
        ]);
    }

    /**
     * Return reviewer activity (submitted assessments per reviewer per month) for the last 6 months.
     */
    public function reviewerActivity(): JsonResponse
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();

        $activity = Assessment::submitted()
            ->where('submitted_at', '>=', $sixMonthsAgo)
            ->join('users', 'assessments.reviewer_id', '=', 'users.id')
            ->select('users.name as reviewer_name')
            ->selectRaw("DATE_FORMAT(assessments.submitted_at, '%Y-%m') as month")
            ->selectRaw('COUNT(*) as count')
            ->groupBy('users.name', 'month')
            ->orderBy('month')
            ->get();

        // Build month labels for the last 6 months
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('Y-m');
        }

        // Group by reviewer
        $reviewers = $activity->pluck('reviewer_name')->unique()->values();
        $datasets = [];

        foreach ($reviewers as $reviewer) {
            $reviewerData = $activity->where('reviewer_name', $reviewer);
            $data = [];
            foreach ($months as $month) {
                $entry = $reviewerData->firstWhere('month', $month);
                $data[] = $entry ? $entry->count : 0;
            }

            $datasets[] = [
                'label' => $reviewer,
                'data' => $data,
            ];
        }

        return response()->json([
            'labels' => $months,
            'datasets' => $datasets,
        ]);
    }

    /**
     * Compare up to 4 resources with their submitted assessments and scores.
     */
    public function comparison(Request $request): View
    {
        if ($request->has('resources')) {
            $request->validate([
                'resources' => ['array', 'max:4'],
                'resources.*' => ['nullable', 'integer', 'exists:resources,id'],
            ]);

            // Filter out empty selections
            $resourceIds = array_filter($request->input('resources'));

            if (count($resourceIds) >= 2) {
                $resources = Resource::whereIn('id', $resourceIds)
                    ->with([
                        'assessments' => function ($query) {
                            $query->submitted()->with('scores.criterion');
                        },
                    ])
                    ->get();
            } else {
                $resources = [];
                if (count($resourceIds) > 0) {
                    session()->flash('error', 'Please select at least 2 resources to compare.');
                }
            }
        } else {
            $resources = [];
        }

        $allResources = Resource::orderBy('title')->get();
        $criteria = Criterion::active()->orderBy('sort_order')->get();

        return view('analytics.comparison', [
            'resources' => $resources,
            'allResources' => $allResources,
            'criteria' => $criteria,
        ]);
    }
}
