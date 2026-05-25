<?php

namespace App\Models;

use Database\Factories\ResourceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    /** @use HasFactory<ResourceFactory> */
    use HasFactory, SoftDeletes;

    // ── Constants ────────────────────────────────────────────

    public const TYPES = [
        'textbook'  => 'Textbook',
        'reference' => 'Reference Book',
        'ebook'     => 'E-Book',
    ];

    public const SUBJECTS = [
        'Mathematics',
        'Science',
        'English',
        'History',
        'Geography',
        'Computer Science',
        'Physics',
        'Chemistry',
        'Biology',
        'Economics',
        'Literature',
        'Art',
        'Music',
        'Physical Education',
    ];

    public const GRADE_LEVELS = [
        'K-2',
        '3-5',
        '6-8',
        '9-10',
        '11-12',
        'Undergraduate',
        'Graduate',
        'Professional',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'isbn',
        'type',
        'subject',
        'grade_level',
        'language',
        'edition',
        'cover_image',
        'description',
        'created_by',
    ];

    // ── Relationships ────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function flags(): HasMany
    {
        return $this->hasMany(Flag::class);
    }

    // ── Scopes ───────────────────────────────────────────────

    /**
     * Search resources by title, author, or ISBN.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('author', 'like', "%{$term}%")
              ->orWhere('isbn', 'like', "%{$term}%");
        });
    }

    /**
     * Filter by resource type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Filter by subject.
     */
    public function scopeOfSubject(Builder $query, string $subject): Builder
    {
        return $query->where('subject', $subject);
    }

    // ── Accessors ────────────────────────────────────────────

    /**
     * Average overall_score from submitted assessments.
     */
    public function getAverageScoreAttribute(): ?float
    {
        $avg = $this->assessments()
            ->where('status', 'submitted')
            ->avg('overall_score');

        return $avg !== null ? round((float) $avg, 2) : null;
    }

    /**
     * Count of submitted assessments.
     */
    public function getAssessmentCountAttribute(): int
    {
        return $this->assessments()
            ->where('status', 'submitted')
            ->count();
    }
}
