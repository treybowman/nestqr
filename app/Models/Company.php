<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_url',
        'brand_color',
        'admin_user_id',
        'billing_email',
        'plan_start_date',
    ];

    protected function casts(): array
    {
        return [
            'plan_start_date' => 'date',
        ];
    }

    // Relationships

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function agents(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
