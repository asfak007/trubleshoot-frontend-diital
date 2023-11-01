<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'first_name', 'last_name', 'email','phone','otp', 'password', 'ref_code', 'image', 'status',
    ];
}
