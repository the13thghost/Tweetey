<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Tweet;
use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as ValidationRule;

class ProfileController extends Controller 
{
    public function show(User $user) 
    {
        $totalTweets = Tweet::where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->get();

        return view('profile.show', [ 
            'tweets' => $tweets,
            'user' => $user,
            'totalTweets' => $totalTweets,
            'yesterday' => carbonTime()
        ]);
    }

    public function edit(User $user) 
    {
        return view('profile.edit', compact('user'));
    }

    public function update(User $user) 
    {
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

    public function updateBio(User $user) 
    {
        $validatedAttributes = request()->validate([
            'bio' => ['string', 'max:140', 'min:1', 'required']
        ]);

        $user->update([
            'bio' => $validatedAttributes['bio']
        ]);
    }

    // Dynamic profile nav link : Tweets 
    public function tweetsNav(User $user) 
    {
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->get();
        
        return response()->json([
            'tweets-timeline' => view('__tweets-timeline',[ 
                'tweets' => $tweets,
                'user' => $user,
                'totalTweets' => $totalTweets,
                'yesterday' => carbonTime()
            ])->render()
        ]);
    }

    // Dynamic profile nav link : Replies 
    public function tweetsWithReplies($userParam) 
    {
        $repliesArr = Reply::where('user_id', $userParam)->select('tweet_id', 'id', 'created_at')->get();
        $tweetsOrder = collect(); 

        foreach($repliesArr as $item) {
            $tweet = Tweet::withLikes()->where('tweets.id', $item['tweet_id'])->first();
            $tweet['reply_id'] = $item['id'];
            $tweet['reply_created_at'] = $item['created_at'];
            $tweetsOrder->push($tweet);
        }
        
        // Sort by time of reply
        $tweets = $tweetsOrder->sortByDesc(function($item) {
            return $item->reply_created_at;
        });

        return $tweets;
    }

    public function withRepliesRes(User $user) 
    {
        return response()->json([
            'with-replies' => view('__with-replies',[ 
                'tweets' => $this->tweetsWithReplies($user->id), 
                'user' => $user,
                'yesterday' => carbonTime()
            ])->render()
        ]);
    }

    public function withReplies(User $user) 
    {
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        return view('profile.show',[ 
            'tweets' => $this->tweetsWithReplies($user->id),
            'user' => $user,
            'dynamicReplies' => true,
            'totalTweets' => $totalTweets,
            'yesterday' => carbonTime()
        ]);
    }

    // Dynamic profile nav link : Media
    public function mediaRes(User $user) 
    {
        // Find tweets with images 
        $imagesArr = $user->images->pluck('tweet_id')->unique();
        $tweets = Tweet::withLikes()->whereIn('tweets.id', $imagesArr)->latest()->get();
        return response()->json([
            'media' => view('__media',[ 
                'user' => $user,
                'tweets' => $tweets,
                'yesterday' => carbonTime()
            ])->render()
        ]);
    }

    public function media(User $user) 
    {
        $imagesArr = $user->images->pluck('tweet_id')->unique();
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->whereIn('tweets.id', $imagesArr)->latest()->get();
        return view('profile.show',[ 
            'tweets' => $tweets,
            'user' => $user,
            'totalTweets' => $totalTweets,
            'yesterday' => carbonTime(), 
            'dynamicMedia' => true,
        ]);
    }

    // Dynamic profile nav link : Likes
    public function tweetsWithLikes($userParam) 
    {
        // Get tweets the user has liked > save by tweet_id 
        // > left join with likes table > sum likes and dislikes > order by updated_at from likes table
        $likesArr = $userParam->likes->where('like', 1)->pluck('tweet_id'); 
        $tweets = Tweet::withUpdatedAt()->whereIn('tweets.id', $likesArr)->orderBy('updated_at_likes', 'DESC')->get();
        return $tweets;
    }

    public function likesRes(User $user) 
    {
        return response()->json([
            'likes' => view('__likes',[ 
                'user' => $user,
                'tweets' => $this->tweetsWithLikes($user),
                'yesterday' => carbonTime()
            ])->render()
        ]);
    }

    public function likes(User $user) 
    {
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        return view('profile.show',[ 
            'tweets' => $this->tweetsWithLikes($user),
            'user' => $user,
            'totalTweets' => $totalTweets,
            'yesterday' => carbonTime(),
            'dynamicLikes' => true
        ]);
    }
}
