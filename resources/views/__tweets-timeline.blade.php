@forelse($tweets as $tweet)
        
            @include('__tweet')
        

        @empty
            <p>You don't have any tweets yet!</p>
    @endforelse 
    {{-- <div class="relative">
    <div class="lds-roller absolute hidden"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
</div> --}}