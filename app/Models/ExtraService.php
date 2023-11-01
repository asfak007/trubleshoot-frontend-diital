<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraService extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'comment', 'min_price', 'service_id'];
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
}
