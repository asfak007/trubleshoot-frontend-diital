<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    protected $table = 'carts'; // Set the table name if different from the default
    protected $fillable = ['user_id', 'service_id', 'count'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
