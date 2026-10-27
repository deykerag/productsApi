<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * List all products with their base currency.
     */
    public function index(): ProductCollection
    {
        $products = Product::with('currency:id,name,code,symbol,exchange_rate')->paginate(10);

        return new ProductCollection($products);
    }

    /**
     * Create a new product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create([
            ...$request->validated(),
        ]);

        return (new ProductResource($product->load('currency:id,name,code,symbol,exchange_rate')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Show a product with its base currency.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('currency:id,name,code,symbol,exchange_rate'));
    }

    /**
     * Update a product.
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->validated());

        return new ProductResource($product->load('currency:id,name,code,symbol,exchange_rate'));
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
