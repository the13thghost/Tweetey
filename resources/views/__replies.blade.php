<div class="{{ $loop->last ? '' : 'border-b-1' }} border-gray-300 py-3 hover:bg-gray-blue">
    <div class="py-3">
        <div class="flex">
            <a href="/profile/{{ $tweet->tweet->user->username }}" class="flex-shrink-0 w-9 ml-4">
                <x-avatar-icon>{{$tweet->tweet->user->avatar}}</x-avatar-icon>
            </a>
            <div class="ml-1 mr-2 w-full">
                <span class="font-bold">{{$tweet->tweet->user->name}}</span>
                <span class="text-gray-600">{{'@' . $tweet->tweet->user->username}}</span>
                <span class="text-gray-600">&middot;
                    @if($tweet->tweet->retweetOrigi()->created_at < $yesterday)
                        {{ $tweet->retweetOrigi()->created_at->format('M d, Y') }} @elseif($tweet->tweet->created_at <
                            $yesterday) {{ $tweet->tweet->created_at->format('M d, Y') }} @else
                            {{ $tweet->tweet->created_at->diffForHumans() }} @endif </span> <div class="word-break">
                            {{$tweet->tweet->comment}}</div>
            @if($tweet->tweet->retweeted_from)
            <div class="original-tweet border border-gray-400 rounded-xlt p-3 mt-2">
                <div class="flex items-center">
                    <img class="rounded-full object-cover mr-2" style="width:20px;height:20px"
                        src="${response['avatar']}">
                    <div class="mr-2 font-bold">${response['name']}</div>
                    <div class="text-gray-600 mr-1">${response['username']}</div>
                    <div class="text-gray-600">&middot; ${response['datetime']}</div>
                </div>
                <div class="word-break">${response['body']}</div>
            </div>
            @endif

        </div>
        <x-tweet-options :tweet="$tweet"></x-tweet-options> 
        {{-- newly added code above --}}
    </div>
</div>
</div>
{{-- check if the tweet is retweeted, if so show another tweet underneath it --}}
