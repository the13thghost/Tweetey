<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Tweet;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
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
        $this->authorize('edit', $user);
        
        return view('profile.edit', compact('user'));
    }

    public function update(User $user) {
        //update the users profile
        //dd(request()->all());
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

    //dynamic profile nav link : tweets & replies

    public function tweetsNav(User $user) {
        $yesterday = Carbon::now()->subDay(); // calculate if tweet is more than 24 hours old
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->paginate(10);
        
        return response()->json([
            'username' => 'sabina',
            'tweets-timeline' => view('__tweets-timeline',[ //sending this part of doc to ajax so it loads it in the div
                'tweets' => $tweets,
                'user' => $user,
                'totalTweets' => $totalTweets,
                'yesterday' => $yesterday 
            ])->render()
        ]);
    }

    //dynamic profile nav link : tweets & replies
    public function withRepliesAjax(User $user) {
        $yesterday = Carbon::now()->subDay(); // calculate if tweet is more than 24 hours old
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        // $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->paginate(10);
        $replies = Reply::where('user_id', $user->id)->latest()->paginate(10);
        return response()->json([
                'with-replies' => view('__with-replies',[ //sending this part of doc to ajax so it loads it in the div
                'tweets' => $replies,
                'user' => $user,
                // 'totalTweets' => $totalTweets,
                'yesterday' => $yesterday
            ])->render()
        ]);
    }

    // if the user refreshed the page above he gets the same, here we show the same doc
    public function withReplies(User $user) {
        $yesterday = Carbon::now()->subDay(); // calculate if tweet is more than 24 hours old
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

