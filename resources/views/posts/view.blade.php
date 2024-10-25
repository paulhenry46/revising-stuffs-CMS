<x-app-layout>
  <div class="-z-50 py-12 hidden md:block">
    <div class="max-w-7xl mx-auto ">
      <div class="">
        
        <div class=" ">
          <x-info-message />
          <div class="px-4 ">
            <div class="mt-8 flow-root">
              <div class="-mx-4 -my-2 overflow-x-auto ">
                <div class="inline-block min-w-full py-2 align-middle ">
                  <section>
                    <div class="grid grid-cols-3 gap-4">
                      <div class="col-span-3 lg:col-span-2">
                      <div class="card  bg-base-200 dark:bg-base-200 ">
            <figure class="h-[50vh] bg-{{$post->course->color}} dark:bg-none">
              <img class="opacity-75 dark:opacity-100 object-cover w-full h-full" src="{{url('storage/'.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png')}}" alt="Thumbnail of the post">
              <div class="right-0 top-0 absolute pin-t pin-l ">
                <div>
                @auth
                <livewire:favorite-button wire:key="fav_{{$post->id}}_mobile" :post=$post :user=Auth::user() lazy mobile=true/>
                @endauth
                </div>
                <div>
                <livewire:like wire:key="like_{{$post->id}}_mobile" :post=$post lazy mobile=true/>
                  
                </div>
              </div>
              @auth
              @if((Auth::id() == $post->user_id) or (Auth::user()->can('manage all posts')))
              <div class="left-14 top-2 absolute pin-t pin-l ">
                <div>
                <details class="dropdown">
  <summary class="cursor-pointer w-10 h-10  flex items-center justify-center bg-base-100  rounded-full"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M200-200h57l391-391-57-57-391 391v57Zm-40 80q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm600-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                      </svg></summary>
  <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
    <li><a wire:navigate href="{{route('posts.edit', $post->id)}}" >{{__('Edit')}}</a></li>
    <li><a wire:navigate href="{{route('files.index', $post->id)}}" >{{__('Manage files')}}</a></li>
    <li><a wire:navigate href="{{route('cards.index', $post->id)}}">{{__('Manage cards')}}</a></li>
  </ul>
