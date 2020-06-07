<?php

namespace App\Policies;

use App\Tweet;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TweetPolicy
{
    use HandlesAuthorization;

    public function editTweet(User $user, Tweet $tweets) {
        return $tweets->user_id == $user->id;
    }
}
