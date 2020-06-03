<x-app>
    <x-top-bar>
        <div class="flex items-center z-20">
            <div class="p-5 cursor-pointer" style="width:53px">
                <a  href="{{ url()->previous() }}">
                
                <svg viewBox="0 0 20 20" class="w-4 text-blue-900">
                        <g class="fill-current">
                            <polygon points="3.82842712 9 9.89949494 2.92893219 8.48528137 1.51471863 0 10 0.707106781 10.7071068 8.48528137 18.4852814 9.89949494 17.0710678 3.82842712 11 20 11 20 9 3.82842712 9"></polygon>
                        </g> 
                    </svg>
                </a>
            </div>
            <div class="ml-2 bg-white block mb-2">
                <div class="font-bold text-xl">
                    {{$user->name}}                
                </div>
                <div class="text-xs text-gray-600">
                    {{$totalTweets}} tweets
                </div>
            </div>
    </div>
    </x-top-bar>
    <section>
        
        <div class="relative">
            <img src="{{ $user->cover }}" alt="cover photo" style="height:250px;width:720px" class="object-cover z-10">
            <div class="absolute" style="left:2%; top: 69%">
                <img style="width:145px;height:145px" class="rounded-full border-double border-6 border-white object-cover" src="{{ $user->avatar }}"
                    alt="profile photo">
            </div>
        </div>
        <div class="pt-4 border-l border-r border-gray-300">
            <div class="flex justify-end px-5">
                @if(current_user()->is($user))
                    <div><a
                            href="{{ route('edit-profile', current_user()->username) }}"><button
                                class="rounded-full font-semibold border border-blue-500 bg-white px-6 py-2 text-blue-500 hover:bg-blue-500 hover:text-white">
                            Setup profile</button></a>
                    </div>
                @endif

                <div>
                    @if(current_user()->isNot($user))
                        <form action="/profile/{{ $user->username }}/follow" method="post" id="toggleFollow">
                            @csrf


                            <div>
                                <button
                                    class="rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 shadow"
                                    type="submit" style="height:45px;width:120px"><div class="follow-btn">
                                        <div class="follow-text">{{ current_user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}</div>
                                    </div></button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            <div class="mt-3 px-5">
                <div class="text-xl font-bold">
                    {{ $user->name }}
                </div>
                <div class="text-gray-600 mb-2">{{'@' . $user->username}}</div>
            </div>
        <div class="bio px-5">
            @if(current_user()->is($user))
             @if($user->bio)
             <div class="user-bio word-break">{{$user->bio}}</div>
             <div class="cursor-pointer open-bio text-sm text-blue-500">Change</div>
             @else 
             <div class="user-bio text-gray-500">Tell people more about yourself</div>
             <div class="cursor-pointer open-bio text-sm text-blue-500">Add bio</div>
             @endif
        @else
        <div class="user-bio word-break">{{$user->bio}}</div>
        @endif
            
        </div>
        
                
                           
            <div class="flex items-center mt-1  px-5">
            <div class="mr-5"><span class="font-bold">{{$user->follows()->count()}}</span> <span class="text-gray-600">Following</span></div>
            <div><span class="font-bold">{{$user->followers->count()}}</span> <span class="text-gray-600">Followers</span></div>
            </div>
            <div class="flex items-center cursor-pointer tablist mt-3 font-bold text-gray-600 w-full">
                <div class="profile-tweets w-1/4 text-center hover:text-blue-900 hover:bg-blue-100 hover:border-b-2 hover:border-red-400 py-4">Tweets</div>
                <div class="with-replies w-1/4 text-center hover:text-blue-900 hover:bg-blue-100 hover:border-b-2 hover:border-red-400 py-4 tweets-and-replies">Tweets & Replies</div>
                <div class="w-1/4 text-center hover:text-blue-900 hover:bg-blue-100 hover:border-b-2 hover:border-red-400 py-4">Media</div>
                <div class="w-1/4 text-center hover:text-blue-900 hover:bg-blue-100 hover:border-b-2 hover:border-red-400 py-4">Likes</div>
            </div>
            
        </div>
        {{-- <div>
            <textarea class="expandable border border-gray-400 w-full rounded-lg focus-none py-2 px-4"
                style="resize:none;outline:none" type="text" placeholder="Write something about yourself..."></textarea>
                <div class="flex flex-row-reverse mt-2 hidden save-status">
                <button class="loaded-ajax rounded-full font-semibold bg-white border border-gray-400 px-6 py-2 text-black" type="submit">Save</button> 
        </div>
    </div> --}}
    </section>


    <section class="border border-gray-300 mb-6 load-tweets">
        <div class="load-tweets-ajax dynamic-load">
            @forelse($tweets as $tweet)
        
            @include('__tweet')
        

        @empty
            <p>You don't have any tweets yet!</p>
        @endforelse 
    </div>
</section>
        {{-- popup --}}
   <div class="popup-overlay border-t border-gray-300" style="height:auto">
    <div style="height:53px" class="border-l border-r border-b border-gray-300 sticky top-0 z-10 bg-white">
        <div class="close text-left py-4 ml-3">
            <svg viewBox="0 0 20 20" class="w-5 text-blue-900">
                    <g class="fill-current">
                        <polygon points="10 8.58578644 2.92893219 1.51471863 1.51471863 2.92893219 8.58578644 10 1.51471863 17.0710678 2.92893219 18.4852814 10 11.4142136 17.0710678 18.4852814 18.4852814 17.0710678 11.4142136 10 18.4852814 2.92893219 17.0710678 1.51471863 10 8.58578644"></polygon>
                </g>
                </svg>
        </div>
       </div>
    <div class="popup-content">
        <div class="border-l border-r border-b border-gray-300 px-3 py-4 flex">
            <div>
                <x-avatar-icon>{{ current_user()->avatar }}</x-avatar-icon>
        </div>
    <form method="POST" class="w-full retweet-comment-form">
                @csrf
                @method('POST')
                <div class="relative">
                    <textarea class="mention w-full block mb-3 p-2 text-xl append-body"
                        onblur="textCounter(this,this.form.counter,140);" onkeyup="textCounter(this,this.form.counter,140);"
                        name="body" id="body" required placeholder="What's happening?"></textarea>
                    <div class="counter-1">
                        <input class="absolute text-blue-300 z-30" style="right:-18px;bottom:157px;background:none"
                        onblur="textCounter(this.form.recipients,this,140);" disabled onfocus="this.blur();" tabindex="999"
                        maxlength="3" size="3" value="140" name="counter">
                    </div>
                </div>
                @error('body')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
                <div class="flex justify-end">
                    <button id="retweet-comment" class="mr-3 cursor-pointer rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 block shadow" type="submit">Retweet</button>

                </div>
            </form>
        </div>
      <!--popup's close button-->
       
    </div>
</div>

{{-- popup to change bio --}}

<div class="bio-overlay  border-t border-gray-300" style="height:auto">
    <div style="height:53px" class="border-l border-r border-b border-gray-300 sticky top-0 z-10 bg-white">
        <div class="close text-left py-4 ml-3">
            <svg viewBox="0 0 20 20" class="w-5 text-blue-900">
                    <g class="fill-current">
                        <polygon points="10 8.58578644 2.92893219 1.51471863 1.51471863 2.92893219 8.58578644 10 1.51471863 17.0710678 2.92893219 18.4852814 10 11.4142136 17.0710678 18.4852814 18.4852814 17.0710678 11.4142136 10 18.4852814 2.92893219 17.0710678 1.51471863 10 8.58578644"></polygon>
                </g>
                </svg>
        </div>
       </div>

<div class="bio-content">
    <div class="border-l border-r border-b border-gray-300 px-3 py-4 flex">
        <div>
            <x-avatar-icon>{{ current_user()->avatar }}</x-avatar-icon>
    </div>
<form method="POST" class="w-full bio-form" action="/profile/{{$user->id}}/bio">
            @csrf
            @method('PUT')
            <div class="relative">
                <textarea class="w-full block mb-3 p-2 text-xl"
                    onblur="textCounter(this,this.form.counter,140);" onkeyup="textCounter(this,this.form.counter,140);"
                    name="bio" id="bio" required placeholder="Edit bio...">
                </textarea>
                <div class="counter-2 align-right">
                    <input class="absolute text-blue-300 z-30" style="right:-18px;bottom:105px;background:none"
                    onblur="textCounter(this.form.recipients,this,140);" disabled onfocus="this.blur();" tabindex="999"
                    maxlength="3" size="3" value="140" name="counter">
                </div>
                
            </div>
            @error('bio')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror

            <div class="flex justify-end">
                <button id="save-bio" class="mr-3 cursor-pointer rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 block shadow" type="submit">Save</button>

            </div>
        </form>
    </div>
   
</div>
</div>
{{-- end popup --}}

{{-- popup to reply --}}

<div class="comment-overlay  border-t border-gray-300" style="height:auto">
    <div style="height:53px" class="border-l border-r border-b border-gray-300 sticky top-0 z-10 bg-white">
        <div class="close text-left py-4 ml-3">
            <svg viewBox="0 0 20 20" class="w-5 text-blue-900">
                    <g class="fill-current">
                        <polygon points="10 8.58578644 2.92893219 1.51471863 1.51471863 2.92893219 8.58578644 10 1.51471863 17.0710678 2.92893219 18.4852814 10 11.4142136 17.0710678 18.4852814 18.4852814 17.0710678 11.4142136 10 18.4852814 2.92893219 17.0710678 1.51471863 10 8.58578644"></polygon>
                </g>
                </svg>
        </div>
       </div>

<div class="comment-content">
    <div class="border-l border-r border-b border-gray-300 px-3 py-4">
        <div class="flex mb-3">
            <a class="flex-shrink-0 w-9 mr-2">
                <img class="rounded-full object-cover original-poster" style="width:44px;height:44px">
                <div class="bg-gray-400 mx-auto mt-1 enter-h"></div>
            </a>
            <div class="ml-1 mr-2 w-full calc-h text-left">
                <span class="font-bold response-name"></span>
                <span class="text-gray-600 response-username"></span>
                <span class="text-gray-600">&middot;</span>
                <span class="text-gray-600 datetime"></span>         
                <div class="response-body word-break-popup"></div>
                <div class="mt-2">
                <span class="text-gray-600">Replying to </span>
                <span class="response-username text-blue-900"></span>
            </div>
            </div>
        </div>
        <div class="flex">
        <x-avatar-icon>{{current_user()->avatar}}</x-avatar-icon>
    
<form method="POST" class="w-full comment-form">
            @csrf
            <div class="relative">
                <textarea class="w-full block mb-3 p-2 text-xl"
                    onblur="textCounter(this,this.form.counter,255);" onkeyup="textCounter(this,this.form.counter,255);"
                    name="reply" id="reply" required placeholder="Comment...">
                </textarea>
                <div class="counter-3">
                    <input class="absolute text-blue-300 z-30" style="right:78px;bottom:-44px;background:none"
                    onblur="textCounter(this.form.recipients,this,255);" disabled onfocus="this.blur();" tabindex="999"
                    maxlength="3" size="3" value="255" name="counter">
                </div>
                
            </div>
            @error('reply')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror

            <div class="flex justify-end">
                <button id="save-comment" class="mr-3 cursor-pointer rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 block shadow" type="submit">Save</button>

            </div>
        </form>
    </div>
    </div>
   
</div>
</div>
{{-- end reply popup --}}

  
        {{ $tweets->links() }}
    
</x-app>

