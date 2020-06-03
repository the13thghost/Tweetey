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
use Illuminate\Http\Request;

class TweetController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // show all tweets from the auth user for now in /tweets
    public function index() 
    {
        //$tweets = auth()->user()->tweets; // i wanna order them by latest so it needs to be a db query
        //$tweets = Tweet::where('user_id', auth()->user()->id)->latest()->get();

        return view('tweets.index', [
            'tweets' => auth()->user()->timeline()
        ]);
    }

    public function show(Tweet $tweet) {
        //show original tweet and its comments

        if(is_null($tweet->retweeted_from)) {
            $tweet = $tweet;
        } else {
            $tweet = Tweet::where('id', $tweet->retweeted_from)->first();
        }
        return view('tweets.show', compact('tweet'));
    }

    public function store() {

        // dd(request()->all());
        
        $attributes = request()->validate([
            'body' => ['required', 'min:1', 'max:255'],
            'image[]' => ['file', 'mimes:jpeg,png,jpg', 'size:1024']
        ]);


        $tweet = Tweet::create([ // save the current tweet
            'user_id' => auth()->user()->id,
            'body' => $attributes['body']
        ]);

       $tweet_id = $tweet->id; // get the currents tweet id which is in the DB
            // dump(request()->kall());

        if(request('image')) { // for each image save a record in db
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
        //check if it was already retweeted before like if i retweeted from zola with a comment
        // and now i want to retweet the tweet above, it sohuld show that i retweeted from zola like a 
        //normal retweet- check: retweeted_from

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

        //check if retweeted_from is null or has value (if its null->its an origigi)
       // $tweet = Tweet::where('id', $tweet->id)->first(); we did that up in ()
       // if value is null its origigi, comment is percation

        if($tweet->retweeted_from == null && $tweet->comment == null) { 

            //delete all retweets associated with it (where retweeted_from eqauls its id) and are my retweets
            // you dont want to unretweet other peoples retweets of this post only yours
            // and you only want to delete your retweet without a comment, which you can only have 1
            //so there is no loop ----------- below comments are useless
            // to delete every loop, you need to iterate over each one with foreach method
            // you keep the original tweet

            // find my retweet (only mine), by id, by my user id and that the comment is empty
            $deleteRetweet = Tweet::where('retweeted_from', $tweet->id)->where('user_id', auth()->id())->where('comment', null);
            $deleteRetweet->delete();

            //check for associated images
            // $imagesDelete = Image::where('tweet_id', $tweet->id)->get();
            // if($imagesDelete) {
            //     foreach($imagesDelete as $delete) {
            //         $delete->delete();
            // }
            
            // }

            // now delete my retweet from retweets table, search by origigi tweet id, and my auth id
            Retweet::where('tweet_id', $tweet->id)->where('user_id', auth()->id())->where('comment', 0)->delete();
            

        } else {
        // the tweet is the retweet with or without comment
        //delete retweet record from tweet table by tweet_id (which is the retweets id, this is normal delete)
        //and and from retweets delete where auth id is equal mine and the tweet_id is the origigi tweet id

        if(is_null($tweet->comment)) {
            Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 0)->where('created_at', $tweet->created_at)->delete();
        } elseif(!is_null($tweet->comment)) {
            // the retweet was with a comment so only delete that
            Retweet::where('tweet_id', $tweet->retweeted_from)->where('user_id', auth()->id())->where('comment', 1)->where('created_at', $tweet->created_at)->delete();
        }
        $tweet->delete();
        

        
        // if tweet was normal retweet
        
        // i need the originals tweet id so $tweet->retweeted_from
        
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
        if($tweet->retweeted_from == null && $tweet->comment == null) { //original 
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

        // dd(request()->all());
        if(!is_null($tweet->retweeted_from)) {
            Tweet::create([
                'user_id' => auth()->id(),
                'body' => $tweet->body,
                'retweeted_from' => $tweet->retweeted_from,
                'comment' => request('body')
            ]);
            //create record in Retweets
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
    
            //create record in Retweets
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
