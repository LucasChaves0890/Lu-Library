<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;
use App\Services\PostsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostsService $service) {}

    public function likePost(Request $request, $postId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->likePost($request, $postId),
                200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao dar like no post, tente novamente mais tarde.' . $e
            ]);
        }
    }

    public function dislikePost(Request $request, $postId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->dislikePost($request, $postId),
                200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao dar dislike no post, tente novamente.'
            ]);
        }
    }

    public function store(PostFormRequest $request): JsonResponse
    {
        $this->service->createPost($request);

        try {
            return response()->json([
                'message' => 'Post criado com sucesso!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $post, int $user): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Post excluído com sucesso.',
                'post' => $this->service->delete($post, $user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
