<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutoryParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'parameter_key',
        'name',
        'description',
        'value_payload',
        'effective_from',
        'effective_to',
        'is_active',
        'reference_gazette',
    ];

    protected $casts = [
        'value_payload' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Scope query to find parameters effective on a given date.
     */
    public function scopeEffectiveOn($query, string $date)
    {
        return $query->where('effective_from', '<=', $date)
            ->where('effective_to', '>=', $date)
            ->where('is_active', true);
    }
}
