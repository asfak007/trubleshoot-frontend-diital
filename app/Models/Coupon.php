<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'code',
        'discount',
        'start',
        'end',
        'min_amount',
        'provider_id',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    // Define the relationship with the provider if necessary
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
