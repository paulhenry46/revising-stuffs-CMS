<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('All posts'),NULL),
  array($post->title,NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/> 
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
        {{$post->title}}
    </h1>
</div>
<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
    <x-info-message/>
    <div class="px-4 ">
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto ">
                <div class="inline-block min-w-full py-2 align-middle ">
                    <section>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-3 lg:col-span-2">
                                <div class="card bg-base-100 shadow-xl">
                                    <figure><img src="{{url('storage/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png')}}" alt="Shoes" /></figure>
                                    <div class="card-body">
                                        <h2 class="card-title">{{$post->title}}</h2>
                                        <p>{{$post->description}}</p>
                                        <div class="card-actions items-center justify-center flex">
                                            <div class="items-center justify-center flex min-h-[6rem] min-w-[18rem] max-w-4xl flex-wrap gap-2">
                                                <ul class="menu menu-horizontal bg-base-200 rounded-box">
                                                    @foreach($files as $file)
                                                    @if ($file->type == 'primary light')
                                                    <li>
                                                        <a href="{{url('storage/'.$file->file_path.'')}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM80-440q-17 0-28.5-11.5T40-480q0-17 11.5-28.5T80-520h80q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440H80Zm720 0q-17 0-28.5-11.5T760-480q0-17 11.5-28.5T800-520h80q17 0 28.5 11.5T920-480q0 17-11.5 28.5T880-440h-80ZM480-760q-17 0-28.5-11.5T440-800v-80q0-17 11.5-28.5T480-920q17 0 28.5 11.5T520-880v80q0 17-11.5 28.5T480-760Zm0 720q-17 0-28.5-11.5T440-80v-80q0-17 11.5-28.5T480-200q17 0 28.5 11.5T520-160v80q0 17-11.5 28.5T480-40ZM226-678l-43-42q-12-11-11.5-28t11.5-29q12-12 29-12t28 12l42 43q11 12 11 28t-11 28q-11 12-27.5 11.5T226-678Zm494 495-42-43q-11-12-11-28.5t11-27.5q11-12 27.5-11.5T734-282l43 42q12 11 11.5 28T777-183q-12 12-29 12t-28-12Zm-42-495q-12-11-11.5-27.5T678-734l42-43q11-12 28-11.5t29 11.5q12 12 12 29t-12 28l-43 42q-12 11-28 11t-28-11ZM183-183q-12-12-12-29t12-28l43-42q12-11 28.5-11t27.5 11q12 11 11.5 27.5T282-226l-42 43q-11 12-28 11.5T183-183Zm297-297Z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                    @elseif($file->type == 'primary dark')
                                                    <li>
                                                        <a href="{{url('storage/'.$file->file_path.'')}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @endforeach
                                                    @if($post->cards)
                                                    <li>
                                                        <a wire:navigate href="{{route('post.public.cards.show', [$post->slug, $post->id])}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="m608-368 46-166-142-98-46 166 142 98ZM160-207l-33-16q-31-13-42-44.5t3-62.5l72-156v279Zm160 87q-33 0-56.5-24T240-201v-239l107 294q3 7 5 13.5t7 12.5h-39Zm206-5q-31 11-62-3t-42-45L245-662q-11-31 3-61.5t45-41.5l301-110q31-11 61.5 3t41.5 45l178 489q11 31-3 61.5T827-235L526-125Zm-28-75 302-110-179-490-301 110 178 490Zm62-300Z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if($post->quizlet_url !== NULL)
                                                    <li>
                                                        <a href="{{'https://quizlet.com/'.$post->quizlet_url.''}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="24" height="24">
                                                                <path d="M 11 4 C 7.14 4 4 7.14 4 11 L 4 39 C 4 42.86 7.14 46 11 46 L 39 46 C 42.86 46 46 42.86 46 39 L 46 11 C 46 7.14 42.86 4 39 4 L 11 4 z M 25 11 C 32.72 11 39 17.28 39 25 C 39 28.231 37.889922 31.201266 36.044922 33.572266 L 39.611328 37.851562 C 39.786328 38.063563 39.823031 38.348656 39.707031 38.597656 C 39.590031 38.846656 39.347266 39 39.072266 39 L 33.96875 39 C 33.52375 39 33.103406 38.804891 32.816406 38.462891 L 32.816406 38.460938 L 31.792969 37.232422 C 29.778969 38.355422 27.464 39 25 39 C 17.28 39 11 32.72 11 25 C 11 17.28 17.28 11 25 11 z M 25 17 C 20.589 17 17 20.589 17 25 C 17 29.411 20.589 33 25 33 C 25.996 33 26.947125 32.809609 27.828125 32.474609 L 23.390625 27.148438 C 23.214625 26.937438 23.176969 26.651344 23.292969 26.402344 C 23.409969 26.153344 23.652734 26 23.927734 26 L 29.03125 26 C 29.47625 26 29.896594 26.195109 30.183594 26.537109 L 32.048828 28.777344 C 32.655828 27.651344 33 26.365 33 25 C 33 20.589 29.411 17 25 17 z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-6">
                                    <div class="rounded-lg navbar dark:bg-base-100">
                                        <div class="flex-1">
                                            <a class="btn btn-ghost normal-case text-xl">Comments</a>
                                        </div>
                                        <div class="flex-none">
                                            <button onclick="add_comment.showModal()" class="btn btn-square btn-ghost dark:bg-base-200">
                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                    <path d="M440-400h80v-120h120v-80H520v-120h-80v120H320v80h120v120ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <!--Modal for adding a comment-->
                                        <dialog id="add_comment" class="modal">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Add a new comment</h3>
                                                <form action="{{route('post.public.comment.create', [$post->slug, $post->id])}}" method="POST">
                                                    @csrf
                                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                                                        <div class="">
                                                            <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Pseudo')}}</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="pseudo" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full" value="{{ old('name') }}">
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <label for="about" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Content')}}</label>
                                                            <div class="mt-2">
                                                                <textarea id="about" name="content" rows="3" class="textarea textarea-primary w-full">{{ old('content') }}</textarea>
                                                            </div>
                                                            <input name="type" type="hidden" value="comment" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-action">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                </form>
                                                <form method="dialog">
                                                <!-- if there is a button in form, it will close the modal -->
                                                <button class="btn">Close</button>
                                                </form>
                                                </div>
                                            </div>
                                        </dialog>
                                        <!--End modal for adding a comment-->
                                        <div class="pl-2 flex-none">
                                            <button onclick="report_error.showModal()" class="btn btn-square btn-ghost dark:bg-base-200">
                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                    <path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <dialog id="report_error" class="modal">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Report an error</h3>
                                                <form action="{{route('post.public.comment.create', [$post->slug, $post->id])}}" method="POST">
                                                    @csrf
                                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                                                        <div class="">
                                                            <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Pseudo')}}</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="pseudo" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full" value="{{ old('name') }}">
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <label for="about" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('What is the error ?')}}</label>
                                                            <div class="mt-2">
                                                                <textarea id="about" name="content" rows="3" class="textarea textarea-error w-full">{{ old('content') }}</textarea>
                                                            </div>
                                                            <input name="type" type="hidden" value="error" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-action">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                </form>
                                                <form method="dialog">
                                                <!-- if there is a button in form, it will close the modal -->
                                                <button class="btn">Close</button>
                                                </form>
                                                </div>
                                            </div>
                                        </dialog>
                                    </div>
                                </div>
                                @forelse($comments as $comment)
                                    @if($comment->user_id == $post->user_id)
                                <div class="chat chat-start">
                                    <div class="chat-image avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="{{$post->user->profile_photo_url}}" />
                                        </div>
                                    </div>
                                    <div class="chat-header">
                                        {{$post->user->name}}
                                        <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                                    </div>
                                    <div class="chat-bubble chat-bubble-primary">{{$comment->content}}</div>
                                </div>
                                    @elseif($comment->user->hasRole('admin') and $comment->user_id != 1)
                                <div class="chat chat-start">
                                    <div class="chat-image avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="{{$comment->user->profile_photo_url}}" />
                                        </div>
                                    </div>
                                    <div class="chat-header">
                                        {{$comment->user->name}}
                                        <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                                    </div>
                                    <div class="chat-bubble chat-bubble-warning">{{$comment->content}}</div>
                                </div>
                                    @elseif($comment->user->hasRole('moderator') and $comment->user_id != 1)
                                <div class="chat chat-start">
                                    <div class="chat-image avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="{{$comment->user->profile_photo_url}}" />
                                        </div>
                                    </div>
                                    <div class="chat-header">
                                        {{$comment->user->name}}
                                        <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                                    </div>
                                    <div class="chat-bubble chat-bubble-error">{{$comment->content}}</div>
                                </div>
                                    @elseif(($comment->user->hasRole('student')) or ($comment->user_id == 1))
                                        @if($comment->type == 'comment')
                                <div class="chat chat-end">
                                    <div class="chat-image avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="
                                        @if($comment->user_id == 1)
                                        https://ui-avatars.com/api/?name={{mb_substr($comment->pseudo, 0, 1)}}&color=7F9CF5&background=EBF4FF
                                        @else
                                        {{$comment->user->profile_photo_url}}
                                        @endif" />
                                        </div>
                                    </div>
                                    <div class="chat-header">
                                        @if($comment->user_id == 1)
                                        {{$comment->pseudo}}
                                        @else
                                        {{$comment->user->name}}
                                        @endif
                                        <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                                    </div>
                                    <div class="chat-bubble">{{$comment->content}}</div>
                                </div>
                                        @elseif($comment->type =='error')
                                <div class="chat chat-end">
                                    <div class="chat-image avatar">
                                        <div class="w-10 rounded-full">
                                            <img src=" @if($comment->user_id == 1)
                                        https://ui-avatars.com/api/?name={{mb_substr($comment->pseudo, 0, 1)}}&color=7F9CF5&background=EBF4FF
                                        @else
                                        {{$comment->user->profile_photo_url}}
                                        @endif" />
                                        </div>
                                    </div>
                                    <div class="chat-header">
                                        @if($comment->user_id == 1)
                                        {{$comment->pseudo}}
                                        @else
                                        {{$comment->user->name}}
                                        @endif
                                        <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                                    </div>
                                    <div class="chat-bubble chat-bubble-error">This user reported an error : {{$comment->content}}</div>
                                </div>
                                        @endif
                                    @endif
                                @empty
                                <div class="chat chat-start">
                                    <div class="chat-image avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
                                        </div>
                                    </div>
                                    <div class="chat-header">
                                        System
                                        <time class="text-xs opacity-50">{{now()->format('d/m/y')}}</time>
                                    </div>
                                    <div class="chat-bubble">{{__('Nobody commented that post.')}}</div>
                                </div>
                                @endforelse
                            </div>
                            <div class="col-span-3 lg:col-span-1">
                                <div class="card bg-base-100 shadow-xl">
                                    <div class="card-body">
                                        <h2 class="card-title">Identification</h2>
                                        <h5>Course : <span class="text-white badge bg-{{$post->course->color}} badge-xm">{{$post->course->name}}</span></h5>
                                        <h5>Level : {{$post->level->name}}</h5>
                                        <h5>State : 
                                            @if($post->published)
                                            <span class="badge badge-success badge-sm">{{__('Published')}}</span>
                                            @else
                                            <span class="badge badge-error badge-sm">{{__('Pending')}}</span>
                                            @endif 
                                            @if($post->pinned)
                                            and <span class="badge badge-neutral badge-sm">{{__('Pinned')}}</span></h5>
                                            @endif
                                        <h5>Type : 
                                            @if($post->type == 'mindmap')
                                            <span class="badge badge-success badge-sm">{{__('Mindmap')}}</span>
                                            @elseif($post->type == 'metodo')
                                            <span class="badge badge-warning badge-sm">{{__('Metodo')}}</span>
                                            @else
                                            <span class="badge badge-info badge-sm">{{__('Revision Sheet')}}</span>
                                            @endif
                                        </h5>
                                        <h5>Date : {{$post->created_at->format('d/m/Y')}}</h5>
                                        <h5>Author : {{$post->user->name}} </h5>
                                    </div>
                                </div>
                                @if( ($files->contains('type', 'source')) or ($files->contains('type', 'exercise')) or ($files->contains('type', 'exercise correction')))
                                <div class="pt-6">
                                    <div class="card bg-base-100 shadow-xl">
                                        <div class="card-body">
                                            <h2 class="card-title">Complementary files</h2>
                                            <div class="flex items-center justify-center">
                                                <ul class="menu bg-base-200 w-56 rounded-box">
                                                    @foreach($files as $file)
                                                    @if($file->type == 'source')
                                                    <li>
                                                        <a href="{{url('storage/'.$file->file_path.'')}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M200-200v-560 560Zm80-400h400v-80H280v80Zm0 160h190q20-24 43.5-44.5T565-520H280v80Zm0 160h122q2-21 7.5-41t13.5-39H280v80Zm-80 160q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v223q-19-8-39-13.5t-41-7.5v-202H200v560h202q2 21 7.5 41t13.5 39H200Zm520 80q-73 0-127.5-45.5T524-200h62q13 44 49.5 72t84.5 28q58 0 99-41t41-99q0-58-41-99t-99-41q-29 0-54 10.5T622-340h58v60H520v-160h60v57q27-26 63-41.5t77-15.5q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/>
                                                            </svg>
                                                            {{$file->name}}
                                                        </a>
                                                    </li>
                                                    @elseif($file->type == 'exercise')
                                                    <li>
                                                        <a href="{{url('storage/'.$file->file_path.'')}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M167-120q-21 5-36.5-10.5T120-167l40-191 198 198-191 40Zm191-40L160-358l458-458q23-23 57-23t57 23l84 84q23 23 23 57t-23 57L358-160Zm317-600L261-346l85 85 414-414-85-85Z"/>
                                                            </svg>
                                                            {{$file->name}}
                                                        </a>
                                                    </li>
                                                    @elseif($file->type == 'exercise correction')
                                                    <li>
                                                        <a href="{{url('storage/'.$file->file_path.'')}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80ZM294-511l56-57 56 57 43-43-57-56 57-56-43-43-56 57-56-57-43 43 57 56-57 56 43 43Zm186 351q133 0 226.5-93.5T800-480q0-66-25-124t-69-102L254-254q44 44 102 69t124 25Zm82-96-96-96 42-43 54 54 100-99 42 42-142 142Z"/>
                                                            </svg>
                                                            {{$file->name}}
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="pt-6">
                                    <div class="card bg-base-100 shadow-xl">
                                        <div class="card-body">
                                            <h2 class="card-title">History</h2>
                                            <ol class="relative border-l border-gray-200 dark:border-gray-700">
                                                @foreach($events as $event)
                                                @if($event->type == 'bugfix')
                                                <li class="mb-10 ml-6">
                                                    <span class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -left-4  dark:bg-base-100 text-error">
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                            <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q65 0 123 19t107 53l-58 59q-38-24-81-37.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160q133 0 226.5-93.5T800-480q0-18-2-36t-6-35l65-65q11 32 17 66t6 70q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-56-216L254-466l56-56 114 114 400-401 56 56-456 457Z"/>
                                                        </svg>
                                                    </span>
                                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">Error Fix</h3>
                                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$event->created_at->format('d/m/Y')}}</time>
                                                    <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{$event->content}}</p>
                                                </li>
                                                @elseif($event->type == 'news')
                                                <li class="mb-10 ml-6">
                                                    <span class="absolute flex items-center justify-center w-8 h-8 bg-green-100 rounded-full -left-4  dark:bg-base-100 text-success">
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                            <path d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
                                                        </svg>
                                                    </span>
                                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">Add new content</h3>
                                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$event->created_at->format('d/m/Y')}}</time>
                                                    <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{$event->content}}</p>
                                                </li>
                                                @endif
                                                @endforeach
                                                <li class="mb-10 ml-6">
                                                    <span class="absolute flex items-center justify-center w-8 h-8 bg-base-100 rounded-full -left-4  dark:bg-base-100 text-primary">
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="24">
                                                            <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                                                        </svg>
                                                    </span>
                                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">Post Created</h3>
                                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$post->created_at->format('d/m/Y')}}</time>
                                                    <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Post created by {{$post->user->name}}</p>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-6">
                                    <div class="card bg-base-100 shadow-xl">
                                        <div class="card-body">
                                            <h2 class="card-title">Author</h2>
                                            <div class="flex items-center justify-center">
                                                <div class="avatar">
                                                    <div class="w-24 rounded-full ring ring-error ring-offset-base-100 ring-offset-2">
                                                        <img src="{{$post->user->profile_photo_url}}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-center">
                                                <h5>Paulhenry <span class="badge badge-error badge-sm">Admin</span></h5>
                                            </div>
                                            <div class="text-center justify-center">
                                                <p>Les information de ma bio</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </section>
            </div>
        </div>
    </div>
</div>
</div>

            </div>
        </div>
    </div>
<x-nav-bottom :active=1/>
</x-app-layout>
