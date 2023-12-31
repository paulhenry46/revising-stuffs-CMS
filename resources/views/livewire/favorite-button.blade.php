<div>
   <button wire:click="favorite">
    <div class="w-10 h-10 absolute right-0 top-0 flex items-center justify-center bg-base-200 shadow-lg  rounded-bl-lg rounded-tr-lg">
        @if(Auth::user()->hasFavorited($post->id) === true)
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-229 294-117q-8 5-17 4.5t-16-5.5q-7-5-10.5-13t-1.5-18l49-212-164-143q-8-7-9.5-15.5t.5-16.5q2-8 9-13.5t17-6.5l217-19 84-200q4-9 12-13.5t16-4.5q8 0 16 4.5t12 13.5l84 200 217 19q10 1 17 6.5t9 13.5q2 8 .5 16.5T826-504L662-361l49 212q2 10-1.5 18T699-118q-7 5-16 5.5t-17-4.5L480-229Z"/></svg>
        @elseif(Auth::user()->hasFavorited($post->id) === false)
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m323-205 157-94 157 95-42-178 138-120-182-16-71-168-71 167-182 16 138 120-42 178Zm157-24L294-117q-8 5-17 4.5t-16-5.5q-7-5-10.5-13t-1.5-18l49-212-164-143q-8-7-9.5-15.5t.5-16.5q2-8 9-13.5t17-6.5l217-19 84-200q4-9 12-13.5t16-4.5q8 0 16 4.5t12 13.5l84 200 217 19q10 1 17 6.5t9 13.5q2 8 .5 16.5T826-504L662-361l49 212q2 10-1.5 18T699-118q-7 5-16 5.5t-17-4.5L480-229Zm0-206Z"/></svg>
        @endif
    </div>
    <div style="display: none;"><span class="text-primary loading loading-spinner loading-sm"></span></div>
    </button>
</div>
