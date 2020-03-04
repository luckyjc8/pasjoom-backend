<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Cart;
use App\Product;

class CartController extends Controller
{
	public function addItem(Request $request){
		$cart = new Cart;
		$cart->product_id = $request->product_id;
		$cart->user_id = $request->user_id;
		$cart->qty = $request->qty;
		$cart->save();
		return $this->jsonify(1,"Added successfully",201);
	}

	public function getItems($id){
		$cart = Cart::where('user_id',$id)->get();
		foreach($cart as $c){
			$c->product = Product::where('id',$c->product_id)->first();
		}
		if(!$cart){return $this->jsonify(0,"Cart empty");}
		return $this->jsonify(0,"Success",200,$cart);
	}
}