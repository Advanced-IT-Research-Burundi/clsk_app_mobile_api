<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

class ContainerController extends Controller
{
    /**
     * List all containers with product count and preview photos.
     */
    public function index()
    {
        $containers = Container::withCount('products')
            ->with(['products' => function ($q) {
                $q->with('photos')->limit(3);
            }])
            ->latest()
            ->get();

        return response()->json($containers->map(function ($c) {
            return [
                'id'            => $c->id,
                'name'          => $c->name,
                'serial_number' => $c->serial_number,
                'description'   => $c->description,
                'products_count'=> $c->products_count,
                'preview_photos'=> $c->products->flatMap(function ($p) {
                    return $p->photos->map(fn($ph) => asset($ph->url));
                })->take(3)->values(),
            ];
        }));
    }

    /**
     * Return one container with all its archived products.
     */
    public function show(string $id)
    {
        $container = Container::with(['products' => function ($q) {
            $q->with(['photos', 'supplier', 'devise', 'category.type']);
        }])->findOrFail($id);

        $products = $container->products->map(function ($p) use ($container) {
            $photos = $p->photos->map(fn($ph) => filter_var($ph->url, FILTER_VALIDATE_URL)
                ? $ph->url
                : asset($ph->url)
            );
            return [
                'id'             => (string) $p->id,
                'name'           => $p->name,
                'description'    => $p->description,
                'price'          => (float) $p->price,
                'currency'       => $p->devise ? $p->devise->code : null,
                'quantity'       => $p->quantity,
                'exchangeRate'   => (float) $p->exchange_rate,
                'convertedPrice' => (float) ($p->price * $p->exchange_rate),
                'photo'          => $photos->values(),
                'supplier_name'  => $p->supplier ? $p->supplier->name : null,
                'is_archived'    => (bool) $p->is_archived,
                'container_id'   => $p->container_id,
                'container_name' => $container->name,
                'container_serial'=> $container->serial_number,
            ];
        });

        return response()->json([
            'id'            => $container->id,
            'name'          => $container->name,
            'serial_number' => $container->serial_number,
            'description'   => $container->description,
            'products'      => $products->values(),
        ]);
    }
}
