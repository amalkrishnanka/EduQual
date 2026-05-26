<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>{{ $title }}</title>
<style>body{font-family:Arial,sans-serif;font-size:12px;color:#333;margin:30px}h1{color:#4f46e5;font-size:20px;border-bottom:2px solid #4f46e5;padding-bottom:8px}h2{color:#6366f1;font-size:16px;margin-top:24px}h3{color:#333;font-size:14px;margin-top:16px}table{width:100%;border-collapse:collapse;margin-top:12px}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f0f0ff;font-weight:bold}.score{font-weight:bold;text-align:center}.header{text-align:center;margin-bottom:30px}.meta{color:#666;font-size:11px}.footer{text-align:center;color:#999;font-size:10px;margin-top:40px;border-top:1px solid #ddd;padding-top:10px}.page-break{page-break-after:always}</style>
</head><body>
<div class="header"><h1>Bookly Assessment Report</h1><p class="meta">{{ $title }}</p><p class="meta">Generated on {{ $generatedAt->format('F d, Y \a\t h:i A') }}</p></div>

@foreach($resources as $resource)
<h2>{{ $resource->title }}</h2>
<table>
    <tr><th width="20%">Author</th><td>{{ $resource->author }}</td><th width="20%">Subject</th><td>{{ $resource->subject }}</td></tr>
    <tr><th>Type</th><td>{{ ucfirst($resource->type) }}</td><th>Grade Level</th><td>{{ $resource->grade_level }}</td></tr>
    @if($resource->publisher)<tr><th>Publisher</th><td>{{ $resource->publisher }}</td><th>ISBN</th><td>{{ $resource->isbn ?? 'N/A' }}</td></tr>@endif
</table>

@forelse($resource->assessments as $assessment)
<h3>Assessment by {{ $assessment->reviewer->name }}</h3>
<p class="meta">Submitted: {{ $assessment->submitted_at?->format('M d, Y') }} | Overall Score: <strong>{{ number_format($assessment->overall_score, 1) }}/10</strong></p>
<table>
    <thead><tr><th>Criterion</th><th width="10%">Weight</th><th width="10%">Score</th><th>Justification</th></tr></thead>
    <tbody>
    @foreach($assessment->scores as $score)
    <tr><td>{{ $score->criterion->name }}</td><td class="score">{{ number_format($score->criterion->weight * 100) }}%</td><td class="score">{{ $score->score }}/10</td><td>{{ $score->justification }}</td></tr>
    @endforeach
    </tbody>
</table>
@empty
<p class="meta"><em>No submitted assessments for this resource.</em></p>
@endforelse

@if(!$loop->last)<div class="page-break"></div>@endif
@endforeach

<div class="footer">Bookly — Educational Resource Quality Assessment System</div>
</body></html>
