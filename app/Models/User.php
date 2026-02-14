<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'bio', 'photo_path',
        'plan_tier', 'preferred_domain', 'custom_logo_path',
        'custom_brand_color', 'theme_preference', 'is_admin', 'company_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'trial_ends_at' => 'datetime',
        ];
    }

    // Relationships

    public function qrSlots(): HasMany
    {
        return $this->hasMany(QrSlot::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Scopes

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    // Helpers

    public function planConfig(): array
    {
        return config('nestqr.plans.' . $this->plan_tier, config('nestqr.plans.free'));
    }

    public function maxQrSlots(): int
    {
        return $this->planConfig()['qr_slots'];
    }

    public function maxIcons(): int
    {
        return $this->planConfig()['icons'];
    }

    public function canAccessIcon(Icon $icon): bool
    {
        if ($icon->tier === 'free') {
            return true;
        }

        return in_array($this->plan_tier, ['pro', 'unlimited', 'company']);
    }

    public function hasCustomBranding(): bool
    {
        return $this->planConfig()['custom_branding'] ?? false;
    }

    public function hasAdvancedAnalytics(): bool
    {
        return ($this->planConfig()['analytics'] ?? 'basic') === 'advanced';
    }

    public function canCreateQrSlot(): bool
    {
        return $this->qrSlots()->count() < $this->maxQrSlots();
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->custom_logo_path ? asset('storage/' . $this->custom_logo_path) : null;
    }
}
