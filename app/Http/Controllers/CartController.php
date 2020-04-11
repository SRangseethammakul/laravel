<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Order;

class CartController extends Controller
{
    //
    public function index(){
        $listCart = auth()->user()->products()->latest()->get();
        // dd($listCart);
        $sumPrice = auth()->user()->products()->sum('products.price');

        $sumQty = Cart::where('user_id',auth()->user()->id)->sum('qty');

        return view('cart',[
            'listCart' => $listCart,
            'sumPrice' => $sumPrice,
            'sumQty' => $sumQty
        ]);
    }

    public function store($product_id){
        $qty = auth()->user()->products()->where('product_id',$product_id)->first();

        if(isset($qty)){
            auth()->user()->products()->syncWithoutDetaching([$product_id => ['qty' => $qty->pivot->qty+1]]); // เพิ่มได้หลายครั้ง วิดีโอ 15
        }
        else{
            auth()->user()->products()->syncWithoutDetaching([$product_id => ['qty' => 1]]); // เพิ่มได้หลายครั้ง วิดีโอ 15
        }

        return back()->with('feedback','เพิ่มสินค้าเรียบร้อยแล้ว');
    }

    public function delete($product_id){
        auth()->user()->products()->detach($product_id);
        return back()->with('feedback','ลบสินค้าเรียบร้อยแล้ว');
    }

    public function confirm(){
        $listCart = auth()->user()->products()->latest()->get();
        foreach ($listCart as $p){
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->product_id = $p->id;
            $order->qty = $p->pivot->qty;
            $order->price = $p->price;
            $order->total = ($p->pivot->qty*$p->price);
            $order->save();

            //delete cart
            auth()->user()->products()->detach($p->id);
        }
        return redirect()->route('welcome')->with('feedback', 'ซื้อสินค้าเรียบร้อยแล้ว');
    }
}
