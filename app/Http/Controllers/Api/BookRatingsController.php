<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRatingFormRequest;
use App\Services\BookRatingsService;
use Illuminate\Http\JsonResponse;

class BookRatingsController extends Controller
{
    public function __construct(
        private BookRatingsService $service
    ) {}

    public function store(BookRatingFormRequest $request): JsonResponse
    {
        try {
            $bookRating = $this->service->createOrUpdateRating($request);

            return response()->json($bookRating, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao avaliar.'
            ], 500);
        }
    }
}
