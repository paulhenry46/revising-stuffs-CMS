<x-app-layout>
  <div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="rounded-lg navbar bg-base-100">
          <div class="navbar-start"> @auth
            <livewire:favorite-button wire:key="fav_{{$post->id}}" :post=$post :user=Auth::user() lazy mobile='cards' />
            <livewire:like wire:key="like_{{$post->id}}" :post=$post lazy mobile=cards /> @endauth
          </div>
          <div class="navbar-center">
            <a wire:navigate href="{{route('post.public.view', [$post->slug, $post->id])}}" class="btn btn-ghost text-xl">{{$post->title}}</a>
          </div>
          <div class="navbar-end">
            <button onclick="add_comment.showModal()" class="btn btn-square btn-ghost dark:bg-base-100">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M440-400h80v-120h120v-80H520v-120h-80v120H320v80h120v120ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
              </svg>
            </button>
            <dialog id="add_comment" class="modal">
              <div class="modal-box">
                <h3 class="font-bold text-lg">{{__('Add a new comment')}}</h3>
                <form action="{{route('post.public.comment.create', [$post->slug, $post->id])}}" method="POST"> @csrf <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                    <div class="">
                      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Pseudo')}}</label>
                      <div class="mt-2">
                        <input type="text" name="pseudo" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full" @auth disabled @endauth @auth value="{{Auth::user()->name}}" @endauth @guest value="{{ old('name') }}" @endguest> @auth <input type="hidden" name="pseudo" value="null" autocomplete="off"> @endauth
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
            </dialog>
            <button onclick="report_error.showModal()" class="btn btn-square btn-ghost dark:bg-base-100">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
              </svg>
            </button>
            <dialog id="report_error" class="modal">
              <div class="modal-box">
                <h3 class="font-bold text-lg">{{__('Report an error')}}</h3>
                <form action="{{route('post.public.comment.create', [$post->slug, $post->id])}}" method="POST"> @csrf <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                    <div class="">
                      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Pseudo')}}</label>
                      <div class="mt-2">
                        <input type="text" name="pseudo" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full" @auth disabled @endauth @auth value="{{Auth::user()->name}}" @endauth @guest value="{{ old('name') }}" @endguest> @auth <input type="hidden" name="pseudo" value="null" autocomplete="off"> @endauth
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
            </dialog>
          </div>
        </div>

        <livewire:step-history :$deck lazy />
        <div class="rounded-lg navbar bg-base-100 mt-8">
          <div class="flex-1">
            <a class="btn btn-ghost text-xl">{{__('Cards')}}</a>
          </div>
          <div class="flex-none"> @guest <button onclick="csv_guest.showModal()" class=" ml-4 btn btn-ghost ">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M480-337q-8 0-15-2.5t-13-8.5L308-492q-12-12-11.5-28t11.5-28q12-12 28.5-12.5T365-549l75 75v-286q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v286l75-75q12-12 28.5-11.5T652-548q11 12 11.5 28T652-492L508-348q-6 6-13 8.5t-15 2.5ZM240-160q-33 0-56.5-23.5T160-240v-80q0-17 11.5-28.5T200-360q17 0 28.5 11.5T240-320v80h480v-80q0-17 11.5-28.5T760-360q17 0 28.5 11.5T800-320v80q0 33-23.5 56.5T720-160H240Z" />
              </svg>
              <!-- {{__('CSV Export')}}-->
            </button> @endguest @auth <a href="{{route('post.public.cards.export', [$post->slug, $post->id])}}" class=" ml-4 btn btn-ghost ">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M480-337q-8 0-15-2.5t-13-8.5L308-492q-12-12-11.5-28t11.5-28q12-12 28.5-12.5T365-549l75 75v-286q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v286l75-75q12-12 28.5-11.5T652-548q11 12 11.5 28T652-492L508-348q-6 6-13 8.5t-15 2.5ZM240-160q-33 0-56.5-23.5T160-240v-80q0-17 11.5-28.5T200-360q17 0 28.5 11.5T240-320v80h480v-80q0-17 11.5-28.5T760-360q17 0 28.5 11.5T800-320v80q0 33-23.5 56.5T720-160H240Z" />
              </svg>
              <!--{{__('CSV Export')}}-->
            </a> @endauth <a href="{{route('post.public.cards.learn', [$post->slug, $post->id])}}" class="btn btn-ghost ml-4">{{__('Learn mode')}}</a>
            <a href="{{route('post.public.cards.quiz', [$post->slug, $post->id])}}" class=" btn btn-ghost ml-4">{{__('Quiz Mode')}}</a>
          </div> @guest <dialog id="csv_guest" class="modal">
            <div class="modal-box">
              <h3 class="font-bold text-lg">{{('Warning')}}</h3>
              <p class="py-4">{{('You must be logged to export cards to CSV.')}}</p>
              <div class="modal-action">
                <form method="dialog">
                  <!-- if there is a button in form, it will close the modal -->
                  <button class="btn">{{ __('Close') }}</button>
                </form>
              </div>
            </div>
          </dialog> @endguest
        </div>
        <div class="mt-4 flow-root">
          <div class=" -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
              <ul class="space-y-4"> 
                
                @if($user)
                  @if($user->id==$post->user_id) 
                    @foreach ($cards as $card) 
                    <x-flashcard-card-edit :card="$card" :post="$post" :user="$user"/> 
                    @endforeach 
                  @else 
                    @foreach ($cards as $card) 
                    <x-flashcard-card :card="$card" :post="$post" :user="$user"/> 
                    @endforeach 
                  @endif
                @else
                    @foreach ($cards as $card) 
                    <x-flashcard-card :card="$card" :post="$post" :user="$user"/> 
                    @endforeach 
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('livewire:navigated', () => {
      MathJax.typeset();
    })
  </script>
</x-app-layout>