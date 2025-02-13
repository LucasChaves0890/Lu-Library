<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowFormRequest;
use App\Services\FollowsService;
use Illuminate\Http\JsonResponse;

class FollowsController extends Controller
{
    public function __construct(private FollowsService $service) {}

    public function store(FollowFormRequest $request): JsonResponse
    {
        try {
            $data = $this->service->toggleFollow($request);

            return response()
                ->json([
                    'message' => 'success',
                    'follow' => $data['follow'],
                    'following' => $data['following'],
                ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao seguir usuário, tente novamente mais tarde.'
            ]);
        }
    }
}
