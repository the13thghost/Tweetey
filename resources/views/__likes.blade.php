@forelse($tweets as $tweet)
    @include('__liked-tweets')  
@empty
@can('noTweetsMsg', $user)
<div class="text-lg text-center m-3 text-gray-500">You don't have any liked tweets yet</div>
@endcan
@cannot('NoTweetsMsg', $user)
<div class="text-lg text-center m-3 text-gray-500">No liked tweets yet</div>
@endcannot
@endforelse 