<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ── Role Helpers ─────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isReviewer(): bool
    {
        return $this->role === 'reviewer';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    // ── Relationships ────────────────────────────────────────

    /**
     * Resources created by this user.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'created_by');
    }

    /**
     * Assessments authored by this user.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'reviewer_id');
    }

    /**
     * Flags raised by this user.
     */
    public function flags(): HasMany
    {
        return $this->hasMany(Flag::class, 'raised_by');
    }

    /**
     * Alias for flags() – flags this user raised.
     */
    public function raisedFlags(): HasMany
    {
        return $this->hasMany(Flag::class, 'raised_by');
    }

    /**
     * Flags resolved by this user.
     */
    public function resolvedFlags(): HasMany
    {
        return $this->hasMany(Flag::class, 'resolved_by');
    }
}
