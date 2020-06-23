<x-app>
    <x-top-bar>
        <div class="flex items-center z-20">
            <div class="p-5 cursor-pointer" style="width:53px">
                <a href="{{ url()->previous() }}">
                    <svg viewBox="0 0 20 20" class="w-4 text-blue-900">
                        <g class="fill-current">
                            <polygon
                                points="3.82842712 9 9.89949494 2.92893219 8.48528137 1.51471863 0 10 0.707106781 10.7071068 8.48528137 18.4852814 9.89949494 17.0710678 3.82842712 11 20 11 20 9 3.82842712 9">
                            </polygon>
                        </g>
                    </svg>
                </a>
            </div>
            <div class="ml-2 bg-white block mb-2">
                <div class="font-bold text-xl">
                    Thread
                </div>
            </div>
        </div>
    </x-top-bar>
    <div class="px-4 pt-4 border-l border-r border-b border-gray-300">
        <div class="flex items-center mb-3">
            <a href="/profile/{{ $tweet->user->username }}" class="flex-shrink-0 w-9 mr-2">
                <x-avatar-icon :tweet='$tweet' class="ml-2">
                    {{ $tweet->user->avatar }}
                </x-avatar-icon>
            </a>
            <span class="">
                <div class="font-bold"><a href="/profile/{{ $tweet->user->username }}">{{ $tweet->user->name }}</a></div>
                <div>{{ '@' . $tweet->user->username }}</div>
            </span>
        </div>
        <div class="text-xl mb-3" style="max-width:620px">
            {{$tweet->body}}
        </div>
        @if($tweet->images->isNotEmpty())
        <div class="mb-2">
            <x-images-layout :tweet="$tweet"></x-images-layout>
        </div>
        @endif
        <div class="flex text-gray-600 mb-2">
            <div class="mr-1">{{$tweet->created_at->format('h:i A')}} &middot;</div>
            <div>{{$tweet->created_at->diffForHumans()}}</div>
        </div>
        <hr>
        <div class="flex py-3 th-in">
            <div class="th-in-load">
            <div class="mr-3 inline"><span class="font-bold">{{$tweet->retweetsNumber()}}</span> Retweets</div>
            <div class="inline"><span class="font-bold">@if(!empty($tweet->likes)){{ $tweet->likes }} @else 0 @endif</span> Likes</div>
        </div>
        </div>
        <hr>
        <div class="fi-so">
        <div class="flex items-center my-5 fi-so-load">
            <x-tweet-social-options :tweet="$tweet"></x-tweet-social-options>
        </div>
    </div>
         
        
        
    </div>
    <section class="mb-6 load-tweets">
        <div class="load-tweets-ajax dynamic-load">
    {{-- replies and retweets--}}
    @forelse ($repliesTweets as $replyTweet)

    <div class="flex py-3 border-l border-r border-b border-gray-300">
    <a href="/profile/{{ $replyTweet->user->username }}" class="flex-shrink-0 w-9 ml-4 mr-2">
        <x-avatar-icon>{{$replyTweet->user->avatar}}</x-avatar-icon>
    </a>
    <div class="ml-1 mr-2 w-full tweet-h">
        <span class="font-bold"><a href="/profile/{{$replyTweet->user->username}}">{{$replyTweet->user->name}}</a></span>
        <span class="text-gray-600">{{'@' . $replyTweet->user->username}}</span>
        <span class="text-gray-600">&middot;
            @if($replyTweet->created_at < $yesterday)
             {{ $replyTweet->created_at->format('M d, Y') }}
            @elseif($replyTweet->created_at < $yesterday)
            {{ $replyTweet->created_at->format('M d, Y') }} 
            @else
            {{ $replyTweet->created_at->diffForHumans() }} 
            @endif </span> <div class="word-break">
            @if(isset($replyTweet->reply))  
            <div class="text-gray-600"> Replying to <span class="text-blue-900"><a href="/profile/{{$tweet->user->username}}">{{ '@' . $tweet->user->username}}</a></span></div>
            <div class="my-1">{{$replyTweet->reply}}</div>
            @else
            <div class="my-1">{{$replyTweet->comment}}</div>
            <div class="flex block items-center mt-3">
                <x-tweet-social-options :tweet="$replyTweet"></x-tweet-social-options>

            </div>

            @endif
        </div>

            </div>
            <x-tweet-options :tweet="$replyTweet" :user="$user"></x-tweet-options>

        </div>
        
        <x-popups :user="$user"></x-popups>

        @empty
            <div class="text-lg text-gray-600 text-center py-3 border-l border-r border-b border-gray-300">Nothing here yet</div>
        @endforelse
    </div>
</section>
</x-app>
