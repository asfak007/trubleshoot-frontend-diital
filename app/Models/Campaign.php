<?php

namespace App\Models;

use App\Models\Zone;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'zone_id', 'category_id','type','start','end', 'service_id', 'sub_category_id', 'image', 'discount'];


    /**
     * Display the specified resource.
     *
     * @return  \App\Models\Service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Display the specified resource.
     *
     * @return  \App\Models\Zone
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * Interact with the image.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value != null && file_exists(public_path($value)) ? asset($value) : asset("assets/images/campaign/default.png"),
            // set: fn ($value) => strtolower($value),
        );
    }
}
