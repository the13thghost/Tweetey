<h3 class="mb-3 font-bold text-lg">Following</h3>
    <ul class="following">
        <div class="following-users">
        @forelse (current_user()->follows as $friend)
        <li class="flex items-center mb-3">
        <a href="/profile/{{$friend->username}}"><x-avatar-icon>{{$friend->avatar}}</x-avatar-icon></a>
        <a href="/profile/{{$friend->username}}"><p>{{$friend->name}}</p></a>
        </li>
        @empty
        <div class="italic">
            You are not following anyone.<br>
            Try <a href="/exlopre" class="text-blue-500 font-semibold hover:underline">Explore</a>
        </div>
            
        @endforelse  

    </ul>


