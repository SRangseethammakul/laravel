<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;

class WelcomeController extends Controller
{
    //
    public function index(){
        $category = Category::all();
        $product = Product::latest()->limit(6)->get();
        return view('welcome',[
            'category' => $category,
            'products' => $product
        ]);
    }

    public function show($id){
        // $product = Category::findOrFail($id)->products()->get();
        // $category_All = DB::select()
        // $category_All = DB::select('name',  'email as user_email')->get();

        $category = Category::with('products')->where('id','=',$id)->first();
        $category_All = Category::all();
        return view('show',[
            'category_All' => $category_All,
            'category' => $category
        ]);

    }
}
