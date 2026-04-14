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
            'devise_id' => $this->devise_id,
            'currency' => $this->devise ? $this->devise->code : null,
            'quantity' => $this->quantity,
            'customs_price' => (float) $this->customs_price,
            'customs_price_currency' => $this->customs_price_currency,
            'cbm' => (float) $this->cbm,
            'exchangeRate' => (float) $this->exchange_rate,
            'convertedPrice' => $this->price * $this->exchange_rate,
            'type' => $this->category && $this->category->type ? $this->category->type->name : null,
            'category' => $this->category ? $this->category->name : null,
            'packaging' => $this->packaging,
            'unit_per_package' => (int) $this->unit_per_package,
            'number_of_cartons' => (int) $this->number_of_cartons,
            'photo' => $urlsImages,
            'supplier_id' => $this->supplier_id,
            'supplier' => $this->supplier,

            'supplier_name' => $this->supplier ? $this->supplier->name : null,
            'date' => $this->date ? $this->date->format('Y-m-d') : null,
            'is_archived' => (bool) $this->is_archived,
            'container_id' => $this->container_id,
            'container_name' => $this->container ? $this->container->name : null,
            'container_serial' => $this->container ? $this->container->serial_number : null,
        ];
    }
}
