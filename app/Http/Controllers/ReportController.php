<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Criterion;
use App\Models\Report;
use App\Models\Resource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     * Admins see all; other users see only their own.
     */
    public function index(): View
    {
        $user = Auth::user();

        $query = Report::with('generatedBy')->latest();

        if (! $user->isSuperAdmin()) {
            $query->where('generated_by', $user->id);
        }

        $reports = $query->paginate(15);

        return view('reports.index', compact('reports'));
    }

    /**
     * Generate a PDF report for a specific resource or all resources.
     */
    public function generatePdf(Request $request): BinaryFileResponse|RedirectResponse
    {
        $request->validate([
            'resource_id' => ['nullable', 'exists:resources,id'],
        ]);

        $resourceId = $request->input('resource_id');

        if ($resourceId) {
            $resources = Resource::where('id', $resourceId)
                ->with([
                    'assessments' => function ($query) {
                        $query->submitted()->with('scores.criterion', 'reviewer');
                    },
                ])
                ->get();
            $title = 'Assessment Report - ' . $resources->first()->title;
        } else {
            $resources = Resource::with([
                'assessments' => function ($query) {
                    $query->submitted()->with('scores.criterion', 'reviewer');
                },
            ])->get();
            $title = 'Full Assessment Report - All Resources';
        }

        $criteria = Criterion::active()->orderBy('sort_order')->get();

        $pdf = Pdf::loadView('reports.pdf-template', [
            'resources' => $resources,
            'criteria' => $criteria,
            'title' => $title,
            'generatedAt' => now(),
        ]);

        // Save to storage
        $filename = 'reports/' . now()->format('Y-m-d_His') . '_assessment_report.pdf';
        Storage::disk('local')->put($filename, $pdf->output());

        // Create report record
        $report = Report::create([
            'type' => 'pdf',
            'title' => $title,
            'generated_by' => Auth::id(),
            'parameters' => json_encode(['resource_id' => $resourceId]),
            'file_path' => $filename,
        ]);

        return response()->download(
            Storage::disk('local')->path($filename),
            $report->title . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Generate a CSV export of assessment data.
     */
    public function generateCsv(Request $request): StreamedResponse
    {
        $request->validate([
            'resource_id' => ['nullable', 'exists:resources,id'],
            'subject' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $query = Assessment::submitted()
            ->with('resource', 'scores.criterion', 'reviewer');

        if ($resourceId = $request->input('resource_id')) {
            $query->where('resource_id', $resourceId);
        }

        if ($subject = $request->input('subject')) {
            $query->whereHas('resource', function ($q) use ($subject) {
                $q->where('subject', $subject);
            });
        }

        if ($type = $request->input('type')) {
            $query->whereHas('resource', function ($q) use ($type) {
                $q->where('type', $type);
            });
        }

        $assessments = $query->get();

        // Save a copy to storage for the report record
        $filename = 'reports/' . now()->format('Y-m-d_His') . '_assessment_export.csv';
        $csvContent = $this->buildCsvContent($assessments);
        Storage::disk('local')->put($filename, $csvContent);

        // Create report record
        Report::create([
            'type' => 'csv',
            'title' => 'CSV Export - ' . now()->format('Y-m-d H:i'),
            'generated_by' => Auth::id(),
            'parameters' => json_encode($request->only('resource_id', 'subject', 'type')),
            'file_path' => $filename,
        ]);

        // Stream the download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="assessment_export.csv"',
        ];

        return response()->streamDownload(function () use ($assessments) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'Resource Title',
                'Author',
                'Subject',
                'Type',
                'Criterion',
                'Score',
                'Justification',
                'Reviewer',
                'Date',
            ]);

            // Data rows
            foreach ($assessments as $assessment) {
                foreach ($assessment->scores as $score) {
                    fputcsv($handle, [
                        $assessment->resource->title,
                        $assessment->resource->author,
                        $assessment->resource->subject,
                        $assessment->resource->type,
                        $score->criterion->name,
                        $score->score,
                        $score->justification,
                        $assessment->reviewer->name,
                        $assessment->submitted_at?->format('Y-m-d'),
                    ]);
                }
            }

            fclose($handle);
        }, 'assessment_export.csv', $headers);
    }

    /**
     * Download a previously generated report.
     */
    public function download(Report $report): BinaryFileResponse|RedirectResponse
    {
        if (! Storage::disk('local')->exists($report->file_path)) {
            return redirect()
                ->route('reports.index')
                ->with('error', 'Report file not found.');
        }

        $extension = $report->type === 'pdf' ? '.pdf' : '.csv';
        $mimeType = $report->type === 'pdf' ? 'application/pdf' : 'text/csv';

        return response()->download(
            Storage::disk('local')->path($report->file_path),
            $report->title . $extension,
            ['Content-Type' => $mimeType]
        );
    }

    /**
     * Build CSV content string from assessments collection.
     */
    private function buildCsvContent($assessments): string
    {
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, [
            'Resource Title',
            'Author',
            'Subject',
            'Type',
            'Criterion',
            'Score',
            'Justification',
            'Reviewer',
            'Date',
        ]);

        foreach ($assessments as $assessment) {
            foreach ($assessment->scores as $score) {
                fputcsv($handle, [
                    $assessment->resource->title,
                    $assessment->resource->author,
                    $assessment->resource->subject,
                    $assessment->resource->type,
                    $score->criterion->name,
                    $score->score,
                    $score->justification,
                    $assessment->reviewer->name,
                    $assessment->submitted_at?->format('Y-m-d'),
                ]);
            }
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }
}
