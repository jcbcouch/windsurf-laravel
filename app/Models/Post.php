<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'body',
        'user_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['likes_count', 'is_liked'];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The users who liked the post.
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user')->withTimestamps();
    }

    /**
     * Get the number of likes for the post.
     *
     * @return int
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Check if the authenticated user has liked the post.
     *
     * @return bool
     */
    public function getIsLikedAttribute(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return $this->likes()->where('user_id', Auth::id())->exists();
    }
}
