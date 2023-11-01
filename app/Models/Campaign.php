<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'zone_id', 'category_id','type','start','end', 'service_id', 'sub_category_id', 'image', 'discount'];


    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }



    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

}
