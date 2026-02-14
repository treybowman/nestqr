<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_slot_id',
        'listing_id',
        'scanned_at',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
        ];
    }

    // Relationships

    public function qrSlot(): BelongsTo
    {
        return $this->belongsTo(QrSlot::class);
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class)->withDefault();
    }

    // Scopes

    public function scopeForDateRange($query, $start, $end)
    {
        return $query->whereBetween('scanned_at', [$start, $end]);
    }

    public function scopeForQrSlot($query, int $slotId)
    {
        return $query->where('qr_slot_id', $slotId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('scanned_at', '>=', now()->subDays($days));
    }
}
