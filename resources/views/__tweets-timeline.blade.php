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
