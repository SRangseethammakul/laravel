<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //one to one
    //user()->profile->address;
    public function profile(){
        //return $this->hasOne(Profile::class,'u_id); fk ตาราง profile
        return $this->hasOne(Profile::class); // fk default user_id
    }

    // many to many ต้องสลับ product กับ user อยู่ที่ model ไหน เอา model นั้นขึ้นก่อน
    public function products(){
        return $this->belongsToMany(Product::class,'carts','user_id','product_id')->withPivot('qty')->withTimestamps(); // ดูว่าใครซื้อบ้าง
    }
}
