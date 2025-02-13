<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookToReadFormRequest;
use App\Services\BooksToReadService;
use Illuminate\Http\JsonResponse;

class BooksToReadController extends Controller
{
    public function __construct(
        private BooksToReadService $service
    ) {}

    public function store(BookToReadFormRequest $request): JsonResponse
    {
        try {
            return response()->json(
                $this->service->toggleBookReadStatus($request),
                201
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao marcar como lido, tente novamente mais tarde.'
            ], 500);
        }
    }

    public function update(BookToReadFormRequest $request): JsonResponse
    {
        try {
            return response()->json(
                $this->service->updateOrCreateBookRead($request),
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar as páginas lidas.'
            ], 500);
        }
    }
}
