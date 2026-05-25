<?php

namespace App\Models;

use Database\Factories\FlagFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flag extends Model
{
    /** @use HasFactory<FlagFactory> */
    use HasFactory;

    // ── Constants ────────────────────────────────────────────

    public const CATEGORIES = [
        'inaccurate'    => 'Inaccurate Content',
        'outdated'      => 'Outdated Material',
        'inappropriate' => 'Inappropriate Content',
        'other'         => 'Other',
    ];

    public const STATUSES = [
        'open'         => 'Open',
        'under_review' => 'Under Review',
        'resolved'     => 'Resolved',
        'dismissed'    => 'Dismissed',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'resource_id',
        'raised_by',
        'title',
        'description',
        'category',
        'status',
        'resolved_by',
        'resolution_notes',
        'resolved_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    // ── Relationships ────────────────────────────────────────

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function raisedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(FlagComment::class);
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
