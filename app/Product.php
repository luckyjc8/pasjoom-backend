<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name','price','resale_price','discounted_price','size','condition','desc'
    ];

    public function getFillables(){
        return $this->fillable;
    }
}
