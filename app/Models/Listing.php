<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'qr_slot_id',
        'address',
        'city',
        'state',
        'zip',
        'price',
        'beds',
        'baths',
        'sqft',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'beds' => 'integer',
            'baths' => 'decimal:1',
            'sqft' => 'integer',
        ];
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function qrSlot(): BelongsTo
    {
        return $this->belongsTo(QrSlot::class)->withDefault();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ListingPhoto::class)->orderBy('sort_order');
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address,
            $this->city,
            $this->state . ' ' . $this->zip,
        ]));
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format((float) $this->price, 0);
    }

    public function getPrimaryPhotoAttribute(): ?ListingPhoto
    {
        return $this->photos->first();
    }

    // Methods

    public function assignToQrSlot(QrSlot $slot): void
    {
        $this->update(['qr_slot_id' => $slot->id]);
        $slot->update(['current_listing_id' => $this->id]);
    }

    public function unassignFromQrSlot(): void
    {
        if ($this->qr_slot_id) {
            QrSlot::where('id', $this->qr_slot_id)
                ->update(['current_listing_id' => null]);

            $this->update(['qr_slot_id' => null]);
        }
    }
}
