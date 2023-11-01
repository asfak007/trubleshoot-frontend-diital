<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function campaigns()
    {
        return $this->hasMany(Camapigns::class);
    }
}
