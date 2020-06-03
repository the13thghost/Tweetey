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
    {{-- {{$tweet}} --}}
    <div class="p-4 border-l border-r border-b border-gray-300">
        <div class="flex items-center mb-3">
            <a href="/profile/{{ $tweet->user->username }}" class="flex-shrink-0 w-9">
                <x-avatar-icon :tweet='$tweet' class="ml-2">

                    {{ $tweet->user->avatar }}
                </x-avatar-icon>
            </a>
            <span class="">
                <div class="font-bold">{{ $tweet->user->name }}</div>
                <div>{{ '@' . $tweet->user->username }}</div>
            </span>
        </div>
        <div class="text-xl mb-3" style="max-width:620px">
            {{$tweet->body}}
        </div>
        <div class="flex text-gray-600 mb-2">
            <div class="mr-1">{{$tweet->created_at->format('h:i A')}} &middot;</div>
            <div>{{$tweet->created_at->diffForHumans()}}</div>
        </div>
        <hr>
        <div class="flex py-3">
            <div class="mr-3"><span class="font-bold">{{$tweet->retweetsNumber()}}</span> Retweets</div>
        <div><span class="font-bold">{{ $tweet->likes()->count() }}</span> Likes</div>
        </div>
        <hr>
        <div class="flex items-center mt-3">

            <x-like-btns :tweet="$tweet"></x-like-btns>
             {{-- comment --}}
            <div class="w-1/4 text-center ">
            <a href="/tweet/{{$tweet->id}}">
                <svg viewBox="0 0 20 20" class="w-5 text-gray-500 cursor-pointer">
                    <g class="fill-current">
                        <path
                            d="M14,11 L8.00585866,11 C6.89706013,11 6,10.1081436 6,9.00798298 L6,1.99201702 C6,0.900176167 6.89805351,0 8.00585866,0 L17.9941413,0 C19.1029399,0 20,0.891856397 20,1.99201702 L20,9.00798298 C20,10.0998238 19.1019465,11 17.9941413,11 L17,11 L17,14 L14,11 Z M14,13 L14,15.007983 C14,16.1081436 13.1029399,17 11.9941413,17 L6,17 L3,20 L3,17 L2.00585866,17 C0.898053512,17 0,16.0998238 0,15.007983 L0,7.99201702 C0,6.8918564 0.897060126,6 2.00585866,6 L4,6 L4,8.99349548 C4,11.2060545 5.78916089,13 7.99620271,13 L14,13 Z"
                            id="Combined-Shape"></path>
                    </g>
                </svg>
             </a>
            </div>
            {{-- retweet in ajax.js if it has been retweeted by you : color green #30c674 else stay gray --}}
            <div class="w-1/4 text-center relative ">
                <svg viewBox="0 0 20 20" class="w-5  
                    {{-- if the origigi tweet was retweeted by auth user(viewing origigi tweet) --}}
                    @if((current_user()->retweeted($tweet) AND current_user()->retweetedComment($tweet)) OR $tweet->retweetsFromAuthUser() OR current_user()->retweetedOri($tweet))
                    text-green-600
                    @else
                    text-gray-500
                    @endif
                    cursor-pointer retweet-click">
                    <g class="fill-current">
                        <path
                            d="M4.99201702,4 C3.8918564,4 3,4.88670635 3,5.99810135 L3,12 L0,12 L4,16 L8,12 L5,12 L5,6 L12,6 L14,4 L4.99201702,4 Z M15,8 L12,8 L16,4 L20,8 L17,8 L17,14.0018986 C17,15.1054196 16.0998238,16 15.007983,16 L6,16 L8,14 L15,14 L15,8 Z"
                            id="Combined-Shape"></path>
                    </g>
                </svg>
                <div class="hidden absolute z-10 h-8 w-8 bg-white shadow modal2"
                    style="top:4px;right:78px; width:180px; height:auto">
                    <ul class="text-sm text-gray-800 p-2">
                        @if((current_user()->retweeted($tweet) AND current_user()->retweetedComment($tweet)) OR $tweet->retweetsFromAuthUser() OR current_user()->retweetedOri($tweet))
                        <li data-id="{{ $tweet->id }}" class="border-b mb-1 pb-1 cursor-pointer unretweet">
                            Unretweet
                        </li> 
                        @else
                        <li data-id="{{ $tweet->id }}" class="border-b mb-1 pb-1 cursor-pointer retweet">
                            Retweet
                        </li>
                        @endif 
                        @if(current_user()->retweetedComment($tweet))
                        <li data-id="{{ $tweet->id }}" class="cursor-pointer delete-comment">
                         Delete comment
                     </li>
                        @elseif(!current_user()->retweetedCommentOri($tweet))
                        <li data-id="{{ $tweet->id }}" class="cursor-pointer retweet-comment open-popup">
                         Retweet with comment
                     </li>
                                               
                        @endif
                    </ul>
                </div>
                {{-- show number of retweets per tweet --}}
                <div class="items-center absolute text-sm text-gray-500" style="top:0;left:26px">
                    {{ $tweet->retweetsNumber() ? $tweet->retweetsNumber() : '' }}
                </div>
            </div>
        </div>
    </div>



</x-app>
