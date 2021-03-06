<div class="border-gray-300 {{ $loop->last ? '' : 'border-b' }} py-3 hover:bg-gray-blue py-3 thread cursor-pointer">
    <div class="flex mb-3"> 
        <a href="{{route('profile', ['user' => $tweet->user->username])}}" class="flex-shrink-0 w-9 ml-4 mr-2">
                <x-avatar-icon>{{$tweet->user->avatar}}</x-avatar-icon>
                <div class="bg-gray-400 mt-1 mx-auto" style="height:calc(100% - 40px);width:2px;"></div> 
        </a>
        <div class="ml-1 mr-2 w-full">
            <span class="font-bold"><a href="{{route('profile', ['user' => $tweet->user->username])}}">{{$tweet->user->name}}</a></span>
            <span class="text-gray-600">{{'@' . $tweet->user->username}}</span>
            <span class="text-gray-600">&middot;
                @if($tweet->created_at < $yesterday) 
                    {{ $tweet->created_at->format('M d, Y') }}
                @elseif($tweet->created_at < $yesterday)
                    {{ $tweet->created_at->format('M d, Y') }} 
                @else
                    {{ $tweet->created_at->diffForHumans() }} 
                @endif 
            </span> 
            <div class="word-break">{{$tweet->comment}}</div>
            {{-- If we replied on a tweet --}}
            @if(!is_null($tweet->retweeted_from))
                <div class="original-tweet-tr border border-gray-400 rounded-xlt p-3 mt-2">
                    <div class="flex items-center">
                        <img class="rounded-full object-cover mr-2" style="width:20px;height:20px" src="{{$tweet->retweetOrigi()->user->avatar}}">
                        <div class="mr-2 font-bold">{{$tweet->retweetOrigi()->user->name}}</div>
                        <div class="text-gray-600 mr-1">{{'@' . $tweet->retweetOrigi()->user->username}}</div>
                        <div class="text-gray-600">&middot; 
                            @if($tweet->retweetOrigi()->created_at < $yesterday) 
                                {{ $tweet->retweetOrigi()->created_at->format('M d, Y') }}
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
    {{-- Auth user info and reply --}}
    <div class="flex mt-2">
        <a href="{{route('profile', ['user' => $user->username])}}" class="flex-shrink-0 w-9 ml-4 mr-2">
            <x-avatar-icon>{{$user->avatar}}</x-avatar-icon>
        </a>
        <div class="ml-1 mr-2 w-fultweet-h">
            <span class="font-bold"><a href="{{route('profile', ['user' => $user->username])}}">{{$user->name}}</a></span>
            <span class="text-gray-600">{{'@' . $user->username}}</span>
            <span class="text-gray-600">&middot;
                @if($tweet->reply_created_at < $yesterday) 
                    {{ $tweet->reply_created_at->format('M d, Y') }}
                @elseif($tweet->reply_created_at < $yesterday)
                    {{ $tweet->reply_created_at->format('M d, Y') }}
                @else
                    {{ $tweet->reply_created_at->diffForHumans() }} 
                @endif 
            </span> 
            <div class="word-break">{{$tweet->replies->where('id', $tweet->reply_id)->first()->reply}}</div>
        </div>
    </div> 
</div> 
