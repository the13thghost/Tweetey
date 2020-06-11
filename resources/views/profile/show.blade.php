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
                <img style="width:145px;height:145px"
                    class="rounded-full border-double border-6 border-white object-cover" src="{{ $user->avatar }}"
                    alt="profile photo">
            </div>
        </div>
        <div class="pt-4 border-l border-r border-gray-300">
            <div class="flex justify-end px-5">
                @if(current_user()->is($user))
                <div>
                    <a href="{{ route('edit-profile', current_user()->username) }}">
                        <button
                            class="rounded-full font-semibold border border-blue-500 bg-white px-6 py-2 text-blue-500 hover:bg-blue-500 hover:text-white">
                            Setup profile
                        </button>
                    </a>
                </div>
                @endif
                <div>
                    @if(current_user()->isNot($user))
                    <form action="/profile/{{ $user->username }}/follow" method="post" id="toggleFollow">
                        @csrf
                        <div>
                            <button
                                class="rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 shadow"
                                type="submit" style="height:45px;width:120px">
                                <div class="follow-btn">
                                    <div class="follow-text">
                                        {{ current_user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}</div>
                                </div>
                            </button>
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
                <div class="cursor-pointer open-bio text-sm text-blue-500 inline">Change</div>
                @else
                <div class="user-bio text-gray-500">Tell people more about yourself</div>
                <div class="cursor-pointer open-bio text-sm text-blue-500">Add bio</div>
                @endif
                @else
                <div class="user-bio word-break">{{$user->bio}}</div>
                @endif
            </div>
            <div class="flex items-center mt-1  px-5">
                <div class="mr-5"><span class="font-bold">{{$user->follows()->count()}}</span>
                    <span class="text-gray-600">Following</span>
                </div>
                <div><span class="font-bold">{{$user->followers->count()}}</span>
                    <span class="text-gray-600">Followers</span>
                </div>
            </div>
            <div class="flex items-center cursor-pointer tablist mt-3 font-bold text-gray-600 w-full">
                <div
                    class="{{Request::is('profile/' . $user->username) ? 'active-link' : ''}} profile-tweets w-1/4 text-center hover:text-blue-900 hover:bg-blue-100 hover:border-b-2 hover:border-red-400 py-4">
                    Tweets</div>
                <div
                    class="{{Request::is('profile/' . $user->username . '/with-replies') ? 'active-link' : ''}} with-replies w-1/4 text-center hover:text-blue-900 hover:bg-blue-100 hover:border-b-2 hover:border-red-400 py-4 tweets-and-replies">
                    Replies</div>
                <div
                    class="{{Request::is('profile/' . $user->username . '/media') ? 'active-link' : ''}} media w-1/4 text-center hover:text-blue-900 hover:bg-blue-100 hover:border-b-2 hover:border-red-400 py-4">
                    Media</div>
                <div
                    class="{{Request::is('profile/' . $user->username . '/likes') ? 'active-link' : ''}} likes-nav w-1/4 text-center hover:text-blue-900 hover:bg-blue-100 hover:border-b-2 hover:border-red-400 py-4">
                    Likes</div>
            </div>

        </div>
    </section>
    <section class="border border-gray-300 mb-6 load-tweets">
        <div class="load-tweets-ajax dynamic-load">
            @forelse($tweets as $tweet)

            @include('__tweet')

            @empty
            @can('noTweetsMsg', $user)
            <div class="text-lg text-center m-3 text-gray-500">You don't have any tweets yet</div>
            @endcan
            @cannot('NoTweetsMsg', $user)
            <div class="text-lg text-center m-3 text-gray-500">This user has not posted yet</div>
            @endcannot
            @endforelse
        </div>
    </section>
    <x-popups :user="$user"></x-popups>
    {{ $tweets->links() }}
</x-app>
