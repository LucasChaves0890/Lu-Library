<?php

use App\Http\Controllers\Api\AuthorsController;
use App\Http\Controllers\Api\BookRatingsController;
use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\BooksToReadController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowsController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\PasswordResetLinkController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserFavoriteBooksController;
use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\BookshelfController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| This is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and will
| be assigned to the "api" middleware group.
|
*/

// Public routes (accessible without authentication)
Route::post('register', [RegisterController::class, 'store']); // User registration
Route::post('login', [LoginController::class, 'store']); // User login
Route::post('reset', [PasswordResetLinkController::class, 'store']); // Request password reset link
Route::post('password/reset', [NewPasswordController::class, 'store']); // Reset password

// Public search routes
Route::get('search', [AppController::class, 'searchAllByQuery']); // Search for books, authors, and users
Route::get('author/search', [AuthorsController::class, 'searchAuthors']); // Search authors
Route::get('book/search', [BooksController::class, 'searchBooks']); // Search books

// Protected routes (require authentication via Sanctum)
Route::middleware('auth:sanctum')->group(callback: function () {

    // Author routes
    Route::get('author/{authorId}', [AuthorsController::class, 'show']); // Get author details

    // Home page route
    Route::get('/home', [AppController::class, 'home']); // Get home page data

    // Profile routes
    Route::get('/profile/{user}', [ProfileController::class, 'showProfile']); // Get user profile

    // User routes (excluding index)
    Route::resource('user', UserController::class)->except('index'); // User resource (show, update, delete)

    // Follow routes
    Route::post('/follow', [FollowsController::class, 'store']); // Follow a user

    // Logout route
    Route::delete('logout', [LoginController::class, 'destroy']); // Logout user

    // Book routes
    Route::get('book/{book}', [BooksController::class, 'show']); // Get book details

    // Favorite book routes
    Route::post('favorite', [UserFavoriteBooksController::class, 'store']); // Favorite a book

    // Books to read routes (Mark, unmark, and update reading progress)
    Route::post('read', [BooksToReadController::class, 'store']); // Mark book as reading
    Route::delete('read', [BooksToReadController::class, 'destroy']); // Unmark book as reading
    Route::put('read', [BooksToReadController::class, 'update']); // Update pages read

    // Book rating routes
    Route::post('/rate-book', [BookRatingsController::class, 'store']); // Rate a book

    // Bookshelf routes
    Route::get('/bookshelf/{userId}', [BookshelfController::class, 'bookShelfPage']); // Get user's bookshelf
    Route::get('/bookshelf/search/{userId}/', [BookshelfController::class, 'getBookshelf']); // Search user's bookshelf
    Route::get('/bookshelf/search/{userId}/{title}', [BookshelfController::class, 'searchBookshelf']); // Search book in bookshelf
    Route::get('readed/{userId}', [BookshelfController::class, 'getUserReadedBooks']); // Get read books
    Route::get('reading/{userId}', [BookshelfController::class, 'getUserReadingBooks']); // Get currently reading books
    Route::get('favorites/{userId}', [BookshelfController::class, 'getUserFavoriteBooks']); // Get favorite books

    // Post routes
    Route::resource('post', PostController::class)->except(['destroy']); // Create, read, update posts
    Route::delete('post/{post}/{user}', [PostController::class, 'destroy']); // Delete a post
    Route::post('/post/{post}/like', [PostController::class, 'likePost']); // Like a post
    Route::post('/post/{post}/dislike', [PostController::class, 'dislikePost']); // Dislike a post

    // Comment routes
    Route::get('/post/teste/{comment}', [CommentController::class, 'getCommentsFirstCommentAndAbovePost']); // Get top-level comments
    Route::post('/comment', [CommentController::class, 'store']); // Create a comment
    Route::get('/post/comments/{post}', [CommentController::class, 'getPostAndCommentsAndFirstSubComment']); // Get comments on a post
    Route::post('/comment/{comment}/like', [CommentController::class, 'likeComment']); // Like a comment
    Route::post('/comment/{comment}/dislike', [CommentController::class, 'dislikeComment']); // Dislike a comment
});

// Admin-only routes (require authentication and admin role)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('user', [UserController::class, 'index']); // Get all users (admin only)
    
    // Admin book routes
    Route::resource('admin/book', BooksController::class)->only(['index', 'store', 'update', 'destroy']); // Manage books (list, add, update, delete)
    
    // Admin author routes
    Route::resource('admin/author', AuthorsController::class)->only(['index', 'store', 'update', 'destroy']); // Manage authors (list, add, update, delete)
});
