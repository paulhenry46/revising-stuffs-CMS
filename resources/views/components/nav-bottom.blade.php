<div class="btm-nav">
  <a href="{{route('post.public.news')}}" wire:navigate @if($active == 0) class="active bg-purple-200 dark:bg-purple-900" @endif>
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M513-492v-171q0-13-8.5-21.5T483-693q-13 0-21.5 8.5T453-663v183q0 6 2 11t6 10l144 149q9 10 22.5 9.5T650-310q9-9 9-22t-9-22L513-492ZM480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-82 31.5-155t86-127.5Q252-817 325-848.5T480-880q82 0 155 31.5t127.5 86Q817-708 848.5-635T880-480q0 82-31.5 155t-86 127.5Q708-143 635-111.5T480-80Zm0-400Zm0 340q140 0 240-100t100-240q0-140-100-240T480-820q-140 0-240 100T140-480q0 140 100 240t240 100Z"/></svg>
  </a>
  <a href="{{route('post.public.library')}}" wire:navigate @if($active == 1) class="active bg-orange-200 dark:bg-warning-content" @endif>
   <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M300-80q-58 0-99-41t-41-99v-520q0-58 41-99t99-41h500v600q-25 0-42.5 17.5T740-220q0 25 17.5 42.5T800-160v80H300Zm-60-267q14-7 29-10t31-3h20v-440h-20q-25 0-42.5 17.5T240-740v393Zm160-13h320v-440H400v440Zm-160 13v-453 453Zm60 187h373q-6-14-9.5-28.5T660-220q0-16 3-31t10-29H300q-26 0-43 17.5T240-220q0 26 17 43t43 17Z"/></svg>
  </a>
  <a href="{{route('post.public.favorites')}}" wire:navigate @if($active == 2) class="active bg-green-200 dark:bg-success-content" @endif>
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M420-167 203-75q-30 13-56.5-5.5T120-131v-609q0-24 18-42t42-18h480q24 0 42.5 18t18.5 42v610q0 32.489-26.5 50.244Q668-62 638-75l-218-92Zm0-67 240 103v-609H180v609l240-103Zm360 104v-730H233v-60h547q24 0 42 18t18 42v730h-60ZM420-740H180h480-240Z"/></svg>
  </a>
</div>