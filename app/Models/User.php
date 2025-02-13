<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory, SoftDeletes;

    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_image',
        'cover_image',
        'sex',
        'description',
        'role'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_favorites_books()
    {
        return $this->hasMany(UserFavoriteBook::class);
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'user_favorites_books');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'followed_user_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'following_user_id');
    }

    public function followersCount()
    {
        return $this->followers()->count();
    }

    public function followingCount()
    {
        return $this->following()->count();
    }

    public function isFollowed(): bool
    {
        return $this->followers()->where('following_user_id', Auth::id())->exists();
    }
    
    
    public function sendPasswordResetNotification($token)
    {
        Log::info('Sending password reset notification to ' . $this->email);
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
