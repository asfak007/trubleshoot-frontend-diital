<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'provider_id',
        'zone_id',
        'price',
        'type',
        'duration',
        'image',
        'discount',
        'status',
        'short_description',
        'long_description',
        'tax',
        'quantity',
        'order_count',
        'rating_count',
        'avg_rating',
        'is_featured',
        'by_admin',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }



    // Define the "belongs to" relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function extra_services()
    {
        return $this->hasMany(ExtraService::class);
    }
    public function carts()
    {
        return $this->hasMany(Card::class, 'service_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'service_id');
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
