<?php

namespace App\Services;

use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Repositories\UsersRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersService
{
    public function __construct(
        private UsersRepository $repository,
        private ImageService $imageService,
        private PostsService $postsService,
        private BooksToReadService $booksToReadService,
        private FollowsService $followsService,
    ) {}


    public function createUser(UserFormRequest $request)
    {
        return DB::transaction(function () use ($request) {
            return $this->repository->create($this->prepareUserData($request));
        });
    }

    private function prepareUserData(UserFormRequest $request): array
    {
        return [
            'username' => $request->username,
            'sex' => $request->sex,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
    }


    public function updateUser(UserFormRequest $request, User $user): User
    {
        return DB::transaction(function () use ($request, $user) {
            $request['profile_image'] = $this->updateProfileImageIfNeeded($user, $request);;
            $request['cover_image'] = $this->updateCoverImageIfNeeded($user, $request);;

            return $this->repository->update($user, $request->all());
        });
    }

    private function updateProfileImageIfNeeded(User $user, UserFormRequest $request)
    {
        if ($request->has('profile_image') && !empty($request->get('profile_image'))) {
            return $this->imageService->updateImage(
                $request->get('profile_image'),
                'profile_images',
                'profile_image.jpg',
                $user->profile_image
            );
        }

        return $user->profile_image;
    }

    private function updateCoverImageIfNeeded(User $user, UserFormRequest $request)
    {
        if ($request->has('cover_image') && !empty($request->get('cover_image'))) {
            return $this->imageService->updateImage(
                $request->get('cover_image'),
                'cover_images',
                'cover_image.jpg',
                $user->cover_image
            );
        }

        return $user->cover_image;
    }


    public function home(): array
    {
        $authUser = $this->getAuthUserWithFollows();
        $followingIds = $this->followsService->getFollowingIdsByUserId($authUser->id);
        $timelinePosts = $this->postsService->timelinePosts($followingIds, $authUser);

        return [
            'authUser' => $authUser,
            'timelinePost' =>  $timelinePosts
        ];
    }

    public function getAllUsersFormatted()
    {
        return $this->repository->getAllUsers()->map(function ($user) {
            return $this->formatUser($user);
        });
    }

    private function formatUser(User $user): array
    {
        $user['followingCount'] = $user->followingCount();
        $user['followersCount'] = $user->followersCount();
        $user['postsCount'] = $user->posts()->whereHas('book', function ($query) {
            $query->whereNull('deleted_at');
        })->count();

        return [
            'user' => $user,
        ];
    }

    public function getAuthUserWithFollows()
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception("Usuário não autenticado.");
        }

        $user['followingCount'] = $user->followingCount();
        $user['followersCount'] = $user->followersCount();

        return $user;
    }

    public function showProfile(int $userId): array
    {
        $authUser = $this->getAuthUserWithFollows();
        $user = $this->repository->findUser($userId);
        $userDetails = $this->addProfileStatistics($user);
        $posts = $this->postsService->getPostsByUserIdFormatted($user->id, $authUser);

        return [
            'authUser' => $authUser,
            'user' => $userDetails,
            'posts' => $posts
        ];
    }

    private function addProfileStatistics(User $user): User
    {
        $user['followingCount'] = $user->followingCount();
        $user['followersCount'] = $user->followersCount();
        $user['followed'] = $user->isFollowed();
        $user['favorites'] =  $user->user_favorites_books()->get()->count();
        $user['read'] = $this->booksToReadService->getReadById($user->id);

        return $user;
    }

    public function searchUserByName(string $query)
    {
        return $this->repository->searchUserByName($query);
    }
}
