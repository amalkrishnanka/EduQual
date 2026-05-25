<?php

namespace App\Models;

use Database\Factories\AssessmentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    /** @use HasFactory<AssessmentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'resource_id',
        'reviewer_id',
        'status',
        'overall_score',
        'submitted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'submitted_at'  => 'datetime',
            'overall_score' => 'decimal:2',
        ];
    }

    // ── Relationships ────────────────────────────────────────

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(AssessmentScore::class);
    }

    // ── Business Logic ───────────────────────────────────────

    /**
     * Calculate the weighted average from individual criterion scores and persist it.
     *
     * Formula: Σ(score × weight) / Σ(weight)
     */
    public function computeOverallScore(): float
    {
        $scores = $this->scores()->with('criterion')->get();

        if ($scores->isEmpty()) {
            $this->overall_score = 0;
            $this->save();

            return 0;
        }

        $weightedSum = 0;
        $totalWeight = 0;

        foreach ($scores as $score) {
            $weight = (float) $score->criterion->weight;
            $weightedSum += $score->score * $weight;
            $totalWeight += $weight;
        }

        $overall = $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : 0;

        $this->overall_score = $overall;
        $this->save();

        return $overall;
    }

    // ── Status Helpers ───────────────────────────────────────

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isLocked(): bool
    {
        return $this->status === 'locked';
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeSubmitted(Builder $query): Builder
    {
        return $query->where('status', 'submitted');
    }
}
