<div class="mt-2">

@if($tweet->images()->count() == 1) 
    
<img 
    src="{{ asset('/storage/' . $tweet->images[0]->image) }}" 
    alt="tweet image"
    class="object-cover rounded-xl border border-gray-400" 
    style="width:607px;height:300px">

@elseif($tweet->images()->count() == 2)

<div class="grid grid-rows-1 grid-flow-col gap-4">
    <div class="row-span-1 col-span-1">
        <img 
            src="{{ asset('/storage/' . $tweet->images[0]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:300px">
    </div>
    <div class="row-span-1 col-span-1">
        <img 
            src="{{ asset('/storage/' . $tweet->images[1]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:300px">
    </div>
  </div>

@elseif($tweet->images()->count() == 3)

<div class="grid grid-rows-2 grid-flow-col gap-4">
    <div class="row-span-3">
        <img 
            src="{{ asset('/storage/' . $tweet->images[0]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:300px">
    </div>
    <div class="row-span-1 col-span-2">
        <img 
            src="{{ asset('/storage/' . $tweet->images[1]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:141px">
    </div>
    <div class="row-span-2 col-span-2">
        <img 
            src="{{ asset('/storage/' . $tweet->images[2]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:141px">
    </div>
</div>

@elseif($tweet->images()->count() == 4)

<div class="grid grid-rows-2 grid-flow-col gap-4">
    <div class="row-span-1 col-span-2">
        <img 
            src="{{ asset('/storage/' . $tweet->images[0]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:141px;width:298px">
    </div>
    <div class="row-span-1 col-span-2">
        <img 
            src="{{ asset('/storage/' . $tweet->images[1]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:141px;width:298px">

    </div>
    <div class="row-span-1 col-span-2">
        <img 
            src="{{ asset('/storage/' . $tweet->images[2]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:141px;width:298px">

    </div>
    <div class="row-span-1 col-span-2">
        <img 
            src="{{ asset('/storage/' . $tweet->images[3]->image) }}" 
            alt="tweet image"
            class="object-cover rounded-xl border border-gray-400" 
            style="height:141px;width:298px">

    </div>
</div>
@endif
</div>