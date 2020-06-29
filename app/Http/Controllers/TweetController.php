<?php

namespace App\Http\Controllers;

use App\Image;
use App\Reply;
use App\Retweet;
use App\Tweet;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class TweetController extends Controller
{
    public function index(User $user) 
    { 
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        return view('tweets.index', [
            'tweets' => auth()->user()->timeline(),
            'yesterday' => carbonTime(),
            'user' => $user,
            'totalTweets' => $totalTweets
        ]);
    }

    // Thread
    public function show(Tweet $tweet, User $user) 
    {
        if(is_null($tweet->retweeted_from)) {
            $tweet = Tweet::withLikes()->where('id', $tweet->id)->first();;
        } else {
            $tweet = Tweet::withLikes()->where('id', $tweet->retweeted_from)->first();
        }

        $replies = Reply::where('tweet_id', $tweet->id)->get();
        $retweets = Tweet::withLikes()->where('retweeted_from', $tweet->id)->whereNotNull('comment')->get();
        $repliesTweets = $replies->toBase()->merge($retweets)->sortByDesc('created_at'); // Combine the 2 collections

        return view('tweets.show', [
            'tweet' => $tweet,
            'repliesTweets' => $repliesTweets,
            'user' => $user,
            'yesterday' => carbonTime()
        ]);
    }

    public function store() 
    {
        $attributes = request()->validate([
            'image' => [function($attribute, $value, $fail) {
                if(count($value) > 4) {
                    return $fail('Max 4 files accepted!');
                }
            }],
            'image[]' => ['file', 'mimes:jpeg,png,jpg', 'size:10240'],
            'body' => ['required', 'min:1', 'max:255']
        ]);

        $tweet = Tweet::create([ 
            'user_id' => auth()->id(),
            'body' => $attributes['body']
        ]);

       $tweet_id = $tweet->id; // Get the current tweets id from DB

        if(request('image')) { // For each image save a record in DB
            foreach(request('image') as $image) {
                Image::create([
                    'user_id' => auth()->id(),
                    'tweet_id' => $tweet_id,
                    'image' => $image->store('uploads')
                ]);
            }
        } 

        return back();
    }

    // EDIT
    // public function destroy(Tweet $tweet) {
    //     if($tweet->retweeted_from == null && $tweet->comment == null) { 
    //         // $tweet->delete();
    //         $imagesDelete = Image::where('tweet_id', $tweet->id)->get();

    //         // Set every retweets retweeted_from to something like 0 so we know it was deleted
    //         $changeRetweets = Tweet::where('retweeted_from', $tweet->id)->get();
    //         foreach($changeRetweets as $change) {
    //             $change->retweeted_from = 13;
    //         }

    //         if($imagesDelete) {
    //             foreach($imagesDelete as $delete) {
    //                 $delete->delete();
    //             }
    //         }
    //     }
    //     else {
    //         if(is_null($tweet->comment)) {
    //             Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 0)->where('created_at', $tweet->created_at)->delete();
    //         } elseif(!is_null($tweet->comment)) {
    //             // the retweet was with a comment so only delete that
    //             Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 1)->where('created_at', $tweet->created_at)->delete();
    //         }
    //         $tweet->delete();
    //         }
    // }
    

    public function retweet(Tweet $tweet) 
    { 
        if(!is_null($tweet->retweeted_from)) {
            Tweet::create([
                'user_id' => auth()->id(),
                'body' => $tweet->body,
                'retweeted_from' => $tweet->retweeted_from
            ]);

            Retweet::create([
                'tweet_id' => $tweet->retweeted_from,
                'user_id' => auth()->id()
                 ]);

        } else {
            $retweet_id = Retweet::create([
                'tweet_id' => $tweet->id,
                'user_id' => auth()->id()
                 ]);
        
                Tweet::create([
                    'user_id' => auth()->id(),
                    'body' => $tweet->body,
                    'retweeted_from' => $retweet_id->tweet_id
                ]);
        }       
    }

    public function unretweet(Tweet $tweet) 
    {
        // Delete all retweets associated with it (where retweeted_from equals its id) && are my retweets > keep the original tweet
        if($tweet->retweeted_from == null && $tweet->comment == null) { // original tweet
            $deleteRetweet = Tweet::where('retweeted_from', $tweet->id)->where('user_id', auth()->id())->where('comment', null);
            $deleteRetweet->delete();

            // check for associated images
            // $imagesDelete = Image::where('tweet_id', $tweet->id)->get();
            // if($imagesDelete) {
            //     foreach($imagesDelete as $delete) {
            //         $delete->delete();
            // }
            
            // }

            // Delete the retweet from retweets table
            Retweet::where('tweet_id', $tweet->id)->where('user_id', auth()->id())->where('comment', 0)->delete();
    
        } else {
        // The tweet is a retweet with or without a comment > delete retweet record from tweets table by tweet_id and from retweets table
            if(is_null($tweet->comment)) {
                Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 0)->where('created_at', $tweet->created_at)->delete();
            } elseif(!is_null($tweet->comment)) {
                Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 1)->where('created_at', $tweet->created_at)->delete();
            }
            $tweet->delete();        
        }
    }

    // Check if images collection is empty > if so no need to render a view 
    public function imgEmpty($imgParam, $varParam) 
    {        
        if($imgParam->isEmpty()) {
            return 0;
        } else {
            return view('/components/images-layout-sm', [
                'tweet' => $varParam
            ])->render();
        }
    }

    public function getTweet() {
        $tweet = Tweet::where('id', request('id'))->first();
        $images = Image::where('tweet_id', request('id'))->get();

        if($tweet->created_at < carbonTime()) {
            $datetime = $tweet->created_at->format('M d, Y');
        } else {
            $datetime = $tweet->created_at->diffForHumans();
        }
        
        // Original tweet
        if($tweet->retweeted_from == null && $tweet->comment == null) { 
            return response()->json([
                'id' => $tweet->id,
                'body' => $tweet->body,
                'name' => $tweet->user->name,
                'avatar' => $tweet->user->avatar,
                'username' => $tweet->user->username,
                'datetime' => $datetime,
                'images' => $this->imgEmpty($images, $tweet)
            ]);

        // A retweet with a comment
        } elseif(!is_null($tweet->retweeted_from)) { 
            
            $tweet1 = Tweet::where('id', $tweet->retweeted_from)->first();
            $images1 = Image::where('tweet_id', $tweet->retweeted_from)->get();

            if($tweet1->created_at < carbonTime()) {
                $datetime1 = $tweet1->created_at->format('M d, Y');
            } else {
                $datetime1 = $tweet1->created_at->diffForHumans();
            }
            return response()->json([
                'id' => $tweet->id,
                'body' => $tweet1->body,
                'name' => $tweet1->user->name,
                'avatar' => $tweet1->user->avatar,
                'username' => $tweet1->user->username,
                'datetime' => $datetime1,
                'comment' => $tweet->comment,
                'reposter_name' =>$tweet->user->name,
                'reposter_avatar' => $tweet->user->avatar,
                'reposter_username' => $tweet->user->username,
                'reposted_datetime' => $datetime,
                'images' => $this->imgEmpty($images1, $tweet1)
            ]);
        }
    }

    public function retweetComment(Tweet $tweet) 
    {
        // Validation > if comment area is empty > can't retweet
        $attributes = request()->validate([
            'body' => ['required', 'min:1', 'max:255']
        ]);


        if(!is_null($tweet->retweeted_from)) {
            Tweet::create([
                'user_id' => auth()->id(),
                'body' => $tweet->body,
                'retweeted_from' => $tweet->retweeted_from,
                'comment' => request('body')
            ]);

            Retweet::create([
                'tweet_id' => $tweet->retweeted_from,
                'user_id' => auth()->id(),
                'comment' => 1
        ]);

        } else {
            Tweet::create([
                'user_id' => auth()->id(),
                'body' => $tweet->body,
                'retweeted_from' => $tweet->id,
                'comment' => request('body')
            ]);
    
            Retweet::create([
                'tweet_id' => $tweet->id,
                'user_id' => auth()->id(),
                'comment' => 1
            ]);
        }
    }

    // public function deleteComment(Tweet $twet) 
    // {

    // }
}
