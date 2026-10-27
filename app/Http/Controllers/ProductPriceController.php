<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateProductPriceException;
use App\Http\Requests\StoreProductPriceRequest;
use App\Http\Resources\ProductPriceResource;
use App\Models\Product;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductPriceController extends Controller
{
    /**
     * List the prices of a product, each with its currency.
     */
    public function index(Product $product): AnonymousResourceCollection
    {
        $prices = $product->productPrices()
            ->with('currency:id,name,code,symbol,exchange_rate')
            ->get();

        return ProductPriceResource::collection($prices);
    }

    /**
     * Create a price for a product in a given currency.
     */
    public function store(StoreProductPriceRequest $request, Product $product): JsonResponse
    {
        $attributes = $request->safe()->only([
            'currency_id',
            'price',
        ]);

        $currencyId = (int) $attributes['currency_id'];

        if ($this->priceExistsInCurrency($product, $currencyId)) {
            throw new DuplicateProductPriceException;
        }

        try {
            $price = $product->productPrices()->create($attributes);
        } catch (UniqueConstraintViolationException $exception) {
            if (! $this->priceExistsInCurrency($product, $currencyId)) {
                throw $exception;
            }

            throw new DuplicateProductPriceException;
        }

        return (new ProductPriceResource(
            $price->load('currency:id,name,code,symbol,exchange_rate')
        ))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Determine whether the product already has a price in the given currency.
     */
    private function priceExistsInCurrency(Product $product, int $currencyId): bool
    {
        return $product->productPrices()
            ->where('currency_id', $currencyId)
            ->exists();
    }
}
