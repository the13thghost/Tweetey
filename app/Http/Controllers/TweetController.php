<?php

namespace App\Http\Controllers;

use App\Events\ProductPurchased;
use App\Image;
use App\Notifications\TweetPublished;
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
        $yesterday = Carbon::now()->subDay(); // calculate if tweet is more than 24 hours old
        $totalTweets = Tweet::withLikes()->where('user_id', $user->id)->count();
        return view('tweets.index', [
            'tweets' => auth()->user()->timeline(),
            'yesterday' => $yesterday,
            'user' => $user,
            'totalTweets' => $totalTweets
        ]);
    }

        
        
    public function show(Tweet $tweet) {
        if(is_null($tweet->retweeted_from)) {
            $tweet = $tweet;
        } else {
            $tweet = Tweet::where('id', $tweet->retweeted_from)->first();
        }
        return view('tweets.show', compact('tweet'));
    }

    public function store() {

        $attributes = request()->validate([
            'image' => [function($attribute, $value, $fail) {
                if(count($value) > 4) {
                    return $fail('Max 4 files accepted!');
                }
            }],
            'image[]' => ['file', 'mimes:jpeg,png,jpg', 'size:10'],
            'body' => ['required', 'min:1', 'max:255']
        ]);

        $tweet = Tweet::create([ 
            'user_id' => auth()->id(),
            'body' => $attributes['body']
        ]);

       $tweet_id = $tweet->id; // get the currents tweet id from DB

        if(request('image')) { // for each image save a record in DB
            foreach(request('image') as $image) {
                // dump($image);
                Image::create([
                    'user_id' => auth()->id(),
                    'tweet_id' => $tweet_id,
                    'image' => $image->store('uploads')
                ]);
            }
        } 

        return back();
    }

    public function destroy(Tweet $tweet) {
        if($tweet->retweeted_from == null && $tweet->comment == null) { 
            // $tweet->delete();
            $imagesDelete = Image::where('tweet_id', $tweet->id)->get();

            //set everys retweet retweeted_from to something like 0 so we know it was deleted
            $changeRetweets = Tweet::where('retweeted_from', $tweet->id)->get();
            foreach($changeRetweets as $change) {
                $change->retweeted_from = 13;
            }

            if($imagesDelete) {
                foreach($imagesDelete as $delete) {
                    $delete->delete();
                }
            }
        }
    }

    // } else {
    // if(is_null($tweet->comment)) {
    //     Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 0)->where('created_at', $tweet->created_at)->delete();
    // } elseif(!is_null($tweet->comment)) {
    //     // the retweet was with a comment so only delete that
    //     Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 1)->where('created_at', $tweet->created_at)->delete();
    // }
    // $tweet->delete();
    
    // }
    

    public function retweet(Tweet $tweet) { 
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

    public function unretweet(Tweet $tweet) {
        if($tweet->retweeted_from == null && $tweet->comment == null) { // original tweet
            // delete all retweets associated with it (where retweeted_from equals its id) and are my retweets
            // keep the original tweet

            // find auth user tweet
            $deleteRetweet = Tweet::where('retweeted_from', $tweet->id)->where('user_id', auth()->id())->where('comment', null);
            $deleteRetweet->delete();

            // check for associated images
            // $imagesDelete = Image::where('tweet_id', $tweet->id)->get();
            // if($imagesDelete) {
            //     foreach($imagesDelete as $delete) {
            //         $delete->delete();
            // }
            
            // }

            // delete the retweet from retweets table
            Retweet::where('tweet_id', $tweet->id)->where('user_id', auth()->id())->where('comment', 0)->delete();
            

        } else {
        // the tweet is a retweet with or without a comment
        // delete retweet record from tweets table by tweet_id and from retweets table
            if(is_null($tweet->comment)) {
                Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 0)->where('created_at', $tweet->created_at)->delete();
            } elseif(!is_null($tweet->comment)) {
                Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 1)->where('created_at', $tweet->created_at)->delete();
            }
            $tweet->delete();        
        }
    }

    public function getTweet() {
        //get the time the tweet was created and convert accordingly
        $yesterday = Carbon::now()->subDay(); // calculate if tweet is more than 24 hours old

        $tweet = Tweet::where('id', request('id'))->first();
        if($tweet->created_at < $yesterday) {
            $datetime = $tweet->created_at->format('M d, Y');
        } else {
            $datetime = $tweet->created_at->diffForHumans();
        }
        if($tweet->retweeted_from == null && $tweet->comment == null) { // original tweet
            return response()->json([
                'id' => $tweet->id,
                'body' => $tweet->body,
                'name' => $tweet->user->name,
                'avatar' => $tweet->user->avatar,
                'username' => $tweet->user->username,
                'datetime' => $datetime

            ]);
        } elseif(!is_null($tweet->retweeted_from)) { // a retweet with a comment
            $tweet1 = Tweet::where('id', $tweet->retweeted_from)->firstOrFail();
            if($tweet1->created_at < $yesterday) {
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
                'reposted_datetime' => $datetime
            ]);
        }
    }

    public function retweetComment(Tweet $tweet) {

        //validation (if comment area is empty > can't retweet)

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

    public function deleteComment(Tweet $twet) {

    }

    // public function search(Request $request) {
    //     $data = Tweet::select("body")
    //             ->where("body","LIKE","%{$request->input('query')}%")
    //             ->get();
   
    //     $data1 = array();
    //     foreach ($data as $dat)
    //         {
    //             $data1[] = $dat->body;
    //         }
    //     return response()->json($data1);
    // }
}
