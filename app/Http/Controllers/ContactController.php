<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(){
        $info = "act";
        return view('contact.contact',[
            'info' => $info
        ]);
    }

    public function show(){
        return "show.contact";
    }

}
