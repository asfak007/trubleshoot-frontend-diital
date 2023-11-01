<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Providor extends Model
{
    use HasFactory;
    public function services()
    {
        return $this->hasMany(Service::class, 'provider_id');
    }

    // Define the "belongs to" relationship with the Country model
//    public function country()
//    {
//        return $this->belongsTo(Country::class, 'country_id');
//    }
//
//    // Define the "belongs to" relationship with the State model
//    public function state()
//    {
//        return $this->belongsTo(State::class, 'state_id');
//    }
//
//    // Define the "belongs to" relationship with the City model
//    public function city()
//    {
//        return $this->belongsTo(City::class, 'city_id');
//    }
//
//    // Define the "belongs to" relationship with the ProviderType model
//    public function providerType()
//    {
//        return $this->belongsTo(ProviderType::class, 'providertype_id');
//    }
}
