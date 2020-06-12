@forelse($tweets as $tweet)
    @include('__media-tweets') 
@empty 
@can('noTweetsMsg', $user)
<div class="text-lg text-center m-3 text-gray-500">You don't have any tweets with images yet</div>
@endcan
@cannot('NoTweetsMsg', $user)
<div class="text-lg text-center m-3 text-gray-500">No tweets with images yet</div>
@endcannot
@endforelse 

