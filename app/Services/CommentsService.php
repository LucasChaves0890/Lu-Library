<?php

namespace App\Services;

use App\Http\Requests\CommentFormRequest;
use App\Models\Comment;
use App\Repositories\CommentsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CommentsService
{
    public function __construct(
        private CommentsRepository $repository,
        private UsersService $usersService,
        private PostsService $postsService,
        private dateFormatService $dateFormatter,
    ) {}

    public function createComment(CommentFormRequest $request): Comment
    {
        return DB::transaction(function () use ($request) {
            return $this->repository->create($request->all());
        });
    }

    public function likeComment(Request $request, $commentId): array
    {
        $comment = $this->repository->findCommentById($commentId);

        $comment->like($request->user(), true);

        return $this->buildReactionResponse($comment, $request);
    }

    public function dislikeComment(Request $request, $commentId): array
    {
        $comment = $this->repository->findCommentById($commentId);

        $comment->dislike($request->user());

        return $this->buildReactionResponse($comment, $request);
    }

    private function buildReactionResponse($comment, $request): array
    {
        $commentWithLikes = $this->repository->findCommentByIdWithLikes($comment->id);

        $liked = $comment->isLikedBy($request->user());
        $disliked = $comment->isDislikedBy($request->user());

        return [
            'likes' => $commentWithLikes->likes,
            'dislikes' => $commentWithLikes->dislikes,
            'liked' => $liked,
            'disliked' => $disliked
        ];
    }

    public function getPostWithCommentsAboveAndBelowWithLikes(int $commentId): array
    {
        $authUser = $this->usersService->getAuthUserWithFollows();
        $comment = $this->repository->getCommentWithUserAndWithLikes($commentId);
        $postData = $this->getPostWithLikes($comment->post_id, $authUser);

        return [
            'authUser' => $authUser,
            'post' => $postData['post'],
            'bookData' => $postData['bookData'],
            'aboveComments' => $this->getAboveComments($comment->parent_id, $authUser),
            'comment' => $this->formatComment($comment, $authUser),
            'comments' => $this->getMainComments($comment->id, $authUser),
        ];
    }

    private function getPostWithLikes(int $postId,  $authUser): array
    {
        $post =  $this->postsService->findPostWithUserAndWithLikes($postId);
        $post['liked'] = $post->isLikedBy($authUser);
        $post['disliked'] = $post->isDislikedBy($authUser);
        $post['time_ago'] = $this->dateFormatter->formatDate($post->created_at);
        $post['comments_count'] = $this->getCommentsCountByPostId($postId);

        return [
            'post' => $post,
            'bookData' => $post->book,
        ];
    }

    private function getCommentsCountByPostId(int $postId): int
    {
        return $this->repository->getCommentsByPostId($postId)
        ->count();
    }

    private function getAboveComments($parent_id,  $authUser): Collection
    {
        $aboveComments = collect();
        $currentComment = $this->repository->getCommentWithUserAndWithLikes($parent_id);

        while ($currentComment) {
            $aboveComments->prepend($this->formatComment($currentComment, $authUser));
            $currentComment = $this->repository->getCommentWithLikes($currentComment->parent_id);
        }

        return $aboveComments;
    }

    private function getMainComments(int $postId, $authUser): Collection
    {
        $comments = $this->repository->getMainComments($postId);

        return $comments->map(function ($comment) use ($authUser) {
            return $this->formatComment($comment, $authUser);
        });
    }

    public function getPostWithCommentsWithLikes(int $postId): array
    {
        $authUser = $this->usersService->getAuthUserWithFollows();
        $postData = $this->getPostWithLikes($postId, $authUser);

        return [
            'authUser' => $authUser,
            'post' => $postData['post'],
            'bookData' => $postData['bookData'],
            'comments' => $this->getCommentsByPost($postId, $authUser),
        ];
    }

    private function getCommentsByPost(int $postId, $authUser): Collection
    {
        $comments = $this->repository->getCommentsByPostId($postId);

        return $comments->map(function ($comment) use ($authUser) {
            return $this->formatComment($comment, $authUser);
        });
    }

    private function formatComment(Comment $comment,  $authUser): array
    {
        $comment['liked'] = $comment->isLikedBy($authUser);
        $comment['disliked'] = $comment->isDislikedBy($authUser);
        $comment['comments_count'] = $comment->children()->count();
        $comment['time_ago'] = $this->dateFormatter->formatDate($comment->created_at);

        return ([
            'comment' => $comment,
            'first_sub_comment' => $this->getFirstSubCommentWithLikes($comment->id, $authUser),
            'user' => $comment->user,
        ]);
    }

    private function getFirstSubCommentWithLikes(int $commentId,  $authUser): array|null
    {
        $firstSubComment = $this->repository->getFirstSubComment($commentId);

        if ($firstSubComment) {
            $firstSubComment['liked'] = $firstSubComment->isLikedBy($authUser);
            $firstSubComment['disliked'] = $firstSubComment->isDislikedBy($authUser);
            $firstSubComment['sub_comments_comment_count'] = $firstSubComment->children()->count();
            $firstSubComment['time_ago'] = $this->dateFormatter->formatDate($firstSubComment->created_at);


            return [
                'comment' => $firstSubComment,
                'user' => $firstSubComment->user,
            ];
        }

        return null;
    }
    
}
