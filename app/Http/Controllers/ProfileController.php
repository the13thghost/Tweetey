<?php

namespace App\Http\Controllers;

use App\Image;
use App\Reply;
use App\Tweet;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as ValidationRule;

class ProfileController extends Controller 
{
    public function show(User $user) {
        $totalTweets = Tweet::where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->paginate(10);

        return view('profile.show', [ 
            'tweets' => $tweets,
            'user' => $user,
            'totalTweets' => $totalTweets,
            'yesterday' => carbonTime()
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
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->paginate(10);
        
        return response()->json([
            'tweets-timeline' => view('__tweets-timeline',[ 
                'tweets' => $tweets,
                'user' => $user,
                'totalTweets' => $totalTweets,
                'yesterday' => carbonTime()
            ])->render()
        ]);
    }

    //dynamic profile nav link : Tweets & Replies 

    public function tweetsWithReplies($userParam) {
        $repliesArr = Reply::where('user_id', $userParam)->select('tweet_id', 'id', 'created_at')->get();
        $tweetsOrder = collect(); 

        foreach($repliesArr as $item) {
            $tweet = Tweet::withLikes()->where('tweets.id', $item['tweet_id'])->first();
            $tweet['reply_id'] = $item['id'];
            $tweet['reply_created_at'] = $item['created_at'];
            $tweetsOrder->push($tweet);
        }
        
        // sort tweets by time of reply
        $tweets = $tweetsOrder->sortByDesc(function($item) {
            return $item->reply_created_at;
        });

        return $tweets;
    }

    public function withRepliesRes(User $user) {
        
        return response()->json([
            'with-replies' => view('__with-replies',[ 
                'tweets' => $this->tweetsWithReplies($user->id), 
                'user' => $user,
                'yesterday' => carbonTime()
            ])->render()
        ]);
    }

    public function withReplies(User $user) {
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        return view('profile.show',[ 
            'tweets' => $this->tweetsWithReplies($user->id),
            'user' => $user,
            'dynamicReplies' => true,
            'totalTweets' => $totalTweets,
            'yesterday' => carbonTime()
        ]);

    }

    //dynamic profile nav link : media (show users original tweets with media, no retweets)

    public function mediaRes(User $user) {
        // find tweets with images for user
        $imagesArr = $user->images->pluck('tweet_id')->unique();
        $tweets = Tweet::withLikes()->whereIn('tweets.id', $imagesArr)->latest()->paginate(10);
        return response()->json([
            'media' => view('__media',[ 
                'user' => $user,
                'tweets' => $tweets,
                'yesterday' => carbonTime()
            ])->render()
        ]);
    }

    public function media(User $user) {
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->paginate(10);
        return view('profile.show',[ 
            'tweets' => $tweets,
            'user' => $user,
            'totalTweets' => $totalTweets,
            'yesterday' => carbonTime() 
        ]);
    }

    //dynamic profile nav link : likes

    public function likesRes(User $user) {
        $likesArr = $user->likes->where('like', 1)->pluck('tweet_id'); // get tweets the user has liked, save by tweet_id
        // left join with likes table, sum likes and dislike, order by updated at from likes table
        $tweets = Tweet::withUpdatedAt()->whereIn('tweets.id', $likesArr)->orderBy('updated_at_likes', 'DESC')->get();
        return response()->json([
            'likes' => view('__likes',[ 
                'user' => $user,
                'tweets' => $tweets,
                'yesterday' => carbonTime()
            ])->render()
        ]);
    }

    public function likes(User $user) {
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        $tweets = Tweet::withLikes()->where('user_id', $user->id)->latest()->paginate(10);
        return view('profile.show',[ 
            'tweets' => $tweets,
            'user' => $user,
            'totalTweets' => $totalTweets,
            'yesterday' => carbonTime()
        ]);
    }
    
}
