<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>$this->id,
            "name"=>$this->name,
            "status"=>$this->status,
            "image"=>$this->image,
            "price"=>$this->price,
            "stock_quantity"=>$this->stock_quantity,
            "category"=>$this->category->name
        ];
    }
}
