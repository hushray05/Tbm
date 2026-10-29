<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// ✅ Make sure Business is imported
use App\Models\Business;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'business_id',
        'avatar_url',
        'job_title',
        'department',
        'phone',
        'bio',
        'role',
        'status',
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
            'is_super_admin' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    /**
     * Check if user is Super Administrator (has access to everything).
     * Only users with is_super_admin = 1 OR the master admin email can access /admin.
     */
    public function isSuperAdmin(): bool
    {
        // Master admin email – hardcoded for safety
        $masterAdminEmail = 'admin@tbm-enterprise.com';
        
        // Check the boolean flag OR the master email
        return (bool) $this->is_super_admin || $this->email === $masterAdminEmail;
    }

    /**
     * Check if user is Business Owner.
     */
    public function isOwner(): bool
    {
        return $this->role === 'Business Owner' || $this->role === 'Owner';
    }

    /**
     * Get the active business for this user.
     */
    public function getActiveBusiness(): ?Business
    {
        if ($this->business_id) {
            return Business::find($this->business_id);
        }

        // If super admin, default to first business or null
        return Business::first();
    }

    /**
     * Get businesses the user can access.
     * Super admins see all; regular users only see their own business.
     */
    public function getAccessibleBusinesses()
    {
        if ($this->isSuperAdmin()) {
            return Business::all();
        }
        return Business::where('id', $this->business_id)->get();
    }

    /**
     * Get the avatar URL with graceful fallback.
     */
    public function getAvatarAttribute(): string
    {
        if ($this->avatar_url) {
            return $this->avatar_url;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=003e97&color=ffffff&bold=true&size=128';
    }

    /**
     * Get user initials for badges.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach ($words as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
        return substr($initials, 0, 2) ?: 'U';
    }
}