<?php

namespace App\Http\Controllers;

use App\Tweet;
use App\User;
use Illuminate\Http\Request;

class TweetsLikeController extends Controller
{
    //
    public function storeLike(Tweet $tweet, User $user) {
        $tweet->toggleLike($user ? $user->id : auth()->id());
        return back();
    }

    public function storeDislike(Tweet $tweet, User $user) {
        $tweet->toggleDislike($user ? $user->id : auth()->id());
        return back();
    }
}
