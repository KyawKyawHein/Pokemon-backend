<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestProduct;
use App\Http\Requests\UpdateRequestProduct;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(isset($_GET['status'])){
            $products= Product::latest('id')->with('category')->where('status',$_GET['status'])->paginate(9);
        }else{
            $products= Product::latest('id')->with('category')->paginate(9);
        }
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestProduct $request)
    {
        $file = $request->file('image');
        $imageName = uniqid() . $file->getClientOriginalName();
        $request->image->storeAs('public', $imageName);

        $product = Product::create([
            'name'=>$request->name,
            "status"=>'active',
            "category_id"=>$request->category_id,
            "image"=>$imageName,
            "price"=>$request->price,
            "stock_quantity"=>$request->stock_quantity
        ]);
        return response()->json([
            "message"=>"Product is created.",
            "product"=>new ProductResource($product)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json(['error'=>"Product not found."],404);
        }else{
            return new ProductResource($product);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequestProduct $request, string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json(['error'=>"Product not found."],404);
        }else{
            // if image is updated, old image is deleted and put new image
            $file = $request->file('image');
            if(!$file){
                $imageName = $product->image;
            }else{
                $imageName = uniqid() . $file->getClientOriginalName();
                $request->image->storeAs('public', $imageName);
            }

            $product->update([
                'name' => $request->name,
                "active"=>$request->active??'active',
                "category_id" => $request->category_id,
                "image" => $imageName,
                "price" => $request->price,
                "stock_quantity" => $request->stock_quantity
            ]);
            return response()->json([
                "message"=>"Product is updated."
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message'=>"Product is not found."],404);
        }else{
            if(Storage::exists($product->image)){
                Storage::delete($product->image);
            }
            $product->delete();
            return response()->json(['message'=>"Product is deleted."]);
        }
    }

    public function filter(){
        $search = $_GET['search']??'';
        if(isset($_GET['status'])){
            $status = $_GET['status'];
            $products = Product::latest('id')->with('category')->where('status', $status)->where('name', 'like', "%$search%")->get();
        }else{
            $products = Product::latest('id')->with('category')->where('name', 'like', "%$search%")->get();
        }
        return ProductResource::collection($products);
    }
}
