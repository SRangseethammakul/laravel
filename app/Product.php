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

    // many to many
    public function users(){
        return $this->belongsToMany(User::class,'carts','product_id','user_id')->withPivot('qty')->withTimestamps(); // ดูว่าใครซื้อบ้าง
    }
}
