<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Order_Item;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Order_ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'price' => $this->price,
            'preparation_by'=> $this->preparation_by,
            'note'=> $this->notes,
            'product' => ProductResource::collection(Product::where('id', $this->product_id)->get()),
        ];
    }
}
