<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Flag;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the role-aware dashboard.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Load unified system-wide dataset for a comprehensive dashboard workspace
        $data = array_merge(
            ['user' => $user],
            $this->superAdminData(),
            $this->reviewerData($user),
            $this->viewerData()
        );

        return view('dashboard.index', $data);
    }

    /**
     * Gather dashboard data for super_admin role.
     *
     * @return array<string, mixed>
     */
    private function superAdminData(): array
    {
        return [
            'totalUsers' => User::count(),
            'totalResources' => Resource::count(),
            'totalAssessments' => Assessment::count(),
            'totalFlags' => Flag::count(),
            'recentFlags' => Flag::with('resource', 'raisedBy')
                ->latest()
                ->limit(5)
                ->get(),
            'recentAssessments' => Assessment::with('resource', 'reviewer')
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Gather dashboard data for reviewer role.
     *
     * @return array<string, mixed>
     */
    private function reviewerData(User $user): array
    {
        $myAssessments = Assessment::where('reviewer_id', $user->id);

        return [
            'draftCount' => (clone $myAssessments)->where('status', 'draft')->count(),
            'submittedCount' => (clone $myAssessments)->where('status', 'submitted')->count(),
            'resourcesNeedingAssessment' => Resource::whereDoesntHave('assessments', function ($query) use ($user) {
                $query->where('reviewer_id', $user->id);
            })->latest()->limit(5)->get(),
            'myAssessments' => Assessment::with('resource')
                ->where('reviewer_id', $user->id)
                ->latest()
                ->paginate(10),
        ];
    }

    /**
     * Gather dashboard data for viewer role.
     *
     * @return array<string, mixed>
     */
    private function viewerData(): array
    {
        return [
            'topResources' => Resource::withAvg(['assessments as average_overall_score' => function($query) {
                    $query->where('status', 'submitted');
                }], 'overall_score')
                ->whereHas('assessments', function($query) {
                    $query->where('status', 'submitted');
                })
                ->orderByDesc('average_overall_score')
                ->limit(10)
                ->get(),
            'recentAssessments' => Assessment::with('resource', 'reviewer')
                ->submitted()
                ->latest('submitted_at')
                ->limit(10)
                ->get(),
            'subjectsOverview' => Resource::selectRaw('subject, COUNT(*) as resource_count')
                ->groupBy('subject')
                ->orderByDesc('resource_count')
                ->get(),
        ];
    }
}
