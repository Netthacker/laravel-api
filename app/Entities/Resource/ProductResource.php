<?php

namespace App\Entities\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'category_id' => $this->category_id,
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image,
            
        ];
    }
}
