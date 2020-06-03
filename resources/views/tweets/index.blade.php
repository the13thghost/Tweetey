<x-app>
    @include('__publish-panel')

    <section class="border border-gray-300 mb-6 tweets-1 load-tweets">
        <div class="tweets-fresh load-tweets-ajax">
            @forelse($tweets as $tweet)
            <div class="flex {{ $loop->last ? '' : 'border-b-1' }} border-gray-300 py-3">
                <!-- this willl be foreach -->
                <a href="/profile/{{ $tweet->user->username }}" class="flex-shrink-0 ml-2 w-9">
                    <x-avatar-icon :tweet='$tweet' class="ml-2">{{ $tweet->user->avatar }}</x-avatar-icon>
                </a>
                <div class="ml-3 mr-2 w-full">

                    <span class="font-bold">{{ $tweet->user->name }}</span>
                        <span class="text-gray-600">{{ '@' . $tweet->user->username }}</span>
                        <span class="text-gray-600">&middot; {{ $tweet->user->created_at->diffForHumans() }}</span>
                        <div>{{ $tweet->body }}</div>
                    <div class="block mt-3 items-center">
                        <div>
                            @if($tweet->images->isNotEmpty())
                            <div class="flex flex-wrap">
                                @foreach ($tweet->images as $image)
                            <img src="{{asset('/storage/' . $image->image)}}" alt="tweet image" 
                            class="{{$loop->last ? '' : 'mr-2'}} object-cover rounded-xl" style="width:553px;height:280px">
                                @endforeach 
                            </div> 
                            @endif
                            </div>
                            <div class="flex items-center mt-3 ">
                                <div class="flex  text-center justify-between items-center w-1/3 mx-auto" style="height:20px">
                                    <x-like-btns :tweet="$tweet"></x-like-btns>
                                </div>
                                <div class="w-1/3 text-center ">
                                    <svg viewBox="0 0 20 20" class="w-5 text-gray-500 mx-auto cursor-pointer">
                                            <g class="fill-current">
                                                <path d="M14,11 L8.00585866,11 C6.89706013,11 6,10.1081436 6,9.00798298 L6,1.99201702 C6,0.900176167 6.89805351,0 8.00585866,0 L17.9941413,0 C19.1029399,0 20,0.891856397 20,1.99201702 L20,9.00798298 C20,10.0998238 19.1019465,11 17.9941413,11 L17,11 L17,14 L14,11 Z M14,13 L14,15.007983 C14,16.1081436 13.1029399,17 11.9941413,17 L6,17 L3,20 L3,17 L2.00585866,17 C0.898053512,17 0,16.0998238 0,15.007983 L0,7.99201702 C0,6.8918564 0.897060126,6 2.00585866,6 L4,6 L4,8.99349548 C4,11.2060545 5.78916089,13 7.99620271,13 L14,13 Z" id="Combined-Shape"></path>
                                        </g>
                                        </svg>
                                </div>
                                <div class="w-1/3 text-center ">
                                    <svg viewBox="0 0 20 20" class="w-5 {{current_user()->retweeted($tweet) ? 'text-green-600' : 'text-gray-500'}} mx-auto cursor-pointer">
                                            <g class="fill-current">
                                                <path d="M4.99201702,4 C3.8918564,4 3,4.88670635 3,5.99810135 L3,12 L0,12 L4,16 L8,12 L5,12 L5,6 L12,6 L14,4 L4.99201702,4 Z M15,8 L12,8 L16,4 L20,8 L17,8 L17,14.0018986 C17,15.1054196 16.0998238,16 15.007983,16 L6,16 L8,14 L15,14 L15,8 Z" id="Combined-Shape"></path>
                                        </g>
                                        </svg>
                                </div>
                            </div>
                            
                    </div>
                </div>
                <div class="w-7 mr-2 text-gray-500 relative">
                    <svg viewBox="0 0 20 20" class="w-5 dots hover:text-gray-800 cursor-pointer">
                        <g class="fill-current">
                            <path d="M10,12 C11.1045695,12 12,11.1045695 12,10 C12,8.8954305 11.1045695,8 10,8 C8.8954305,8 8,8.8954305 8,10 C8,11.1045695 8.8954305,12 10,12 Z M10,6 C11.1045695,6 12,5.1045695 12,4 C12,2.8954305 11.1045695,2 10,2 C8.8954305,2 8,2.8954305 8,4 C8,5.1045695 8.8954305,6 10,6 Z M10,18 C11.1045695,18 12,17.1045695 12,16 C12,14.8954305 11.1045695,14 10,14 C8.8954305,14 8,14.8954305 8,16 C8,17.1045695 8.8954305,18 10,18 Z">
                            </path>
                        </g>
                    </svg>
                    <div class="hidden absolute z-10 h-8 w-8 bg-white shadow modal1"
                        style="top:0;right:20px; width:145px; height:100px">
                        <ul class="text-sm text-gray-800 p-2">
                            <li class="border-b mb-1 pb-1">
                                Edit post
                            </li>
                            <li class="mb-1">
                                Hide from timeline
                            </li>
                            <li class="delete-post cursor-pointer" data-id="{{$tweet->id}}">Delete post</li>
                        </ul>
                    </div>
                </div>

            </div>
            <hr>

            @empty
            <p>You don't have any tweets yet!</p>
            @endforelse
            {{ $tweets->links() }}
        </div>
    </section>
    <div class="fixed text-center background text-white bg-green-400 p-4 rounded-lg text-sm notify-published hidden" style="bottom: 50px; right: 10px;width:225px">
        Your tweet has been successfully published.
    <div class="absolute cursor-pointer" style="top:6px;right:6px">
        <svg class="remove-notify text-white w-4" viewBox="0 0 20 20">
                <g class="fill-current">
                    <polygon id="Combined-Shape" points="10 8.58578644 2.92893219 1.51471863 1.51471863 2.92893219 8.58578644 10 1.51471863 17.0710678 2.92893219 18.4852814 10 11.4142136 17.0710678 18.4852814 18.4852814 17.0710678 11.4142136 10 18.4852814 2.92893219 17.0710678 1.51471863 10 8.58578644"></polygon>
                </g>
            </svg>
    </div>
    </div>
</x-app>