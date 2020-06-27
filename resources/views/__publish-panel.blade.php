<x-top-bar>
    <div class="font-bold text-xl ml-3 py-2">Home</div>
</x-top-bar>
<div class="border-l border-r border-b border-gray-300 px-3 py-4 flex">
    <div>
        <x-avatar-icon>{{ current_user()->avatar }}</x-avatar-icon>
    </div>
    <form id="publish" action="/tweets" method="post" enctype="multipart/form-data" class="w-full">
        @csrf 
        @method('POST')
        <div class="relative">
            <div class="textarea-1">
                <textarea 
                    class="textarea-fresh w-full block mb-3 p-2 text-xl outline-none"
                    style="min-height:120px;resize:none;" 
                    onblur="textCounter(this,this.form.counter,255);"
                    onkeyup="textCounter(this,this.form.counter,255);" 
                    name="body" 
                    id="body"
                    placeholder="What's going on?">{{ old('body') }}
                </textarea>
            </div>
            <div id="country_list"></div>
            <div class="counter-1">
                <input 
                    class="counter-fresh absolute text-blue-300" 
                    style="right:-18px;bottom:6px;background:none"
                    onblur="textCounter(this.form.recipients,this,255);" 
                    disabled 
                    onfocus="this.blur();" 
                    tabindex="999"
                    maxlength="3" 
                    size="3" 
                    value="255" 
                    name="counter">
            </div>
        </div>
        <div class="publish-errors text-sm text-red-500 font-semibold"></div>
        <div class="flex justify-end">
            <div class="cursor-pointer add-image py-2 px-3 rounded-full inline-block">
                <svg viewBox="0 0 20 20" class="text-gray-400 w-6">
                    <g class="fill-current">
                        <path
                            d="M11,13 L8,10 L2,16 L11,16 L18,16 L13,11 L11,13 Z M0,3.99406028 C0,2.8927712 0.898212381,2 1.99079514,2 L18.0092049,2 C19.1086907,2 20,2.89451376 20,3.99406028 L20,16.0059397 C20,17.1072288 19.1017876,18 18.0092049,18 L1.99079514,18 C0.891309342,18 0,17.1054862 0,16.0059397 L0,3.99406028 Z M15,9 C16.1045695,9 17,8.1045695 17,7 C17,5.8954305 16.1045695,5 15,5 C13.8954305,5 13,5.8954305 13,7 C13,8.1045695 13.8954305,9 15,9 Z"
                            id="Combined-Shape">
                        </path>
                    </g>
                </svg>
            </div>
            <input type="file" multiple name="image[]" id="gallery-photo-add" style="display:none">
            @error('image')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror
            <div class="justify-end">
                <x-blue-btn>Tweet</x-blue-btn>
            </div>
        </div>
        <div class="gallery flex flex-wrap"></div>
    </form>
</div>
<div class="bg-gray-300 h-2 w-full"></div>
