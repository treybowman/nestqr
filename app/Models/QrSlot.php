<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class QrSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'icon_id',
        'short_code',
        'qr_image_path',
        'current_listing_id',
        'total_scans',
        'icon_locked_at',
    ];

    protected function casts(): array
    {
        return [
            'icon_locked_at' => 'datetime',
            'total_scans' => 'integer',
        ];
    }

    // Boot

    protected static function booted(): void
    {
        static::creating(function (QrSlot $slot) {
            if (empty($slot->short_code)) {
                $slot->short_code = static::generateShortCode();
            }
        });
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function icon(): BelongsTo
    {
        return $this->belongsTo(Icon::class);
    }

    public function currentListing(): BelongsTo
    {
        return $this->belongsTo(Listing::class, 'current_listing_id');
    }

    public function scanAnalytics(): HasMany
    {
        return $this->hasMany(ScanAnalytic::class);
    }

    // Scopes

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('current_listing_id');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('current_listing_id');
    }

    // Helpers

    public function isAssigned(): bool
    {
        return $this->current_listing_id !== null;
    }

    public function isIconLocked(): bool
    {
        return $this->icon_locked_at
            && $this->icon_locked_at->diffInHours(now()) < config('nestqr.icon_lock_hours', 24);
    }

    public function canChangeIcon(): bool
    {
        return ! $this->isIconLocked();
    }

    public function getPublicUrl(): string
    {
        $domain = $this->user?->preferred_domain ?? config('nestqr.primary_domain', 'nestqr.com');

        return 'https://' . $domain . '/' . $this->short_code;
    }

    public static function generateShortCode(): string
    {
        do {
            $code = Str::lower(Str::random(6));
        } while (static::where('short_code', $code)->exists());

        return $code;
    }
}
