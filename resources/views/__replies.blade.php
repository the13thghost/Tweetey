    <div class="border-gray-300 {{ $loop->last ? '' : 'border-b' }} py-3 hover:bg-gray-blue py-3">
        <div class="flex mb-3">
            <a href="/profile/{{ $reply->tweet->user->username }}" class="flex-shrink-0 w-9 ml-4 mr-2">
                <x-avatar-icon>{{$reply->tweet->user->avatar}}</x-avatar-icon>
                <div class="bg-gray-400 mt-1 enter-h mx-auto h-full" style="width:2px;"></div> <!-- gray line-->
            </a>
            <div class="ml-1 mr-2 w-full calc-h">
                <span class="font-bold">{{$reply->tweet->user->name}}</span>
                <span class="text-gray-600">{{'@' . $reply->tweet->user->username}}</span>
                <span class="text-gray-600">&middot;
                    @if($reply->tweet->created_at < $yesterday) {{ $reply->tweet->created_at->format('M d, Y') }}
                        @elseif($reply->tweet->created_at < $yesterday)
                            {{ $reply->tweet->created_at->format('M d, Y') }} 
                            @else
                            {{ $reply->tweet->created_at->diffForHumans() }} 
                            @endif 
                </span> 
                <div class="word-break">{{$reply->tweet->comment}}</div>
            {{-- <div>{{$reply->originalLikes()}}</div> --}}
                {{-- <div class="flex items-center mt-3">
                    <x-tweet-social-options :tweet="$reply->tweet"></x-tweet-social-options>
                </div> --}}
            @if(!is_null($reply->tweet->retweeted_from))

            <!-- if we replied on a retweet -->
            <div class="original-tweet-tr border border-gray-400 rounded-xlt p-3 mt-2">
                <div class="flex items-center">
                    <img class="rounded-full object-cover mr-2" style="width:20px;height:20px"
                        src="{{$reply->originalTweet()->user->avatar}}">
                    <div class="mr-2 font-bold">{{$reply->originalTweet()->user->name}}</div>
                    <div class="text-gray-600 mr-1">{{'@' . $reply->originalTweet()->user->username}}</div>
                    <div class="text-gray-600">&middot; 
                        @if($reply->originalTweet()->created_at < $yesterday) {{ $reply->originalTweet()->created_at->format('M d, Y') }}
                        @elseif($reply->originalTweet()->created_at < $yesterday)
                            {{ $reply->originalTweet()->created_at->format('M d, Y') }} 
                            @else
                            {{ $reply->originalTweet()->created_at->diffForHumans() }} 
                            @endif
                    </div>
                </div>
                <div class="word-break">{{$reply->originalTweet()->body}}</div>
                <x-images-layout :tweet="$reply->originalTweet()"></x-images-layout>
            </div>
            @else
            <div class="word-break">{{$reply->tweet->body}}</div>
            <x-images-layout :tweet="$reply->tweet"></x-images-layout>
            @endif
            

        </div>
        <x-tweet-options :tweet="$reply->tweet"></x-tweet-options>
    </div>
    
    {{-- auth user info and reply --}}
    <div class="flex mt-2">
        <a href="/profile/{{ $reply->user->username }}" class="flex-shrink-0 w-9 ml-4 mr-2">
            <x-avatar-icon>{{$reply->user->avatar}}</x-avatar-icon>
        </a>
        <div class="ml-1 mr-2 w-full calc-h">
            <span class="font-bold">{{$reply->user->name}}</span>
            <span class="text-gray-600">{{'@' . $reply->user->username}}</span>
            <span class="text-gray-600">&middot;
                @if($reply->created_at < $yesterday) {{ $reply->created_at->format('M d, Y') }}
                    @elseif($reply->created_at < $yesterday)
                        {{ $reply->created_at->format('M d, Y') }} @else
                        {{ $reply->created_at->diffForHumans() }} @endif </span> <div class="word-break">
                        {{$reply->reply}}</div>

        </div>
    </div>
</div> 
