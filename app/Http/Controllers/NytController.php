<?php

namespace App\Http\Controllers;

use App\Exceptions\NytApiException;
use App\Http\Requests\BestSellersRequest;
use App\NytApi\Services\NytApiService;

class NytController extends Controller
{
    public function __construct(protected NytApiService $nytApiService)
    {
    }

    public function bestSellers(BestSellersRequest $request)
    {
        $validated = $request->validated();

        try {
            return $this->nytApiService->findBooks(
                $validated['author'] ?? null,
                collect($validated['isbn'] ?? []),
                $validated['title'] ?? null,
                $validated['offset'] ?? 0,
            );
        } catch (NytApiException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }
}
