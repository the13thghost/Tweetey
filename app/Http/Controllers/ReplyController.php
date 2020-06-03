<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Tweet;
use App\User;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Tweet $tweet) {

        $attributes = request()->validate([
            'reply' => ['required', 'min:1', 'max:255'],
        ]);

        Reply::create([
            'tweet_id' => $tweet->id, 
            'user_id' => auth()->user()->id,
            'reply' => $attributes['reply']
        ]);
    }
}
