<div class="w-1/4 text-center items-center" style="height:20px">
    <div data-id-like="{{ $tweet->id }}"
        class="relative submit-like cursor-pointer mr-2 w-4 {{ $tweet->checkIfLiked('like', auth()->id()) ? 'text-blue-400' : 'text-gray-500' }}"
        type="submit">
        <svg viewBox="0 0 20 20">
            <g class="fill-current">
                <path
                    d="M11.0010436,0 C9.89589787,0 9.00000024,0.886706352 9.0000002,1.99810135 L9,8 L1.9973917,8 C0.894262725,8 0,8.88772964 0,10 L0,12 L2.29663334,18.1243554 C2.68509206,19.1602453 3.90195042,20 5.00853025,20 L12.9914698,20 C14.1007504,20 15,19.1125667 15,18.000385 L15,10 L12,3 L12,0 L11.0010436,0 L11.0010436,0 Z M17,10 L20,10 L20,20 L17,20 L17,10 L17,10 Z">
                </path>
            </g>
        </svg>
        <div class="absolute load-here-{{ $tweet->id }} text-sm text-gray-500 w-4" style="top:0;left:22px">
            <div class="load-ajax-{{ $tweet->id }}">@if($tweet->likes != 0){{ $tweet->likes }}@endif</div>
        </div>
    </div>
</div>
<div class="w-1/4 text-center items-center" style="height:20px">
    <div data-id-dislike="{{ $tweet->id }}"
        class="relative w-4 mr-2 cursor-pointer submit-dislike {{ $tweet->checkIfLiked('dislike', auth()->id()) ? 'text-blue-400' : 'text-gray-500' }}"
        type="submit">
        <svg viewBox="0 0 20 20">
            <g class="fill-current">
                <path
                    d="M11.0010436,20 C9.89589787,20 9.00000024,19.1132936 9.0000002,18.0018986 L9,12 L1.9973917,12 C0.894262725,12 0,11.1122704 0,10 L0,8 L2.29663334,1.87564456 C2.68509206,0.839754676 3.90195042,8.52651283e-14 5.00853025,8.52651283e-14 L12.9914698,8.52651283e-14 C14.1007504,8.52651283e-14 15,0.88743329 15,1.99961498 L15,10 L12,17 L12,20 L11.0010436,20 L11.0010436,20 Z M17,10 L20,10 L20,0 L17,0 L17,10 L17,10 Z">
                </path>
            </g>
        </svg>
        <div class="absolute load-here-dis-{{ $tweet->id }} text-sm text-gray-500 w-4" style="top:0;left:22px">
            <div class="load-ajax-dis-{{ $tweet->id }}">@if($tweet->dislikes != 0){{ $tweet->dislikes }}@endif</div>
        </div>
    </div>
</div>
