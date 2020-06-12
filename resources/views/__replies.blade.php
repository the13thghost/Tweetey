    <div class="border-gray-300 {{ $loop->last ? '' : 'border-b' }} py-3 hover:bg-gray-blue py-3">
        <div class="flex mb-3"> 
            <a href="/profile/{{ $tweet->user->username }}" class="flex-shrink-0 w-9 ml-4 mr-2">
                <x-avatar-icon>{{$tweet->user->avatar}}</x-avatar-icon>
                <div class="bg-gray-400 mt-1 enter-h mx-auto h-full" style="width:2px;"></div> <!-- gray line-->
            </a>
            <div class="ml-1 mr-2 w-full calc-h">
                <span class="font-bold">{{$tweet->user->name}}</span>
                <span class="text-gray-600">{{'@' . $tweet->user->username}}</span>
                <span class="text-gray-600">&middot;
                    @if($tweet->created_at < $yesterday) {{ $tweet->created_at->format('M d, Y') }}
                        @elseif($tweet->created_at < $yesterday)
                            {{ $tweet->created_at->format('M d, Y') }} 
                            @else
                            {{ $tweet->created_at->diffForHumans() }} 
                            @endif 
                </span> 
                <div class="word-break">{{$tweet->comment}}</div>
            {{-- <div>{{$reply->originalLikes()}}</div> --}}
                
            @if(!is_null($tweet->retweeted_from))

            <!-- if we replied on a retweet -->
            <div class="original-tweet-tr border border-gray-400 rounded-xlt p-3 mt-2">
                <div class="flex items-center">
                    <img class="rounded-full object-cover mr-2" style="width:20px;height:20px"
                        src="{{$tweet->retweetOrigi()->user->avatar}}">
                    <div class="mr-2 font-bold">{{$tweet->retweetOrigi()->user->name}}</div>
                    <div class="text-gray-600 mr-1">{{'@' . $tweet->retweetOrigi()->user->username}}</div>
                    <div class="text-gray-600">&middot; 
                        @if($tweet->retweetOrigi()->created_at < $yesterday) {{ $tweet->retweetOrigi()->created_at->format('M d, Y') }}
                        @elseif($tweet->retweetOrigi()->created_at < $yesterday)
                            {{ $tweet->retweetOrigi()->created_at->format('M d, Y') }} 
                            @else
                            {{ $tweet->retweetOrigi()->created_at->diffForHumans() }} 
                            @endif
                    </div>
                </div>
                <div class="word-break">{{$tweet->retweetOrigi()->body}}</div>
                <x-images-layout :tweet="$tweet->retweetOrigi()"></x-images-layout>
            </div>
            @else
            <div class="word-break">{{$tweet->body}}</div>
            <x-images-layout :tweet="$tweet"></x-images-layout>
            @endif
            <div class="flex items-center mt-3">
                <x-tweet-social-options :tweet="$tweet"></x-tweet-social-options>
            </div>
            

        </div>
        <x-tweet-options :tweet="$tweet"></x-tweet-options>
    </div>
    
    {{-- auth user info and reply --}}
    <div class="flex mt-2">
        <a href="/profile/{{ $tweet->user->username }}" class="flex-shrink-0 w-9 ml-4 mr-2">
            <x-avatar-icon>{{$tweet->user->avatar}}</x-avatar-icon>
        </a>
        <div class="ml-1 mr-2 w-fultweet-h">
            <span class="font-bold">{{$tweet->user->name}}</span>
            <span class="text-gray-600">{{'@' . $tweet->user->username}}</span>
            <span class="text-gray-600">&middot;
                @if($tweet->created_at < $yesterday) {{ $tweet->created_at->format('M d, Y') }}
                    @elseif($tweet->created_at < $yesterday)
                        {{ $tweet->created_at->format('M d, Y') }} @else
                        {{ $tweet->created_at->diffForHumans() }} @endif </span> <div class="word-break">
                        {{$tweet->replies[0]->reply}}</div>

        </div>
    </div> 
</div> 
