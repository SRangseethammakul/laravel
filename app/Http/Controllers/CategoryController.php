<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index(){

        // $category = Category::all();
        // $category = Category::find(1); //
        // $category = Category::findOrFail(10); // page 404
        // $category = Category::select('id','name')->where('id', 1)->get();
        $category = Category::orderBy('id','asc')->get();
        $countrow = Category::count();

        // return $category;
        return view('backend.category.index',[
            'category' => $category,
            'countrow' => $countrow
        ]);
    }

    public function store(){
        // $cat = new Category();
        // $cat->name = 'Food';
        // $cat->save();
        // return 'save successfully';

        $cat = Category::find(7);
        $cat->delete();
        return 'Delete successfully';
    }
}
