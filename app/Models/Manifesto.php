<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manifesto extends Model
{
    protected $fillable = [
        'container_id',
        'destination',
        'original_weight',
        'original_unit',
        'weight_kg',
        'hazmat',
    ];

    protected function casts(): array
    {
        return [
            'container_id' => 'integer',
            'original_weight' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'hazmat' => 'boolean',
        ];
    }
}
