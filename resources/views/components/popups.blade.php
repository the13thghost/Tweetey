    {{-- popup --}}
    <div class="popup-overlay border-t border-gray-300" style="height:auto">
        <div style="height:53px" class="border-l border-r border-b border-gray-300 sticky top-0 z-10 bg-white">
            <div class="close text-left py-4 ml-3">
                <svg viewBox="0 0 20 20" class="w-5 text-blue-900">
                    <g class="fill-current">
                        <polygon
                            points="10 8.58578644 2.92893219 1.51471863 1.51471863 2.92893219 8.58578644 10 1.51471863 17.0710678 2.92893219 18.4852814 10 11.4142136 17.0710678 18.4852814 18.4852814 17.0710678 11.4142136 10 18.4852814 2.92893219 17.0710678 1.51471863 10 8.58578644">
                        </polygon>
                    </g>
                </svg>
            </div>
        </div>
        <div class="popup-content">
            <div class="border-l border-r border-b border-gray-300 px-3 py-4 flex">
                <div>
                    <x-avatar-icon>{{ current_user()->avatar }}</x-avatar-icon>
                </div>
                <form method="POST" class="w-full retweet-comment-form">
                    @csrf
                    @method('POST')
                    <div>
                        <textarea class="mention w-full block mb-3 p-2 text-xl append-body"
                            onblur="textCounter(this,this.form.counter,255);"
                            onkeyup="textCounter(this,this.form.counter,255);" name="body" id="body"
                            placeholder="Comment..."></textarea>
                    </div>
                    <div class="publish-errors-comment text-sm text-red-500 font-semibold text-left"></div>
                    <div class="flex justify-end items-center">
                        <div class="counter-1">
                            <input class="text-blue-300 z-30" style="background:none;width:40px"
                                onblur="textCounter(this.form.recipients,this,255);" disabled onfocus="this.blur();"
                                tabindex="999" maxlength="3" size="3" value="255" name="counter">
                        </div>
                        <button id="retweet-comment"
                            class="mr-3 cursor-pointer rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 block shadow"
                            type="submit">
                            Retweet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Popup for bio --}}
    <div class="bio-overlay  border-t border-gray-300" style="height:auto">
        <div style="height:53px" class="border-l border-r border-b border-gray-300 sticky top-0 z-10 bg-white">
            <div class="close text-left py-4 ml-3">
                <svg viewBox="0 0 20 20" class="w-5 text-blue-900">
                    <g class="fill-current">
                        <polygon
                            points="10 8.58578644 2.92893219 1.51471863 1.51471863 2.92893219 8.58578644 10 1.51471863 17.0710678 2.92893219 18.4852814 10 11.4142136 17.0710678 18.4852814 18.4852814 17.0710678 11.4142136 10 18.4852814 2.92893219 17.0710678 1.51471863 10 8.58578644">
                        </polygon>
                    </g>
                </svg>
            </div>
        </div>

        <div class="bio-content">
            <div class="border-l border-r border-b border-gray-300 px-3 py-4 flex">
                <div>
                    <x-avatar-icon>{{ current_user()->avatar }}</x-avatar-icon>
                </div>
                <form method="POST" class="w-full bio-form" action="/profile/{{$user->id}}/bio">
                    @csrf
                    @method('PUT')
                    <div>
                        <textarea class="w-full block mb-3 p-2 text-xl"
                            onblur="textCounter(this,this.form.counter,140);"
                            onkeyup="textCounter(this,this.form.counter,140);" name="bio" id="bio" required
                            placeholder="Edit bio...">
                        </textarea>
                    </div>
                    <div class="flex justify-end items-center">
                        <div class="counter-2 align-right">
                            <input class="text-blue-300 z-30" style="width:40px;background:none"
                                onblur="textCounter(this.form.recipients,this,140);" disabled onfocus="this.blur();"
                                tabindex="999" maxlength="3" size="3" value="140" name="counter">
                        </div>
                        <button id="save-bio"
                            class="mr-3 cursor-pointer rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 block shadow"
                            type="submit">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Popup to reply --}}
    <div class="comment-overlay border-t border-gray-300" style="height:auto">
        <div style="height:53px" class="border-l border-r border-b border-gray-300 sticky top-0 z-10 bg-white">
            <div class="close text-left py-4 ml-3">
                <svg viewBox="0 0 20 20" class="w-5 text-blue-900">
                    <g class="fill-current">
                        <polygon
                            points="10 8.58578644 2.92893219 1.51471863 1.51471863 2.92893219 8.58578644 10 1.51471863 17.0710678 2.92893219 18.4852814 10 11.4142136 17.0710678 18.4852814 18.4852814 17.0710678 11.4142136 10 18.4852814 2.92893219 17.0710678 1.51471863 10 8.58578644">
                        </polygon>
                    </g>
                </svg>
            </div>
        </div>

        <div class="comment-content">
            <div class="border-l border-r border-b border-gray-300 px-3 py-4">
                <div class="flex mb-3">
                    <a class="flex-shrink-0 w-9 mr-2">
                        <img class="rounded-full object-cover original-poster" style="width:44px;height:44px">
                        <div class="bg-gray-400 mx-auto mt-1 enter-h"></div> 
                    </a>
                    <div class="ml-1 mr-2 w-full calc-h text-left">
                        <span class="font-bold response-name"></span>
                        <span class="text-gray-600 response-username"></span>
                        <span class="text-gray-600">&middot;</span>
                        <span class="text-gray-600 datetime"></span>
                        <div class="response-body word-break-popup"></div>
                        {{-- <x-images-layout></x-images-layout> --}}
                        <div class="mt-2">
                            <span class="text-gray-600">Replying to </span>
                            <span class="response-username text-blue-900"></span>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <x-avatar-icon>{{current_user()->avatar}}</x-avatar-icon>
                    <form method="POST" class="w-full comment-form">
                        @csrf
                        <div>
                            <textarea class="w-full block mb-3 p-2 text-xl"
                                onblur="textCounter(this,this.form.counter,255);"
                                onkeyup="textCounter(this,this.form.counter,255);" name="reply" id="reply" 
                                placeholder="Comment...">
                            </textarea>
                    <div class="publish-errors-reply text-sm text-red-500 font-semibold text-left"></div>

                        </div>
                        <div class="flex justify-end items-center">
                            <div class="counter-3">
                                <input class="text-blue-300 z-30"
                                    style="width:44px;background:none"
                                    onblur="textCounter(this.form.recipients,this,255);" disabled onfocus="this.blur();"
                                    tabindex="999" maxlength="3" size="3" value="255" name="counter">
                            </div>
                            <button id="save-comment"
                                class="mr-3 cursor-pointer rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 block shadow"
                                type="submit">
                                Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