</details>
                </div>
              </div>
              @endif
              @endauth
              <div class="left-0 top-0 absolute pin-t pin-l ">
                <div>
                  <a wire:navigate href="{{ url()->previous() }}">
                    <div class="w-10 h-10 absolute left-2 top-2 flex items-center justify-center bg-base-100  rounded-full">
                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="m382-480 294 294q15 15 14.5 35T675-116q-15 15-35 15t-35-15L297-423q-12-12-18-27t-6-30q0-15 6-30t18-27l308-308q15-15 35.5-14.5T676-844q15 15 15 35t-15 35L382-480Z" />
                      </svg>
                    </div>
                                </a>
                </div>
              </div>
            </figure>
            <div class=" relative card-body">
              <div class="  absolute inset-x-0 -top-16 flex justify-center items-center ">
                <div class="bg-white rounded-lg max-w-[90%] dark:border-0 border-0 border-{{$post->course->color}} min-w-[90%] justify-center content-center text-center items-center dark:bg-base-100">
                  <h2 class="mt-4 mx-1 dark:text-white text-center items-center text-2xl font-semibold">
                  {{$post->title}}
                  </h2>
                  <div class="my-5 grid grid-cols-2 gap-2">
                    <div class="text-{{$post->course->color}} text-center flex  justify-center">
                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="m297-581 149-243q6-10 15-14.5t19-4.5q10 0 19 4.5t15 14.5l149 243q6 10 6 21t-5 20q-5 9-14 14.5t-21 5.5H331q-12 0-21-5.5T296-540q-5-9-5-20t6-21ZM700-80q-75 0-127.5-52.5T520-260q0-75 52.5-127.5T700-440q75 0 127.5 52.5T880-260q0 75-52.5 127.5T700-80Zm-580-60v-240q0-17 11.5-28.5T160-420h240q17 0 28.5 11.5T440-380v240q0 17-11.5 28.5T400-100H160q-17 0-28.5-11.5T120-140Zm580-20q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-500-20h160v-160H200v160Zm202-420h156l-78-126-78 126Zm78 0ZM360-340Zm340 80Z" />
                      </svg>
                      <span class="ml-2">{{$post->course->name}}</span>
                    </div>
                    <div class="text-center flex  justify-center">
                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M200-200h57l391-391-57-57-391 391v57Zm-40 80q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm600-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                      </svg>
                      <span class="ml-2">{{$post->level->name}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-10">
                
                <div>
                @if($post->early_access)
                <div role="alert" class="alert alert-info mt-3">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    class="h-6 w-6 shrink-0 stroke-current">
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  <span>{{__('This resource is published in early access. This means that it is not in its final version and may change at any time.')}}</span>
</div>
@endif
                <h2 class="card-title mb-2 mt-4">{{__('Description')}}</h2>
                <p>{{$post->description}}</p>

                  <ul class="mt-4 menu menu-lg bg-white dark:bg-base-100 w-full rounded-box">
                  @foreach($post->files as $file)
                    @if ($file->type == 'primary light')
                    <li>
                      <a href="{{url('storage/'.$file->file_path.'')}}">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                          <path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM80-440q-17 0-28.5-11.5T40-480q0-17 11.5-28.5T80-520h80q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440H80Zm720 0q-17 0-28.5-11.5T760-480q0-17 11.5-28.5T800-520h80q17 0 28.5 11.5T920-480q0 17-11.5 28.5T880-440h-80ZM480-760q-17 0-28.5-11.5T440-800v-80q0-17 11.5-28.5T480-920q17 0 28.5 11.5T520-880v80q0 17-11.5 28.5T480-760Zm0 720q-17 0-28.5-11.5T440-80v-80q0-17 11.5-28.5T480-200q17 0 28.5 11.5T520-160v80q0 17-11.5 28.5T480-40ZM226-678l-43-42q-12-11-11.5-28t11.5-29q12-12 29-12t28 12l42 43q11 12 11 28t-11 28q-11 12-27.5 11.5T226-678Zm494 495-42-43q-11-12-11-28.5t11-27.5q11-12 27.5-11.5T734-282l43 42q12 11 11.5 28T777-183q-12 12-29 12t-28-12Zm-42-495q-12-11-11.5-27.5T678-734l42-43q11-12 28-11.5t29 11.5q12 12 12 29t-12 28l-43 42q-12 11-28 11t-28-11ZM183-183q-12-12-12-29t12-28l43-42q12-11 28.5-11t27.5 11q12 11 11.5 27.5T282-226l-42 43q-11 12-28 11.5T183-183Zm297-297Z"></path>
                        </svg>{{__('Light Version')}}</a>
                    </li>
                    @elseif ($file->type == 'primary dark')
                    <li>
                    <a href="{{url('storage/'.$file->file_path.'')}}">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                          <path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"></path>
                        </svg>{{__('Dark Version')}}</a>
                    </li>
                    @endif
                    @endforeach
                    @if($post->cards)
                    <li>
                    <a wire:navigate href="{{
                                                          route('post.public.cards.show', [$post->slug, $post->id])
                                                        }}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="m608-368 46-166-142-98-46 166 142 98ZM160-207l-33-16q-31-13-42-44.5t3-62.5l72-156v279Zm160 87q-33 0-56.5-24T240-201v-239l107 294q3 7 5 13.5t7 12.5h-39Zm206-5q-31 11-62-3t-42-45L245-662q-11-31 3-61.5t45-41.5l301-110q31-11 61.5 3t41.5 45l178 489q11 31-3 61.5T827-235L526-125Zm-28-75 302-110-179-490-301 110 178 490Zm62-300Z"/>
                                                            </svg>{{__('Cards')}}
                                                        </a>
                    </li>
                    @endif
                  </ul>
                </div>
              </div>
            </div>
          </div>
                        @if($post->cards)
                        <livewire:step-history :$post lazy /> 
                        @endif <div class="pt-6">
                          <div class="rounded-lg navbar bg-white dark:bg-base-200">
                            <div class="flex-1">
                              <a class="btn btn-ghost normal-case text-xl">{{__('Comments')}}</a>
                            </div>
                            <div class="flex-none">
                              <button onclick="add_comment.showModal()" class="btn btn-square btn-ghost dark:bg-base-100">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                  <path d="M440-400h80v-120h120v-80H520v-120h-80v120H320v80h120v120ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                                </svg>
                              </button>
                            </div>
                            <!--Modal for adding a comment-->
                            <dialog id="add_comment" class="modal">
                              <div class="modal-box">
                                <h3 class="font-bold text-lg">{{__('Add a new comment')}}</h3>
                                <form action="{{route('post.public.comment.create', [$post->slug, $post->id])}}" method="POST"> @csrf <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                                    <div class="">
                                      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Pseudo')}}</label>
                                      <div class="mt-2">
                                        <input type="text" name="pseudo" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full" @auth disabled @endauth @auth value="{{Auth::user()->name}}" @endauth @guest value="{{ old('name') }}" @endguest>
                                        @auth
                                        <input type="hidden" name="pseudo" value="null" autocomplete="off">
                                        @endauth
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
                                    <button type="submit" class="btn btn-success">{{__('Submit')}}</button>
                                </form>
                                <form method="dialog">
                                  <!-- if there is a button in form, it will close the modal -->
                                  <button class="btn">{{__('Close')}}</button>
                                </form>
                              </div>
                          </div>
                          </dialog>
                          <!--End modal for adding a comment-->
                          <div class="pl-2 flex-none">
                            <button onclick="report_error.showModal()" class="btn btn-square btn-ghost dark:bg-base-100">
                              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                <path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                              </svg>
                            </button>
                          </div>
                          <dialog id="report_error" class="modal">
                            <div class="modal-box">
                              <h3 class="font-bold text-lg">{{__('Report an error')}}</h3>
                              <form action="{{route('post.public.comment.create', [$post->slug, $post->id])}}" method="POST"> @csrf <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                              <div class="">
                                      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Pseudo')}}</label>
                                      <div class="mt-2">
                                        <input type="text" name="pseudo" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full" @auth disabled @endauth @auth value="{{Auth::user()->name}}" @endauth @guest value="{{ old('name') }}" @endguest>
                                        @auth
                                        <input type="hidden" name="pseudo" value="null" autocomplete="off">
                                        @endauth
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
                                  <button type="submit" class="btn btn-success">{{__('Submit')}}</button>
                              </form>
                              <form method="dialog">
                                <!-- if there is a button in form, it will close the modal -->
                                <button class="btn">{{__('Close')}}</button>
                              </form>
                            </div>
                        </div>
                        </dialog>
                      </div>
                    </div> 
                    @forelse($comments as $comment) @if($comment->user_id == $post->user_id) <div class="chat chat-start">
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
                    </div> @elseif($comment->user->hasRole('admin') and $comment->user_id != 1) <div class="chat chat-start">
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
                    </div> @elseif($comment->user->hasRole('moderator') and $comment->user_id != 1) <div class="chat chat-start">
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
                    </div> @elseif(($comment->user->hasRole('student')) or ($comment->user_id == 1)) @if($comment->type == 'comment') <div class="chat chat-end"> @if($comment->user_id == 1) <div class="chat-image">
                        <div class="w-10 bg-info text-info-content rounded-full flex h-10 justify-center items-center">
                          <div class="text-center">
                            <p>{{strtoupper(mb_substr($comment->pseudo, 0, 1))}}</p>
                          </div>
                        </div>
                      </div> @else <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                          <img src="{{$comment->user->profile_photo_url}}" />
                        </div>
                      </div> @endif <div class="chat-header"> @if($comment->user_id == 1) {{$comment->pseudo}} @else {{$comment->user->name}} @endif <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                      </div>
                      <div class="chat-bubble">{{$comment->content}}</div>
                    </div> @elseif($comment->type =='error') <div class="chat chat-end"> @if($comment->user_id == 1) <div class="chat-image">
                        <div class="w-10 bg-info text-info-content rounded-full flex h-10 justify-center items-center">
                          <div class="text-center">
                            <p>{{strtoupper(mb_substr($comment->pseudo, 0, 1))}}</p>
                          </div>
                        </div>
                      </div> @else <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                          <img src="{{$comment->user->profile_photo_url}}" />
                        </div>
                      </div> @endif <div class="chat-header"> @if($comment->user_id == 1) {{$comment->pseudo}} @else {{$comment->user->name}} @endif <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                      </div>
                      <div class="chat-bubble chat-bubble-error">{{('This user reported an error')}} : {{$comment->content}}</div>
                    </div> @endif @endif @empty <div class=" text-lg  pt-6 text-center">
                      {{__('Nobody commented that post.')}}
                    </div> @endforelse
                </div>
                <div class="col-span-3 lg:col-span-1"> @if( ($files->contains('type', 'source')) or ($files->contains('type', 'exercise')) or ($files->contains('type', 'other')) or ($files->contains('type', 'exercise correction'))) <ul class="menu dark:bg-base-200 bg-white w-full rounded-box">
                    <li>
                      <h2 class="card-title ">{{__('Complementary files')}}</h2>
                      <ul> @foreach($files as $file) @if($file->type == 'source') <li>
                          <a href="{{url('storage/'.$file->file_path.'')}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                              <path d="M200-200v-560 560Zm80-400h400v-80H280v80Zm0 160h190q20-24 43.5-44.5T565-520H280v80Zm0 160h122q2-21 7.5-41t13.5-39H280v80Zm-80 160q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v223q-19-8-39-13.5t-41-7.5v-202H200v560h202q2 21 7.5 41t13.5 39H200Zm520 80q-73 0-127.5-45.5T524-200h62q13 44 49.5 72t84.5 28q58 0 99-41t41-99q0-58-41-99t-99-41q-29 0-54 10.5T622-340h58v60H520v-160h60v57q27-26 63-41.5t77-15.5q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z" />
                            </svg>
                            {{$file->name}}
                          </a>
                        </li> @elseif($file->type == 'other') <li>
                          <a href="{{url('storage/'.$file->file_path.'')}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                              <path d="M280-420q25 0 42.5-17.5T340-480q0-25-17.5-42.5T280-540q-25 0-42.5 17.5T220-480q0 25 17.5 42.5T280-420Zm200 0q25 0 42.5-17.5T540-480q0-25-17.5-42.5T480-540q-25 0-42.5 17.5T420-480q0 25 17.5 42.5T480-420Zm200 0q25 0 42.5-17.5T740-480q0-25-17.5-42.5T680-540q-25 0-42.5 17.5T620-480q0 25 17.5 42.5T680-420ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                            </svg>
                            {{$file->name}}
                          </a>
                        </li> @elseif($file->type == 'exercise') <li>
                          <a href="{{url('storage/'.$file->file_path.'')}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                              <path d="M167-120q-21 5-36.5-10.5T120-167l40-191 198 198-191 40Zm191-40L160-358l458-458q23-23 57-23t57 23l84 84q23 23 23 57t-23 57L358-160Zm317-600L261-346l85 85 414-414-85-85Z" />
                            </svg>
                            {{$file->name}}
                          </a>
                        </li> @elseif($file->type == 'exercise correction') <li>
                          <a href="{{url('storage/'.$file->file_path.'')}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                              <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80ZM294-511l56-57 56 57 43-43-57-56 57-56-43-43-56 57-56-57-43 43 57 56-57 56 43 43Zm186 351q133 0 226.5-93.5T800-480q0-66-25-124t-69-102L254-254q44 44 102 69t124 25Zm82-96-96-96 42-43 54 54 100-99 42 42-142 142Z" />
                            </svg>
                            {{$file->name}}
                          </a>
                        </li> @endif @endforeach </ul>
                    </li>
                  </ul>
                  <div class="pt-6"> @else <div class=""> @endif <div class="card dark:bg-base-200 bg-white">
                        <div class="card-body">
                          <h2 class="card-title">{{__('History')}}</h2>
                          <ol class="relative border-l border-gray-200 dark:border-gray-700"> @foreach($events as $event) @if($event->type == 'bugfix') <li class="mb-10 ml-6">
                              <span class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -left-4  dark:bg-base-100 text-error">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                  <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q65 0 123 19t107 53l-58 59q-38-24-81-37.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160q133 0 226.5-93.5T800-480q0-18-2-36t-6-35l65-65q11 32 17 66t6 70q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-56-216L254-466l56-56 114 114 400-401 56 56-456 457Z" />
                                </svg>
                              </span>
                              <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">{{__('Error Fix')}}</h3>
                              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$event->created_at->format('d/m/Y')}}</time>
                              <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{$event->content}}</p>
                            </li> @elseif($event->type == 'news') <li class="mb-10 ml-6">
                              <span class="absolute flex items-center justify-center w-8 h-8 bg-green-100 rounded-full -left-4  dark:bg-base-100 text-success">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                  <path d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                                </svg>
                              </span>
                              <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">{{__('Add new content')}}</h3>
                              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$event->created_at->format('d/m/Y')}}</time>
                              <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{$event->content}}</p>
                            </li> @endif @endforeach <li class="mb-10 ml-6">
                              <span class="absolute flex items-center justify-center w-8 h-8 bg-base-100 rounded-full -left-4  dark:bg-base-100 text-primary">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="24">
                                  <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                </svg>
                              </span>
                              <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">{{__('Post Created')}}</h3>
                              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$post->created_at->format('d/m/Y')}}</time>
                              <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{__('Post created by')}} {{$post->user->name}}</p>
                            </li>
                          </ol>
                        </div>
                      </div>
                    </div>
                    <div class="pt-6">
                      <div class="card dark:bg-base-200 bg-white ">
                        <div class="card-body">
                          <h2 class="card-title">{{__('Author')}}</h2>
                          @php
                            $user = $post->user;
                          @endphp
                         <div class="relative z-0 mt-1 flex h-32 w-full justify-center rounded-xl bg-cover bg-primary">
                <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-base-100">
                  <img class="h-full w-full rounded-full" src="{{$user->profile_photo_url}}" alt="" />
                </div>
              </div>
              <div class="mt-16 flex flex-col items-center justify-center">
                <h4 class="flex text-primary text-xl font-bold">
                  <p class="mr-1">{{$user->name}}</p> @if($user->hasRole('admin')) <p class="flex items-center text-error tooltip" data-tip="{{__('Administrator')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                      <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                    </svg>
                  </p> @elseif($user->hasRole('moderator')) <p class="flex items-center text-warning tooltip" data-tip="{{__('Moderator')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                      <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                    </svg>
                  </p> @elseif($user->hasRole('contributor')) <p class="flex items-center text-primary tooltip" data-tip="{{__('Contributor')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                      <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                    </svg>
                  </p> @endif
                </h4> 
                <p>{{$user->curriculum->name}} ({{$user->level->name}})  · {{$user->school->name}}</p>
                @if($user->social_network_link !== NULL) @php $link = $user->social_network_link; @endphp <div class="flex justify-center">
                  <a href="{{$user->social_network_link}}" class=""> @if (str_contains($link, 'instagram.com')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M 8 3 C 5.239 3 3 5.239 3 8 L 3 16 C 3 18.761 5.239 21 8 21 L 16 21 C 18.761 21 21 18.761 21 16 L 21 8 C 21 5.239 18.761 3 16 3 L 8 3 z M 18 5 C 18.552 5 19 5.448 19 6 C 19 6.552 18.552 7 18 7 C 17.448 7 17 6.552 17 6 C 17 5.448 17.448 5 18 5 z M 12 7 C 14.761 7 17 9.239 17 12 C 17 14.761 14.761 17 12 17 C 9.239 17 7 14.761 7 12 C 7 9.239 9.239 7 12 7 z M 12 9 A 3 3 0 0 0 9 12 A 3 3 0 0 0 12 15 A 3 3 0 0 0 15 12 A 3 3 0 0 0 12 9 z"></path>
                    </svg> @elseif (str_contains($link, 'github.com')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M10.9,2.1c-4.6,0.5-8.3,4.2-8.8,8.7c-0.5,4.7,2.2,8.9,6.3,10.5C8.7,21.4,9,21.2,9,20.8v-1.6c0,0-0.4,0.1-0.9,0.1 c-1.4,0-2-1.2-2.1-1.9c-0.1-0.4-0.3-0.7-0.6-1C5.1,16.3,5,16.3,5,16.2C5,16,5.3,16,5.4,16c0.6,0,1.1,0.7,1.3,1c0.5,0.8,1.1,1,1.4,1 c0.4,0,0.7-0.1,0.9-0.2c0.1-0.7,0.4-1.4,1-1.8c-2.3-0.5-4-1.8-4-4c0-1.1,0.5-2.2,1.2-3C7.1,8.8,7,8.3,7,7.6c0-0.4,0-0.9,0.2-1.3 C7.2,6.1,7.4,6,7.5,6c0,0,0.1,0,0.1,0C8.1,6.1,9.1,6.4,10,7.3C10.6,7.1,11.3,7,12,7s1.4,0.1,2,0.3c0.9-0.9,2-1.2,2.5-1.3 c0,0,0.1,0,0.1,0c0.2,0,0.3,0.1,0.4,0.3C17,6.7,17,7.2,17,7.6c0,0.8-0.1,1.2-0.2,1.4c0.7,0.8,1.2,1.8,1.2,3c0,2.2-1.7,3.5-4,4 c0.6,0.5,1,1.4,1,2.3v2.6c0,0.3,0.3,0.6,0.7,0.5c3.7-1.5,6.3-5.1,6.3-9.3C22,6.1,16.9,1.4,10.9,2.1z"></path>
                    </svg> @elseif (str_contains($link, 'tiktok.com')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M 6 3 C 4.3550302 3 3 4.3550302 3 6 L 3 18 C 3 19.64497 4.3550302 21 6 21 L 18 21 C 19.64497 21 21 19.64497 21 18 L 21 6 C 21 4.3550302 19.64497 3 18 3 L 6 3 z M 12 7 L 14 7 C 14 8.005 15.471 9 16 9 L 16 11 C 15.395 11 14.668 10.734156 14 10.285156 L 14 14 C 14 15.654 12.654 17 11 17 C 9.346 17 8 15.654 8 14 C 8 12.346 9.346 11 11 11 L 11 13 C 10.448 13 10 13.449 10 14 C 10 14.551 10.448 15 11 15 C 11.552 15 12 14.551 12 14 L 12 7 z"></path>
                    </svg> @elseif (str_contains($link, 'discord.com')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M21,23l-4.378-3.406L17,21H5c-1.105,0-2-0.895-2-2V5c0-1.105,0.895-2,2-2h14c1.105,0,2,0.895,2,2V23z M16.29,8.57	c0,0-1.23-0.95-2.68-1.06l-0.3,0.61C12.86,8.04,12.4,7.98,12,7.98c-0.41,0-0.88,0.06-1.31,0.14l-0.3-0.61	C8.87,7.66,7.71,8.57,7.71,8.57s-1.37,1.98-1.6,5.84C7.49,15.99,9.59,16,9.59,16l0.43-0.58c-0.44-0.15-0.82-0.35-1.21-0.65	l0.09-0.24c0.72,0.33,1.65,0.53,3.1,0.53s2.38-0.2,3.1-0.53l0.09,0.24c-0.39,0.3-0.77,0.5-1.21,0.65L14.41,16	c0,0,2.1-0.01,3.48-1.59C17.66,10.55,16.29,8.57,16.29,8.57z M10,13.38c-0.52,0-0.94-0.53-0.94-1.18c0-0.65,0.42-1.18,0.94-1.18	s0.94,0.53,0.94,1.18C10.94,12.85,10.52,13.38,10,13.38z M14,13.38c-0.52,0-0.94-0.53-0.94-1.18c0-0.65,0.42-1.18,0.94-1.18	s0.94,0.53,0.94,1.18C14.94,12.85,14.52,13.38,14,13.38z"></path>
                    </svg> @elseif (str_contains($link, 'tg.me')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M 20.302734 2.984375 C 20.013769 2.996945 19.748583 3.080055 19.515625 3.171875 C 19.300407 3.256634 18.52754 3.5814726 17.296875 4.0976562 C 16.06621 4.61384 14.435476 5.2982348 12.697266 6.0292969 C 9.2208449 7.4914211 5.314238 9.1361259 3.3125 9.9785156 C 3.243759 10.007156 2.9645852 10.092621 2.65625 10.328125 C 2.3471996 10.564176 2.0039062 11.076462 2.0039062 11.636719 C 2.0039062 12.088671 2.2295201 12.548966 2.5019531 12.8125 C 2.7743861 13.076034 3.0504903 13.199244 3.28125 13.291016 L 3.28125 13.289062 C 4.0612776 13.599827 6.3906939 14.531938 6.9453125 14.753906 C 7.1420423 15.343433 7.9865895 17.867278 8.1875 18.501953 L 8.1855469 18.501953 C 8.3275588 18.951162 8.4659791 19.243913 8.6582031 19.488281 C 8.7543151 19.610465 8.8690398 19.721184 9.0097656 19.808594 C 9.0637596 19.842134 9.1235454 19.868148 9.1835938 19.892578 C 9.191962 19.896131 9.2005867 19.897012 9.2089844 19.900391 L 9.1855469 19.894531 C 9.2029579 19.901531 9.2185841 19.911859 9.2363281 19.917969 C 9.2652427 19.927926 9.2852873 19.927599 9.3242188 19.935547 C 9.4612233 19.977694 9.5979794 20.005859 9.7246094 20.005859 C 10.26822 20.005859 10.601562 19.710937 10.601562 19.710938 L 10.623047 19.695312 L 12.970703 17.708984 L 15.845703 20.369141 C 15.898217 20.443289 16.309604 21 17.261719 21 C 17.829844 21 18.279025 20.718791 18.566406 20.423828 C 18.853787 20.128866 19.032804 19.82706 19.113281 19.417969 L 19.115234 19.416016 C 19.179414 19.085834 21.931641 5.265625 21.931641 5.265625 L 21.925781 5.2890625 C 22.011441 4.9067171 22.036735 4.5369631 21.935547 4.1601562 C 21.834358 3.7833495 21.561271 3.4156252 21.232422 3.2226562 C 20.903572 3.0296874 20.591699 2.9718046 20.302734 2.984375 z M 19.908203 5.1738281 C 19.799442 5.7198576 17.33401 18.105877 17.181641 18.882812 L 13.029297 15.041016 L 10.222656 17.414062 L 11 14.375 C 11 14.375 16.362547 8.9468594 16.685547 8.6308594 C 16.945547 8.3778594 17 8.2891719 17 8.2011719 C 17 8.0841719 16.939781 8 16.800781 8 C 16.675781 8 16.506016 8.1197812 16.416016 8.1757812 C 15.272368 8.8887854 10.401283 11.664685 8.0058594 13.027344 C 7.8617016 12.96954 5.6973962 12.100458 4.53125 11.634766 C 6.6055146 10.76177 10.161156 9.2658083 13.472656 7.8730469 C 15.210571 7.142109 16.840822 6.4570977 18.070312 5.9414062 C 19.108158 5.5060977 19.649538 5.2807035 19.908203 5.1738281 z M 17.152344 19.025391 C 17.152344 19.025391 17.154297 19.025391 17.154297 19.025391 C 17.154252 19.025621 17.152444 19.03095 17.152344 19.03125 C 17.153615 19.024789 17.15139 19.03045 17.152344 19.025391 z"></path>
                    </svg> @else <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                      <path d="M376-178q-18-33-31.5-68.5T322-320H204q29 50 72.5 87t99.5 55ZM170-400h136q-3-20-4.5-39.5T300-480q0-21 1.5-40.5T306-560H170q-5 20-7.5 39.5T160-480q0 21 2.5 40.5T170-400Zm34-240h118q9-38 22.5-73.5T376-782q-56 18-99.5 55T204-640Zm200 0h152q-12-44-31-83t-45-75q-26 36-45 75t-31 83Zm234 0h118q-29-50-72.5-87T584-782q18 33 31.5 68.5T638-640ZM480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-155.5t86-127Q252-817 325-848.5T480-880q83 0 155.5 31.5t127 86q54.5 54.5 86 127T880-480q0 10-.5 20t-1.5 20h-81q2-10 2.5-19.5t.5-20.5q0-21-2.5-40.5T790-560H654q3 20 4.5 39.5T660-480v20.5q0 9.5-1 19.5h-80q1-10 1-19.5V-480q0-21-1.5-40.5T574-560H386q-3 20-4.5 39.5T380-480q0 21 1.5 40.5T386-400h134v80H404q12 44 31 83t45 75q11-16 21-32.5t19-33.5v146q-10 1-19.5 1.5T480-80Zm318-25L680-223v89h-80v-226h226v80h-90l118 118-56 57Z" />
                    </svg> @endif </a>
                </div> @endif
              </div>
                         
                          <div class="divider"></div> <a href="{{route('users.public.view', $post->user->id)}}">{{__('See more about this user')}}</a> 
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
  <div class="md:hidden">
    <div>
      <div class=" ">
        <div class="pt-4 col-span-4 lg:col-span-1">
          <div class="card   dark:bg-base-300 ">
            <figure class="h-96 bg-{{$post->course->color}} dark:bg-none">
              <img class="opacity-75 dark:opacity-100 object-cover w-full h-full" src="{{url('storage/'.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png')}}" alt="Thumbnail of the post">
              <div class="right-0 top-0 absolute pin-t pin-l ">
                <div>
                @auth
                <livewire:favorite-button wire:key="fav_{{$post->id}}_mobile" :post=$post :user=Auth::user() lazy mobile=true/>
                @endauth
                </div>
                <div>
                <livewire:like wire:key="like_{{$post->id}}_mobile" :post=$post lazy mobile=true/>
                  
                </div>
              </div>
              <div class="left-0 top-0 absolute pin-t pin-l ">
                <div>
                  <a wire:navigate href="{{ url()->previous() }}">
                    <div class="w-10 h-10 absolute left-2 top-2 flex items-center justify-center bg-base-200 dark:bg-base-100 rounded-full">
                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="m382-480 294 294q15 15 14.5 35T675-116q-15 15-35 15t-35-15L297-423q-12-12-18-27t-6-30q0-15 6-30t18-27l308-308q15-15 35.5-14.5T676-844q15 15 15 35t-15 35L382-480Z" />
                      </svg>
                    </div>
                                </a>
                </div>
              </div>
            </figure>
            <div class=" relative card-body">
              <div class="  absolute inset-x-0 -top-16 flex justify-center items-center ">
                <div class="bg-white rounded-lg max-w-[90%] dark:border-0 border-0 border-{{$post->course->color}} min-w-[90%] justify-center content-center text-center items-center dark:bg-base-200">
                  <h2 class="mt-4 mx-1 dark:text-white text-center items-center text-2xl font-semibold">
                  {{$post->title}}
                  </h2>
                  <div class="my-5 grid grid-cols-2 gap-2">
                    <div class="text-{{$post->course->color}} text-center flex  justify-center">
                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="m297-581 149-243q6-10 15-14.5t19-4.5q10 0 19 4.5t15 14.5l149 243q6 10 6 21t-5 20q-5 9-14 14.5t-21 5.5H331q-12 0-21-5.5T296-540q-5-9-5-20t6-21ZM700-80q-75 0-127.5-52.5T520-260q0-75 52.5-127.5T700-440q75 0 127.5 52.5T880-260q0 75-52.5 127.5T700-80Zm-580-60v-240q0-17 11.5-28.5T160-420h240q17 0 28.5 11.5T440-380v240q0 17-11.5 28.5T400-100H160q-17 0-28.5-11.5T120-140Zm580-20q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-500-20h160v-160H200v160Zm202-420h156l-78-126-78 126Zm78 0ZM360-340Zm340 80Z" />
                      </svg>
                      <span class="ml-2">{{$post->course->name}}</span>
                    </div>
                    <div class="text-center flex  justify-center">
                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M200-200h57l391-391-57-57-391 391v57Zm-40 80q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm600-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                      </svg>
                      <span class="ml-2">{{$post->level->name}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-24" x-data="{ activeTab:  0 }">
                <div class="grid grid-flow-col justify-stretch">
                  <button class="btn btn-primary mr-1" @click="activeTab = 0" class="tab-control" :class="{ 'btn-ghost': activeTab !== 0 }">Accueil</button>
                  @if($post->cards)<button class="btn btn-primary mr-1" @click="activeTab = 1" class="tab-control" :class="{ 'btn-ghost': activeTab !== 1 }">{{__('Cards')}}</button>@endif
                  <button class="btn btn-primary" @click="activeTab = 2" class="tab-control" :class="{ 'btn-ghost': activeTab !== 2 }">Autre</button>
                </div>
                <div id="accueil" x-show.transition.in.opacity.duration.600="activeTab === 0">
                  @if($post->early_access)
                <div role="alert" class="alert alert-info mt-3">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    class="h-6 w-6 shrink-0 stroke-current">
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  <span>{{__('This resource is published in early access. This means that it is not in its final version and may change at any time.')}}</span>
</div>
@endif
                <h2 class="card-title mb-2 mt-4">{{__('Description')}}</h2>
                <p>{{$post->description}}</p>
                <h2 class="card-title mb-2 mt-4">{{__('Download')}}</h2>
                  <ul class="mt-4 menu menu-lg bg-white dark:bg-base-100 w-full rounded-box">
                  @foreach($post->files as $file)
                    @if ($file->type == 'primary light')
                    <li>
                      <a href="{{url('storage/'.$file->file_path.'')}}">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                          <path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM80-440q-17 0-28.5-11.5T40-480q0-17 11.5-28.5T80-520h80q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440H80Zm720 0q-17 0-28.5-11.5T760-480q0-17 11.5-28.5T800-520h80q17 0 28.5 11.5T920-480q0 17-11.5 28.5T880-440h-80ZM480-760q-17 0-28.5-11.5T440-800v-80q0-17 11.5-28.5T480-920q17 0 28.5 11.5T520-880v80q0 17-11.5 28.5T480-760Zm0 720q-17 0-28.5-11.5T440-80v-80q0-17 11.5-28.5T480-200q17 0 28.5 11.5T520-160v80q0 17-11.5 28.5T480-40ZM226-678l-43-42q-12-11-11.5-28t11.5-29q12-12 29-12t28 12l42 43q11 12 11 28t-11 28q-11 12-27.5 11.5T226-678Zm494 495-42-43q-11-12-11-28.5t11-27.5q11-12 27.5-11.5T734-282l43 42q12 11 11.5 28T777-183q-12 12-29 12t-28-12Zm-42-495q-12-11-11.5-27.5T678-734l42-43q11-12 28-11.5t29 11.5q12 12 12 29t-12 28l-43 42q-12 11-28 11t-28-11ZM183-183q-12-12-12-29t12-28l43-42q12-11 28.5-11t27.5 11q12 11 11.5 27.5T282-226l-42 43q-11 12-28 11.5T183-183Zm297-297Z"></path>
                        </svg>{{__('Light Version')}}</a>
                    </li>
                    @elseif ($file->type == 'primary dark')
                    <li>
                    <a href="{{url('storage/'.$file->file_path.'')}}">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                          <path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"></path>
                        </svg>{{__('Dark Version')}}</a>
                    </li>
                    @endif
                    @endforeach
                  </ul>
                  <div class="pt-6">
                    <div class="rounded-lg navbar bg-white  dark:bg-base-200">
                      <div class="flex-1 ml-3">
                        <a class="card-title normal-case text-xl">{{__('Comments')}}</a>
                      </div>
                      <div class="flex-none">
                        <button onclick="add_comment_mobile.showModal()" class="btn btn-square btn-ghost dark:bg-base-100">
                          <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M440-400h80v-120h120v-80H520v-120h-80v120H320v80h120v120ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"></path>
                          </svg>
                        </button>
                      </div>
                      <!--Modal for adding a comment-->
                      <dialog id="add_comment_mobile" class="modal">
                        <div class="modal-box">
                          <h3 class="font-bold text-lg">{{__('Add a new comment')}}</h3>
                          <form action="{{route('post.public.comment.create', [$post->slug, $post->id])}}" method="POST">
                          @csrf
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                            <div class="">
                                      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Pseudo')}}</label>
                                      <div class="mt-2">
                                        <input type="text" name="pseudo" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full" @auth disabled @endauth @auth value="{{Auth::user()->name}}" @endauth @guest value="{{ old('name') }}" @endguest>
                                        @auth
                                        <input type="hidden" name="pseudo" value="null" autocomplete="off">
                                        @endauth
                                      </div>
                                    </div>
                              <div class="">
                                <label for="about" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">Contenu</label>
                                <div class="mt-2">
                                  <textarea id="about" name="content" rows="3" class="textarea textarea-primary w-full"></textarea>
                                </div>
                                <input name="type" type="hidden" value="comment">
                              </div>
                            </div>
                            <div class="modal-action">
                              <button type="submit" class="btn btn-success">{{__('Send')}}</button>
                              <form method="dialog">
                                <!-- if there is a button in form, it will close the modal -->
                                <button class="btn">Fermer</button>
                              </form>
                            </div>
                          </form>
                        </div>
                      </dialog>
                      <!--End modal for adding a comment-->
                      <div class="pl-2 flex-none">
                        <button onclick="report_error_mobile.showModal()" class="btn btn-square btn-ghost dark:bg-base-100">
                          <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"></path>
                          </svg>
                        </button>
                      </div>
                      <dialog id="report_error_mobile" class="modal">
                        <div class="modal-box">
                          <h3 class="font-bold text-lg">{{__('Report an error')}}</h3>
                          <form action="{{route('post.public.comment.create', [$post->slug, $post->id])}}" method="POST">
                          @csrf
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                            <div class="">
                                      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Pseudo')}}</label>
                                      <div class="mt-2">
                                        <input type="text" name="pseudo" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full" @auth disabled @endauth @auth value="{{Auth::user()->name}}" @endauth @guest value="{{ old('name') }}" @endguest>
                                        @auth
                                        <input type="hidden" name="pseudo" value="null" autocomplete="off">
                                        @endauth
                                      </div>
                                    </div>
                              <div class="">
                                <label for="about" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">Quelle est l'erreur ?</label>
                                <div class="mt-2">
                                  <textarea id="about" name="content" rows="3" class="textarea textarea-error w-full"></textarea>
                                </div>
                                <input name="type" type="hidden" value="error">
                              </div>
                            </div>
                            <div class="modal-action">
                              <button type="submit" class="btn btn-success">{{__('Send')}}</button>
                            </div>
                          </form>
                          <form method="dialog">
                            <!-- if there is a button in form, it will close the modal -->
                            <button class="btn">{{__('Close')}}</button>
                          </form>
                        </div>
                      </dialog>
                    </div>
                  </div>
                  @forelse($comments as $comment) @if($comment->user_id == $post->user_id) <div class="chat chat-start">
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
                    </div> @elseif($comment->user->hasRole('admin') and $comment->user_id != 1) <div class="chat chat-start">
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
                    </div> @elseif($comment->user->hasRole('moderator') and $comment->user_id != 1) <div class="chat chat-start">
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
                    </div> @elseif(($comment->user->hasRole('student')) or ($comment->user_id == 1)) @if($comment->type == 'comment') <div class="chat chat-end"> @if($comment->user_id == 1) <div class="chat-image">
                        <div class="w-10 bg-info text-info-content rounded-full flex h-10 justify-center items-center">
                          <div class="text-center">
                            <p>{{strtoupper(mb_substr($comment->pseudo, 0, 1))}}</p>
                          </div>
                        </div>
                      </div> @else <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                          <img src="{{$comment->user->profile_photo_url}}" />
                        </div>
                      </div> @endif <div class="chat-header"> @if($comment->user_id == 1) {{$comment->pseudo}} @else {{$comment->user->name}} @endif <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                      </div>
                      <div class="chat-bubble">{{$comment->content}}</div>
                    </div> @elseif($comment->type =='error') <div class="chat chat-end"> @if($comment->user_id == 1) <div class="chat-image">
                        <div class="w-10 bg-info text-info-content rounded-full flex h-10 justify-center items-center">
                          <div class="text-center">
                            <p>{{strtoupper(mb_substr($comment->pseudo, 0, 1))}}</p>
                          </div>
                        </div>
                      </div> @else <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                          <img src="{{$comment->user->profile_photo_url}}" />
                        </div>
                      </div> @endif <div class="chat-header"> @if($comment->user_id == 1) {{$comment->pseudo}} @else {{$comment->user->name}} @endif <time class="text-xs opacity-50">{{$comment->created_at->format('d/m/y')}}</time>
                      </div>
                      <div class="chat-bubble chat-bubble-error">{{('This user reported an error')}} : {{$comment->content}}</div>
                    </div> @endif @endif @empty <div class=" text-lg  pt-4 text-center">
                      {{__('Nobody commented that post.')}}
                    </div> @endforelse
                </div>
                @if($post->cards)
                <div id="cards" x-show.transition.in.opacity.duration.600="activeTab === 1">
                  <ul class="mt-4 menu menu-lg bg-base-200 dark:bg-base-100 w-full rounded-box">
                    <li>
                    <a wire:navigate href="{{
                                                          route('post.public.cards.show', [$post->slug, $post->id])
                                                        }}">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                          <path d="m608-368 46-166-142-98-46 166 142 98ZM160-207l-33-16q-31-13-42-44.5t3-62.5l72-156v279Zm160 87q-33 0-56.5-24T240-201v-239l107 294q3 7 5 13.5t7 12.5h-39Zm206-5q-31 11-62-3t-42-45L245-662q-11-31 3-61.5t45-41.5l301-110q31-11 61.5 3t41.5 45l178 489q11 31-3 61.5T827-235L526-125Zm-28-75 302-110-179-490-301 110 178 490Zm62-300Z"></path>
                        </svg>{{__('See cards')}} </a>
                    </li>
                  </ul>
                  @if($post->cards)
                        <livewire:step-history :$post lazy /> @endif
                </div>
                @endif
                <div id="others" x-show.transition.in.opacity.duration.600="activeTab === 2">
                @if( ($files->contains('type', 'source')) or ($files->contains('type', 'exercise')) or ($files->contains('type', 'other')) or ($files->contains('type', 'exercise correction')))
                  <h2 class="card-title mt-4">{{__('Complementary files')}}</h2>
                  <ul class="mt-4 menu menu-lg bg-base-200 w-full rounded-box">
                   @foreach($files as $file) @if($file->type == 'source') <li>
                          <a href="{{url('storage/'.$file->file_path.'')}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                              <path d="M200-200v-560 560Zm80-400h400v-80H280v80Zm0 160h190q20-24 43.5-44.5T565-520H280v80Zm0 160h122q2-21 7.5-41t13.5-39H280v80Zm-80 160q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v223q-19-8-39-13.5t-41-7.5v-202H200v560h202q2 21 7.5 41t13.5 39H200Zm520 80q-73 0-127.5-45.5T524-200h62q13 44 49.5 72t84.5 28q58 0 99-41t41-99q0-58-41-99t-99-41q-29 0-54 10.5T622-340h58v60H520v-160h60v57q27-26 63-41.5t77-15.5q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z" />
                            </svg>
                            {{$file->name}}
                          </a>
                        </li> @elseif($file->type == 'other') <li>
                          <a href="{{url('storage/'.$file->file_path.'')}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                              <path d="M280-420q25 0 42.5-17.5T340-480q0-25-17.5-42.5T280-540q-25 0-42.5 17.5T220-480q0 25 17.5 42.5T280-420Zm200 0q25 0 42.5-17.5T540-480q0-25-17.5-42.5T480-540q-25 0-42.5 17.5T420-480q0 25 17.5 42.5T480-420Zm200 0q25 0 42.5-17.5T740-480q0-25-17.5-42.5T680-540q-25 0-42.5 17.5T620-480q0 25 17.5 42.5T680-420ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                            </svg>
                            {{$file->name}}
                          </a>
                        </li> @elseif($file->type == 'exercise') <li>
                          <a href="{{url('storage/'.$file->file_path.'')}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                              <path d="M167-120q-21 5-36.5-10.5T120-167l40-191 198 198-191 40Zm191-40L160-358l458-458q23-23 57-23t57 23l84 84q23 23 23 57t-23 57L358-160Zm317-600L261-346l85 85 414-414-85-85Z" />
                            </svg>
                            {{$file->name}}
                          </a>
                        </li> @elseif($file->type == 'exercise correction') <li>
                          <a href="{{url('storage/'.$file->file_path.'')}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                              <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80ZM294-511l56-57 56 57 43-43-57-56 57-56-43-43-56 57-56-57-43 43 57 56-57 56 43 43Zm186 351q133 0 226.5-93.5T800-480q0-66-25-124t-69-102L254-254q44 44 102 69t124 25Zm82-96-96-96 42-43 54 54 100-99 42 42-142 142Z" />
                            </svg>
                            {{$file->name}}
                          </a>
                        </li> @endif @endforeach 
                  </ul>
                  @endif
                  <div class="">
                    <div class="">
                      <h2 class="card-title mb-2 mt-4">{{__('History')}}</h2>
                      <ol class="relative border-l border-gray-200 dark:border-gray-700"> @foreach($events as $event) @if($event->type == 'bugfix') <li class="mb-10 ml-6">
                              <span class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -left-4  dark:bg-base-100 text-error">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                  <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q65 0 123 19t107 53l-58 59q-38-24-81-37.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160q133 0 226.5-93.5T800-480q0-18-2-36t-6-35l65-65q11 32 17 66t6 70q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-56-216L254-466l56-56 114 114 400-401 56 56-456 457Z" />
                                </svg>
                              </span>
                              <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">{{__('Error Fix')}}</h3>
                              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$event->created_at->format('d/m/Y')}}</time>
                              <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{$event->content}}</p>
                            </li> @elseif($event->type == 'news') <li class="mb-10 ml-6">
                              <span class="absolute flex items-center justify-center w-8 h-8 bg-green-100 rounded-full -left-4  dark:bg-base-100 text-success">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                  <path d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                                </svg>
                              </span>
                              <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">{{__('Add new content')}}</h3>
                              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$event->created_at->format('d/m/Y')}}</time>
                              <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{$event->content}}</p>
                            </li> @endif @endforeach <li class="mb-10 ml-6">
                              <span class="absolute flex items-center justify-center w-8 h-8 bg-base-100 rounded-full -left-4  dark:bg-base-100 text-primary">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="24">
                                  <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                </svg>
                              </span>
                              <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">{{__('Post Created')}}</h3>
                              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$post->created_at->format('d/m/Y')}}</time>
                              <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{__('Post created by')}} <a href="{{route('users.public.view', $post->user->id)}}" class="link link-primary">{{$post->user->name}}</a></p>
                            </li>
                          </ol>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

</x-app-layout>