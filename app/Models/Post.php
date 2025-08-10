<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User; // Add this line to import the User model

class Post extends Model
{
    use HasFactory;

    // Define fillable attributes
    protected $fillable = [
        'title',
        'body',
        'user_id' // Add user_id to fillable
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
