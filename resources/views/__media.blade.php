@forelse($tweets as $tweet)
    @if($tweet->images->isNotEmpty())    
    @include('__media-tweets') 
    @endif
@empty
@can('noTweetsMsg', $user)
<div class="text-lg text-center m-3 text-gray-500">You don't have any tweets with images yet</div>
@endcan
@cannot('NoTweetsMsg', $user)
<div class="text-lg text-center m-3 text-gray-500">No tweets with images yet</div>
@endcannot
@endforelse 