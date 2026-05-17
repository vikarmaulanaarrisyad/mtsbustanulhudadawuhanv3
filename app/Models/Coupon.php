<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Check if coupon is valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount.
     */
    public function calculateDiscount($originalPrice): int
    {
        if ($this->discount_type === 'percentage') {
            return (int) round(($originalPrice * $this->discount_value) / 100);
        }

        // Fixed discount
        return (int) min($this->discount_value, $originalPrice);
    }
}
