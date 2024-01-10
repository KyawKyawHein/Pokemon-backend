<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function payment(Request $request){
        $request->validate([
            "product_id" =>['required','exists:products,id'],
            "user_id"=>['required','exists:users,id'],
            "quantity"=>['required']
        ]);
        $product = Product::where('id', $request->product_id);
        if($product->first()->stock_quantity<=0){
            return response()->json(['error'=>'There is no stock.']);
        }else if($product->first()->stock_quantity <$request->quantity){
            return response()->json(['error'=>"Stock is only ".$product->first()->stock_quantity." left."]);
        }
        $cart = Cart::create([
            'product_id'=>$request->product_id,
            "user_id"=>$request->user_id,
            "quantity"=>$request->quantity
        ]);
        $product->decrement('stock_quantity',$request->quantity);
        if($product->first()->stock_quantity==0){
            $product->update(['status'=>'inactive']);
        }
        return response()->json($cart);
    }
}
