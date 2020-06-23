<div class="{{ $loop->last ? '' : 'border-b-1' }} border-gray-300 py-3 hover:bg-gray-blue">
    <div class="flex">
        <a href="/profile/{{ $tweet->user->username }}" class="flex-shrink-0 ml-4 w-9 mr-2">
            <x-avatar-icon :tweet='$tweet' class="ml-2">
                {{ $tweet->user->avatar }}
            </x-avatar-icon>
        </a>
        <div class="ml-1 mr-2 w-full calc-h">
            <span class="font-bold">
                {{ $tweet->user->name }}
            </span>
            <span class="text-gray-600">
                {{ '@' . $tweet->user->username }}
            </span> 
            {{-- Time of tweet --}}
            <span class="text-gray-600">&middot;
                @if($tweet->created_at < $yesterday)
                    {{ $tweet->created_at->format('M d, Y') }} 
                @else
                {{ $tweet->created_at->diffForHumans() }} 
                @endif 
            </span> 
            <div class="word-break">
                {{ $tweet->body }}
            </div>
            <div class="block items-center">
                <div>
                    <x-images-layout :tweet="$tweet"></x-images-layout>
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
