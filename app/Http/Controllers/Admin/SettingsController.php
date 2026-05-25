<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings page with all criteria ordered by sort_order.
     */
    public function index(): View
    {
        $criteria = Criterion::orderBy('sort_order')->get();

        return view('admin.settings', compact('criteria'));
    }

    /**
     * Update criterion weights.
     *
     * Validates that weights is an array of numeric values between 0 and 1,
     * and that all weights sum to exactly 1 (with a small tolerance for floats).
     */
    public function updateWeights(Request $request): RedirectResponse
    {
        $request->validate([
            'weights' => ['required', 'array'],
            'weights.*' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $weights = $request->input('weights');
        $total = array_sum($weights);

        // Allow small floating-point tolerance
        if (abs($total - 1.0) > 0.001) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['weights' => 'The criterion weights must sum to exactly 1. Current sum: ' . round($total, 4)]);
        }

        foreach ($weights as $criterionId => $weight) {
            Criterion::where('id', $criterionId)->update(['weight' => $weight]);
        }

        return redirect()
            ->back()
            ->with('success', 'Criterion weights updated successfully.');
    }
}
