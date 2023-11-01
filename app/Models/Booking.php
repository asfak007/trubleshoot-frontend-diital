<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "provider_id",
        "address_id",
        "customer_id",
        "coupon_id",
        "handyman_id",
        "campaign_id",
        "service_id",
        "category_id",
        "zone_id",
        "status",
        "is_paid",
        "payment_method",
        "hint",
        "metadata",
        "total_amount",
        "total_tax",
        "total_discount",
        "additional_charge",
        "is_rated",
        "schedule",
        "quantity"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => AsCollection::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'customer.remember_token',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function handyman()
    {
        return $this->belongsTo(Handyman::class);
    }



}
