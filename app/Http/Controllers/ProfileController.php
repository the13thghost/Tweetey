<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Tweet;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as ValidationRule;

class ProfileController extends Controller 
{
    public function show(User $user) {
        $yesterday = Carbon::now()->subDay(); // calculate if tweet is more than 24 hours old
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->paginate(10);
        return view('profile.show', [ 
            'tweets' => $tweets,
            'user' => $user,
            'totalTweets' => $totalTweets,
            'yesterday' => $yesterday 
        ]);
    }

    public function edit(User $user) {
        return view('profile.edit', compact('user'));
    }

    public function update(User $user) {
        $attributes = request()->validate([
            'name' => ['string', 'max:255', 'required'],
            'username' => ['required', 'string', 'alpha_dash', 'max:255', 'min:6', ValidationRule::unique('users')->ignore($user)],
            'avatar' => ['file'],
            'cover' => ['file'],
            'email' => ['required', 'string', 'email', 'max:255', ValidationRule::unique('users')->ignore($user)],
            'password' => ['required', 'string', 'max:255', 'min:6', 'confirmed']
        ]);

        if(request('avatar')) {
            $attributes['avatar'] = request('avatar')->store('avatars');
        }

        if(request('cover')) {
            $attributes['cover'] = request('cover')->store('covers');
        }

        $user->update($attributes);
        return redirect($user->path());
    }

    public function updateBio(User $user) {
        $validatedAttributes = request()->validate([
            'bio' => ['string', 'max:140', 'min:1', 'required']
        ]);

        $user->update([
            'bio' => $validatedAttributes['bio']
        ]);
    }

    //dynamic profile nav link : Tweets 
    public function tweetsNav(User $user) {
        $yesterday = Carbon::now()->subDay(); 
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->paginate(10);
        
        return response()->json([
            'tweets-timeline' => view('__tweets-timeline',[ 
                'tweets' => $tweets,
                'user' => $user,
                'totalTweets' => $totalTweets,
                'yesterday' => $yesterday 
            ])->render()
        ]);
    }

    //dynamic profile nav link : Tweets & Replies
    public function withRepliesAjax(User $user) {
        $yesterday = Carbon::now()->subDay(); 
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $replies = Reply::where('user_id', $user->id)->latest()->paginate(10);
        return response()->json([
            'with-replies' => view('_with-replies',[ 
                'tweets' => $replies,
                'user' => $user,
                'yesterday' => $yesterday
            ])->render()
        ]);
    }

    public function withReplies(User $user) {
        $yesterday = Carbon::now()->subDay(); 
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->where('retweeted_from', null)->latest()->paginate(10);
        return view('profile.show',[ 
            'tweets' => $tweets,
            'user' => $user,
            'totalTweets' => $totalTweets,
            'yesterday' => $yesterday 
        ]);
    }

    //dynamic profile nav link : media

    //dynamic profile nav link : links

    
}
