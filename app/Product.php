<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //vatproduct
    public function getVatproductAttribute(){
        return $this->price * 0.07;
    }

    //many to one
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
