<?php

namespace App\Http\Resources;

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
        $urlsImages = $this->photos->pluck('url');
        $urlsImages = collect($urlsImages)->map(function ($url) {
            // check if is not a valid url 
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            } else {
                return asset($url); 
            }
            //return url($url);
        });
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'currency' => $this->devise ? $this->devise->code : null,
            'quantity' => $this->quantity,
            'exchangeRate' => (float) $this->exchange_rate,
            'convertedPrice' => $this->price * $this->exchange_rate,
            'type' => $this->category && $this->category->type ? $this->category->type->name : null,
            'category' => $this->category ? $this->category->name : null,
            'packaging' => $this->packaging,
            'photo' => $urlsImages,
            'date' => $this->date->format('Y-m-d'),
        ];
    }
}
