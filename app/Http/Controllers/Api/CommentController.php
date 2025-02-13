<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentFormRequest;
use App\Services\CommentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CommentController extends Controller
{

    public function __construct(
        private CommentsService $service,
    ) {}

    public function getPostAndCommentsAndFirstSubComment(int $postId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->getPostWithCommentsWithLikes($postId),
                200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha na busca por comentario, tente novamente mais tarde.'
            ], 500);
        }
    }

    public function getCommentsFirstCommentAndAbovePost(int $commentId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->getPostWithCommentsAboveAndBelowWithLikes($commentId),
                200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha na busca por comentario, tente novamente mais tarde.'
            ], 500);
        }
    }

    public function store(CommentFormRequest $request): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Comentário enviado com sucesso.',
                $this->service->createComment($request)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao criar comentário, tente novamente mais tarde.'
            ]);
        }
    }

    public function likeComment(Request $request, $commentId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->likeComment($request, $commentId),
                200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao dar Like no comentário, tente novamente mais tarde.'
            ]);
        }
    }

    public function dislikeComment(Request $request, $commentId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->dislikeComment($request, $commentId),
                200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao dar Like no comentário, tente novamente mais tarde.'
            ]);
        }
    }
}
