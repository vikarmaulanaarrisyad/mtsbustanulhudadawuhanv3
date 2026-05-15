<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'spp_billing_id',
        'amount',
        'payment_date',
        'payment_method',
        'receiver_id',
        'receipt_number',
        'notes',
    ];

    public function billing()
    {
        return $this->belongsTo(SppBilling::class, 'spp_billing_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
