<div class="p-4 bg-gray-blue sticky" style="top:18px">
    <h3 class="mb-3 font-bold text-lg">Following</h3>
    <ul class="following"> 
        <div class="following-users">
            @if(empty(current_user()->follows))
            <div class="italic">
                You are not following anyone.<br>
                Try <a href="/explore" class="text-blue-500 font-semibold hover:underline">Explore</a>
            </div>
            @else
            @for ($i = 0; $i < 5; $i++)
            <li class="flex items-center mb-3">
                <a href="/profile/{{current_user()->follows[$i]->username}}" class="mr-2">
                    <x-avatar-icon>{{current_user()->follows[$i]->avatar}}</x-avatar-icon>
                </a>
                <a href="/profile/{{current_user()->follows[$i]->username}}">
                    <p>{{current_user()->follows[$i]->name}}</p>
                </a>
            </li>
            @endfor
        <div class="font-semibold text-blue-600 text-lg cursor-pointer hover:underline">And {{current_user()->follows->count() - 5}}+ others</div>
            @endif
            
            
            
    </ul>
</div>

