<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Http\Requests\ProductRequest;
// use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $products = Product::orderby('id','desc')->paginate(20);
        $products = Product::with('category')->orderby('id','desc')->paginate(20);
        return view('backend.product.index',[
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('backend.product.create',[
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        //check upload file
        if($request->hasFile('picture')){
            $newFileName    =   uniqid().'.'.$request->picture->extension();//gen name
            //upload file
            $request->picture->storeAs('image',$newFileName,'public'); // upload file
            $product->picture = $newFileName;

            // //resize
            // $path = Storage::disk('public')->path('image/resize/')
            // Image::make($request->picture->getRealPath(), $newFileName)->resize(120, null, function($constraint){
            //     $constraint->aspectRatio();
            // })->save($path.$newFileName);
        }
        $product->save();
        return redirect()->route('product.index')->with('feedback' ,'บันทึกข้อมูลเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
        return view('backend.product.edit',[
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        //ลบไฟล์เก่า แล้วอัพไฟล์ใหม่เข้าไป
        if($request->hasFile('picture')){
            Storage::disk('public')->delete('image/'.$product->picture);
            $newFileName    =   uniqid().'.'.$request->picture->extension();//gen name
            //upload file
            $request->picture->storeAs('image',$newFileName,'public'); // upload file
            $product->picture = $newFileName;
        }
        $product->save();
        return redirect()->route('product.index')->with('feedback' ,'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //delete file
        if($product->picture != 'nopic.png'){
            // ควรกำหนด disk ก่อน เผื่อวางไว้ใน cloud
            // dd($product->picture);
            Storage::disk('public')->delete('image/'.$product->picture);
        }
        $product->delete();
        return redirect()->route('product.index')->with('feedback' ,'ลบข้อมูลเรียบร้อยแล้ว');
    }
}
