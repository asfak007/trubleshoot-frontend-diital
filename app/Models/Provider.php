<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'email',
        'phone',
        'zone_id',
        'identity_type',
        'identity_number',
        'contact_person_name',
        'contact_person_phone',
        'contact_email',
        'first_name',
        'last_name',
        'password',
        'image',
        'start',
        'end',
        'identity_images',
        'order_count',
        'service_man_count',
        'service_capacity_per_day',
        'rating_count',
        'avg_rating',
        'commission_status',
        'commission_percentage',
        'is_active',
        'is_approved',
        'off-day'
    ];

//    protected $casts = [
//        'identity_images' => 'array'
//    ];
    public function services()
    {
        return $this->hasMany(Service::class, 'provider_id');
    }
    public function documents()
    {
        return $this->hasMany(ProviderDocument::class);
    }

}
