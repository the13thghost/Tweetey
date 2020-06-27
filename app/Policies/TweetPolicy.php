<?php

namespace App\Policies;

use App\Tweet;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TweetPolicy
{
    use HandlesAuthorization;

    public function editTweet(User $user, Tweet $tweet) 
    {
        return $tweet->user_id == $user->id OR $tweet->user_id == auth()->id();
    }
}
