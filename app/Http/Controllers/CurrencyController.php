<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CurrencyController extends Controller
{
    /**
     * List all currencies.
     */
    public function index(): AnonymousResourceCollection
    {
        $currencies = Currency::all(['id', 'name', 'code', 'symbol', 'exchange_rate']);

        return CurrencyResource::collection($currencies);
    }

    /**
     * Create a new currency.
     */
    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        $currency = Currency::create($request->validated());

        return (new CurrencyResource($currency))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Delete a currency when no product or product price references it.
     */
    public function destroy(Currency $currency): JsonResponse|Response
    {
        $isInUse = $currency->products()->exists() || $currency->productPrices()->exists();

        if ($isInUse) {
            return response()->json([
                'message' => 'No se puede eliminar la moneda porque está siendo utilizada por uno o más productos o precios de productos.',
            ], 409);
        }

        $currency->delete();

        return response()->noContent();
    }
}
