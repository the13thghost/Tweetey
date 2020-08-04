<x-app>
    @include('__publish-panel')  

    <section class="border border-gray-300 mb-6 tweets-1 load-tweets cursor-pointer">
        <div class="tweets-fresh load-tweets-ajax">
            @forelse($tweets as $tweet)
            <div class="{{ $loop->last ? '' : 'border-b-1' }} border-gray-300 py-3 hover:bg-gray-blue thread">
                {{-- check if value exists in retweeted_from > its a retweetet && check if comment is nul > its a tweet without a comment --}}
                @if(!is_null($tweet->retweeted_from) && is_null($tweet->comment)) 
                <div class="flex ml-4 items-center mb-1">
                    <div class="text-right" style="width:44px;padding-left:30px">
                        <svg viewBox="0 0 20 20" class="w-4 text-gray-500">
                            <g class="fill-current">
                                <path
                                    d="M4.99201702,4 C3.8918564,4 3,4.88670635 3,5.99810135 L3,12 L0,12 L4,16 L8,12 L5,12 L5,6 L12,6 L14,4 L4.99201702,4 Z M15,8 L12,8 L16,4 L20,8 L17,8 L17,14.0018986 C17,15.1054196 16.0998238,16 15.007983,16 L6,16 L8,14 L15,14 L15,8 Z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <div class="ml-3 text-sm text-gray-600">
                        @if(current_user()->is($tweet->user))
                        You Retweeted
                        @else
                        {{$tweet->user->name}} Retweeted
                        @endif
                    </div>
                </div>
                @endif
                <div class="flex">
                <a href="/profile/@if(!is_null($tweet->retweeted_from) && is_null($tweet->comment)){{$tweet->retweetOrigi()->user->username}}@else{{ $tweet->user->username }}@endif" class="flex-shrink-0 ml-4 w-9 mr-2">
                        <x-avatar-icon :tweet='$tweet' class="ml-2">
                            @if(!is_null($tweet->retweeted_from) && is_null($tweet->comment))
                            {{$tweet->retweetOrigi()->user->avatar}}
                            @else
                            {{ $tweet->user->avatar }}
                            @endif
                        </x-avatar-icon>
                    </a>
                    <div class="ml-1 mr-2 w-full">
                        <span class="font-bold">
                            @if(!is_null($tweet->retweeted_from) && is_null($tweet->comment))
                            <a href="{{route('profile', ['user' => $tweet->retweetOrigi()->user->username])}}">{{$tweet->retweetOrigi()->user->name}}</a>
                            @else
                        <a href="{{route('profile', ['user' => $tweet->user->username])}}">{{ $tweet->user->name }}</a>
                            @endif
                        </span>
                        <span class="text-gray-600">
                            @if(!is_null($tweet->retweeted_from) && is_null($tweet->comment))
                            {{'@' . $tweet->retweetOrigi()->user->username}}
                            @else
                            {{ '@' . $tweet->user->username }}
                            @endif
                        </span>
                        <span class="text-gray-600">&middot;
                            @if(!is_null($tweet->retweeted_from) && is_null($tweet->comment))
                            @if($tweet->retweetOrigi()->created_at < $yesterday)
                                {{ $tweet->retweetOrigi()->created_at->format('M d, Y') }} 
                            @else
                            {{ $tweet->created_at->diffForHumans() }} 
                            @endif 
                            @elseif($tweet->created_at < $yesterday) 
                                {{ $tweet->created_at->format('M d, Y') }} 
                            @else
                                {{ $tweet->created_at->diffForHumans() }} 
                            @endif 
                        </span> 
                        {{-- A retweet without comment --}}
                        @if(!is_null($tweet->retweeted_from) && is_null($tweet->comment))
                        <div class="word-break">{{ $tweet->retweetOrigi()->body }}</div>
                        {{-- A retweet with comment --}}
                        @elseif(!is_null($tweet->retweeted_from) && !is_null($tweet->comment))
                        <div class="mb-2">{{$tweet->comment}}</div>
                        <div class="border border-gray-400 rounded-xlt p-3">
                            <div class="flex items-center" style="font-size:15px">
                                <div>
                                <a href="{{route('profile', ['user' => $tweet->retweetOrigi()->user->username])}}">
                                    <img class="rounded-full object-cover mr-2" style="width:20px;height:20px"
                                        src="{{ $tweet->retweetOrigi()->user->avatar }}">
                                    </div>
                                </a>
                            <div class="mr-2 font-bold"><a href="{{route('profile', ['user' => $tweet->retweetOrigi()->user->username])}}">{{$tweet->retweetOrigi()->user->name}}</a></div>
                                <div class="text-gray-600 mr-1">{{'@' . $tweet->retweetOrigi()->user->username}}</div>
                                <div class="text-gray-600">&middot;
                                @if($tweet->retweetOrigi()->created_at < $yesterday)
                                {{ $tweet->retweetOrigi()->created_at->format('M d, Y') }} 
                                @else
                                {{ $tweet->retweetOrigi()->created_at->diffForHumans() }} 
                                @endif 
                                </div> 
                            </div>
                            <div>
                            {{ $tweet->retweetOrigi()->body }}
                            @if(!is_null($tweet->retweeted_from) AND !is_null($tweet->comment))
                            @if($tweet->retweetOrigi()->images->isNotEmpty())
                                <x-images-layout :tweet="$tweet->retweetOrigi()"></x-images-layout>
                            @endif
                            @endif
                            </div>
                        </div>
                        @else
                        <div class="word-break">
                            {{ $tweet->body }}
                        </div>
                        @endif
                        <div class="block items-center">
                            <div>
                                @if(!is_null($tweet->retweeted_from) AND is_null($tweet->comment))
                                @if($tweet->retweetOrigi()->images->isNotEmpty())
                                <x-images-layout :tweet="$tweet->retweetOrigi()"></x-images-layout>
                                @endif
                                @else
                                @if($tweet->images->isNotEmpty())
                                <x-images-layout :tweet="$tweet"></x-images-layout>
                                @endif
                                @endif
                            </div>
                            <div class="flex items-center mt-3">
                                <x-tweet-social-options :tweet="$tweet"></x-tweet-social-options>
                            </div>
                        </div>
                    </div>
                    <x-tweet-options :tweet="$tweet"></x-tweet-options> 
                    </div>
                </div>
                <hr class="line">
            @empty
            <div class="text-lg text-center m-3 text-gray-500">No tweets to show for now. Try Explore</div>
            @endforelse
        </div>
    </section>
    <x-popups :user="$user"></x-popups>
    <div class="fixed text-center background text-white bg-green-400 p-4 rounded-lg text-sm notify-published hidden z-50"
        style="bottom: 50px; right: 10px;width:225px">
        Your tweet has been successfully published.
        <div class="absolute cursor-pointer" style="top:6px;right:6px">
            <svg class="remove-notify text-white w-4" viewBox="0 0 20 20">
                <g class="fill-current">
                    <polygon id="Combined-Shape"
                        points="10 8.58578644 2.92893219 1.51471863 1.51471863 2.92893219 8.58578644 10 1.51471863 17.0710678 2.92893219 18.4852814 10 11.4142136 17.0710678 18.4852814 18.4852814 17.0710678 11.4142136 10 18.4852814 2.92893219 17.0710678 1.51471863 10 8.58578644">
                    </polygon>
                </g>
            </svg>
        </div>
    </div>
    {{ $tweets->links() }}
</x-app>
