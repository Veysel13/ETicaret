<?php

namespace App\Http\Resources\Basket;

use App\Model\Config\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'foodId' => $this['attributes']['productId'] ?? '',
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'priceFormat' => $this->price.' TL',
            'total' => $this->getPriceSumWithConditions(),
            'totalFormat' => $this->getPriceSumWithConditions().' TL',
            'description' => $this['attributes']['description'] ?? '',
            'imageUrl' => $this->associatedModel->imageUrl,
            'categoryName' => $this->categoryName,
        ];
    }
}
