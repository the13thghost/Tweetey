@forelse($tweets as $tweet)
{{-- <div>{{$tweet}}</div><br> --}}
    @include('__replies') 

@empty
@can('noTweetsMsg', $user)
<div class="text-lg text-center m-3 text-gray-500">You don't have any replies yet</div>
@endcan
@cannot('NoTweetsMsg', $user)
<div class="text-lg text-center m-3 text-gray-500">No replies yet</div>
@endcannot
@endforelse 