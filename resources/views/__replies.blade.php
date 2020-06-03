<div class="{{ $loop->last ? '' : 'border-b-1' }} border-gray-300 py-3 hover:bg-gray-blue">
<div class="py-3">
    <div class="flex">
        {{-- $tweet is actually $reply, using $tweet because of reuse of component --}}
            <a href="/profile/{{ $tweet->tweet->user->username }}" class="flex-shrink-0 w-9 ml-4">
                <x-avatar-icon>{{$tweet->tweet->user->avatar}}</x-avatar-icon>
            </a>
        <div class="ml-1 mr-2 w-full">
            <span class="font-bold">{{$tweet->tweet->user->name}}</span>
            <span class="text-gray-600">{{'@' . $tweet->tweet->user->username}}</span>
            <span class="text-gray-600">&middot; 
                @if($tweet->tweet->retweetOrigi()->created_at < $yesterday)
                    {{ $tweet->retweetOrigi()->created_at->format('M d, Y') }} 
                @elseif($tweet->tweet->created_at < $yesterday) 
                    {{ $tweet->tweet->created_at->format('M d, Y') }} 
                @else
                    {{ $tweet->tweet->created_at->diffForHumans() }} 
                @endif
            </span>
        <div class="word-break">{{$tweet->tweet->comment}}</div>
        @if($tweet->tweet->retweeted_from)
        <div class="original-tweet border border-gray-400 rounded-xlt p-3 mt-2">
            <div class="flex items-center">
                <img class="rounded-full object-cover mr-2" style="width:20px;height:20px" src="${response['avatar']}">
                <div class="mr-2 font-bold">${response['name']}</div>
                <div class="text-gray-600 mr-1">${response['username']}</div>
                <div class="text-gray-600">&middot; ${response['datetime']}</div>
            </div>
            <div class="word-break">${response['body']}</div></div>
        @endif

        </div>
        <div class="w-7 mr-2 text-gray-500 relative">
            <svg viewBox="0 0 20 20" class="w-5 dots hover:text-gray-800 cursor-pointer">
                <g class="fill-current">
                    <path d="M10,12 C11.1045695,12 12,11.1045695 12,10 C12,8.8954305 11.1045695,8 10,8 C8.8954305,8 8,8.8954305 8,10 C8,11.1045695 8.8954305,12 10,12 Z M10,6 C11.1045695,6 12,5.1045695 12,4 C12,2.8954305 11.1045695,2 10,2 C8.8954305,2 8,2.8954305 8,4 C8,5.1045695 8.8954305,6 10,6 Z M10,18 C11.1045695,18 12,17.1045695 12,16 C12,14.8954305 11.1045695,14 10,14 C8.8954305,14 8,14.8954305 8,16 C8,17.1045695 8.8954305,18 10,18 Z">
                    </path>
                </g>
            </svg>
            <div class="hidden absolute z-10 h-8 w-8 bg-white shadow modal1" style="top:0;right:20px; width:145px; height:82px">
                <ul class="text-xs text-gray-800 p-2">
                    <li class="border-b mb-1 pb-1">
                        Pin to timeline
                    </li>
                    <li class="mb-1">
                        Hide from timeline
                    </li>
                    <li class="delete-post cursor-pointer" data-id="608">Delete tweet</li>
                </ul>
            </div>
        </div>
        </div>
</div>
</div>
{{-- check if the tweet is retweeted, if so show another tweet underneath it --}}