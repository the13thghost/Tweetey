@forelse($tweets as $tweet)
        
    <!-- file for showing only replies (structure )on tweets of certain users -->
    @include('__replies')
    <!-- i need a new file here because the tweet structure will not be the same, __tweet-replies -->
        

@empty
    <p>You don't have any tweets yet!</p>
@endforelse 