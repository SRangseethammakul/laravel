<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    public function user(){
        //return $this->belongsTo(User::class,'u_id','user_id); fk and pk
        return $this->belongsTo(User::class);
    }
}
