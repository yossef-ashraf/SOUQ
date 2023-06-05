<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductSizeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
                'id' =>$this->id ,
                'count' =>$this->count ,
                'user_id' => $this->user_id,
                'soldout' => $this->soldout,
                'product' =>  [
                    'product_id' =>$this->products->id ,
                    'name' =>$this->products->name ,
                    'discount' =>$this->products->discount ,
                    'price' =>$this->product_size->price ,
                    'quantity' =>$this->product_size->quantity ,
                    'image' => asset($this->product_size->image),
                   ],
               ];


    }
}
