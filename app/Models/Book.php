<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'gender',
        'book_cover',
        'price',
        'author_id',
        'number_of_pages',
        'average_rating',
    ];

    protected $data = ['deleted_at'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, UserFavoriteBook::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites_books');
    }

    public function booksToRead()
    {
        return $this->hasMany(BookToRead::class, 'books_to_read');
    }

    public function ratings()
    {
        return $this->hasMany(BookRating::class, 'book_id');
    }

    public function userRating($userId)
    {
        return $this->hasOne(BookRating::class, 'book_id')->where('user_id', $userId);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}
