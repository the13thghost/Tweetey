<div class="w-7 mr-2 text-gray-500 relative">
    <svg viewBox="0 0 20 20" class="w-5 dots hover:text-gray-800 cursor-pointer">
        <g class="fill-current">
            <path
                d="M10,12 C11.1045695,12 12,11.1045695 12,10 C12,8.8954305 11.1045695,8 10,8 C8.8954305,8 8,8.8954305 8,10 C8,11.1045695 8.8954305,12 10,12 Z M10,6 C11.1045695,6 12,5.1045695 12,4 C12,2.8954305 11.1045695,2 10,2 C8.8954305,2 8,2.8954305 8,4 C8,5.1045695 8.8954305,6 10,6 Z M10,18 C11.1045695,18 12,17.1045695 12,16 C12,14.8954305 11.1045695,14 10,14 C8.8954305,14 8,14.8954305 8,16 C8,17.1045695 8.8954305,18 10,18 Z">
            </path>
        </g>
    </svg>
    <div class="hidden absolute z-10 h-8 w-8 bg-white shadow modal1"
        style="top:0;right:20px; width:145px; height:82px">
        <ul class="text-xs text-gray-800 p-2">
            <li class="border-b mb-1 pb-1">
                Pin to timeline
            </li>
            <li class="mb-1">
                Hide from timeline
            </li>
            <li class="delete-post cursor-pointer" data-id="{{ $tweet->id }}">Delete tweet</li>
        </ul>
    </div>
</div>