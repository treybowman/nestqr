<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'market_name',
        'is_active',
        'launched_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'launched_at' => 'date',
        ];
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors

    public function getFullDomainAttribute(): string
    {
        return $this->domain . '.com';
    }
}
