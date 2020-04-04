<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $category_count = Category::count();
        $product_count  = Product::count();
        $user_count = User::count();
        return view('home',[
            'category_count' => $category_count,
            'product_count' => $product_count,
            'user_count' => $user_count
        ]);
    }
}
