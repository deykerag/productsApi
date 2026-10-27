<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceResource extends JsonResource
{
    /**
     * Transform the product price into an array.
     *
     * The currency is included only when it has been eager loaded,
     * so no extra query runs when it is missing.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'currency_id' => $this->currency_id,
            'price' => $this->price,
            'currency' => new CurrencyResource($this->whenLoaded('currency')),
        ];
    }
}
