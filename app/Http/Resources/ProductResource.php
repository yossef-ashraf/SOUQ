<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductSizeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category_id' =>$this->category_id ,
            'name' =>$this->name ,
            'description' =>$this->description ,
            'discount' =>$this->discount ,
            'image' => asset($this->image),
            'price' =>$this->price ,
            'quantity' =>$this->quantity ,
           ];
    }
}
