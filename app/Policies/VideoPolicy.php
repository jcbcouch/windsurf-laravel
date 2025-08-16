<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the video.
     */
    public function delete(User $user, Video $video): bool
    {
        return $user->id === $video->user_id;
    }
}
