<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id','nominal','products','delivery_address','status'
    ];

    public static function getFillables(){
        return $fillable;
    }
}
