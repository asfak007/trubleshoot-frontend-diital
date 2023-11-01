<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'parent_id', 'is_active', 'is_featured', 'zone_id', 'image'];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
    public function zones()
    {
        return $this->hasMany(Zone::class);
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }
    public function zonek()
    {
        return $this->belongsToMany(Zone::class, 'category_zone', 'category_id', 'zone');
    }
}
