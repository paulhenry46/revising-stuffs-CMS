<div>
    @if($mobile == false)
   <button wire:click="like">
    <div class="w-16 h-10 absolute left-0 top-0 flex items-center justify-center bg-base-200 dark:bg-base-100 shadow-lg  rounded-br-lg rounded-tl-lg">
        @if($post->isLiked())
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-140q-11 0-22-4t-19-12l-53-49Q262-320 171-424.5T80-643q0-90 60.5-150.5T290-854q51 0 101 24.5t89 80.5q44-56 91-80.5t99-24.5q89 0 149.5 60.5T880-643q0 114-91 218.5T574-205l-53 49q-8 8-19 12t-22 4Z"/>
        </svg>
        @else
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-140q-10.699 0-21.78-3.869-11.082-3.869-19.488-12.381L386-205Q262-320 171-424.5T80-643q0-90.155 60.5-150.577Q201-854 290-854q51 0 101 24.5t89 80.5q44-56 91-80.5t99-24.5q89 0 149.5 60.423Q880-733.155 880-643q0 114-91 218.5T574-205l-53 49q-8.25 8.381-19.125 12.19Q491-140 480-140Zm-26-543q-27-49-71-80t-93-31q-66 0-108 42.5t-42 108.929q0 57.571 38.881 121.225 38.882 63.654 93 123.5Q326-338 384-286.5q58 51.5 96 86.5 38-34 96-86t112-112.5q54-60.5 93-124.192Q820-586.385 820-643q0-66-42.5-108.5T670-794q-50 0-93.5 30.5T504-683q-5 8-11 11.5t-14 3.5q-8 0-14.5-3.5T454-683Zm26 186Z"/>
        </svg>
        @endif
           <p class="ml-1">{{$count}}</p>
    </div>
    <div style="display: none;"><span class="text-primary loading loading-spinner loading-sm"></span></div>
    </button>
    @elseif($mobile=='cards')
    <button class="btn btn-square btn-ghost dark:bg-base-100" wire:click="like">
    <div>
        @if($post->isLiked())
        <svg class="text-error" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-140q-11 0-22-4t-19-12l-53-49Q262-320 171-424.5T80-643q0-90 60.5-150.5T290-854q51 0 101 24.5t89 80.5q44-56 91-80.5t99-24.5q89 0 149.5 60.5T880-643q0 114-91 218.5T574-205l-53 49q-8 8-19 12t-22 4Z"/>
        </svg>
        @else
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-140q-10.699 0-21.78-3.869-11.082-3.869-19.488-12.381L386-205Q262-320 171-424.5T80-643q0-90.155 60.5-150.577Q201-854 290-854q51 0 101 24.5t89 80.5q44-56 91-80.5t99-24.5q89 0 149.5 60.423Q880-733.155 880-643q0 114-91 218.5T574-205l-53 49q-8.25 8.381-19.125 12.19Q491-140 480-140Zm-26-543q-27-49-71-80t-93-31q-66 0-108 42.5t-42 108.929q0 57.571 38.881 121.225 38.882 63.654 93 123.5Q326-338 384-286.5q58 51.5 96 86.5 38-34 96-86t112-112.5q54-60.5 93-124.192Q820-586.385 820-643q0-66-42.5-108.5T670-794q-50 0-93.5 30.5T504-683q-5 8-11 11.5t-14 3.5q-8 0-14.5-3.5T454-683Zm26 186Z"/>
        </svg>
        @endif
    </div>
    <div style="display: none;"><span class="text-primary loading loading-spinner loading-sm"></span></div>
    </button>
    @else
    <button wire:click="like">
    <div class="w-10 h-10 absolute @auth right-14 @endauth @guest right-2 @endguest top-2 flex items-center justify-center bg-base-200 dark:bg-base-100 shadow-lg  rounded-full ">
        @if($post->isLiked())
        <svg class="text-red-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-140q-11 0-22-4t-19-12l-53-49Q262-320 171-424.5T80-643q0-90 60.5-150.5T290-854q51 0 101 24.5t89 80.5q44-56 91-80.5t99-24.5q89 0 149.5 60.5T880-643q0 114-91 218.5T574-205l-53 49q-8 8-19 12t-22 4Z"/>
        </svg>
        @else
        <svg  fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-140q-10.699 0-21.78-3.869-11.082-3.869-19.488-12.381L386-205Q262-320 171-424.5T80-643q0-90.155 60.5-150.577Q201-854 290-854q51 0 101 24.5t89 80.5q44-56 91-80.5t99-24.5q89 0 149.5 60.423Q880-733.155 880-643q0 114-91 218.5T574-205l-53 49q-8.25 8.381-19.125 12.19Q491-140 480-140Zm-26-543q-27-49-71-80t-93-31q-66 0-108 42.5t-42 108.929q0 57.571 38.881 121.225 38.882 63.654 93 123.5Q326-338 384-286.5q58 51.5 96 86.5 38-34 96-86t112-112.5q54-60.5 93-124.192Q820-586.385 820-643q0-66-42.5-108.5T670-794q-50 0-93.5 30.5T504-683q-5 8-11 11.5t-14 3.5q-8 0-14.5-3.5T454-683Zm26 186Z"/>
        </svg>
        @endif
          
    </div>
    <div style="display: none;"><span class="text-primary loading loading-spinner loading-sm"></span></div>
    </button>
    @endif
</div>
