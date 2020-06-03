<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware('auth')->group(function() {
    
    Route::get('/tweets', 'TweetController@index')->name('home');
    Route::post('/tweets', 'TweetController@store');
    Route::post('/retweets/{tweet}', 'TweetController@retweet');
    Route::get('/tweet/{tweet}', 'TweetController@show');

    Route::post('/retweets/{tweet}/getTweet', 'TweetController@getTweet'); // getting the tweet object of clicked tweet
    Route::post('/retweets/{tweet}/comment', 'TweetController@retweetComment');

    //updatebio
    Route::put('/profile/{user}/bio', 'ProfileController@updateBio');
    //post a reply
    Route::post('/replies/{tweet}', 'ReplyController@store');


// dynamic nav

    Route::get('/profile/{user:username}/with-replies-ajax', 'ProfileController@withRepliesAjax');
    Route::get('/profile/{user:username}/with-replies', 'ProfileController@withReplies');

    Route::get('/profile/{user:username}/tweets', 'ProfileController@tweetsNav');


//autocomplete rough
    Route::get('search', 'SearchController@index')->name('search');
    Route::get('autocomplete', 'SearchController@autocomplete')->name('autocomplete');


    Route::delete('/unretweets/{tweet}', 'TweetController@unretweet');
    Route::post('/profile/{user:username}/follow', 'FollowsController');
    Route::get('/profile/{user:username}', 'ProfileController@show')->name('profile');
    Route::patch('/profile/{user:username}', 'ProfileController@update');
    Route::post('/tweets/{tweet:id}/like', 'TweetsLikeController@storeLike');
    Route::post('/tweets/{tweet:id}/dislike', 'TweetsLikeController@storeDislike');
    Route::delete('/tweets/{tweet:id}/delete', 'TweetController@destroy');

    Route::delete('/tweets/comment/{tweet:id}/delete', 'TweetController@deleteComment'); //delete a comment

    Route::get('/profile/{user:username}/edit', 'ProfileController@edit')->middleware('can:edit,user')->name('edit-profile');
    
});
