<x-app-layout>
     <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($card->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Post : ')}}{{$post->title}}
    </a></li>
    <li><a wire:navigate href="{{route('cards.index', $post->id)}}">
      {{__('Cards')}}
    </a></li>
    <li>
      {{__('New card')}}
    </li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Post : ')}}{{$post->title}}
    </a></li>
    <li><a href="{{route('cards.index', $post->id)}}">
      {{__('Cards')}}
    </a></li>
    <li>
      {{__('Card n°')}} {{$card->id}}</li>
  </ul>
</div>
@endif
           
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
                    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
                        @if($card->id !== 0) {{__('Edit a card')}} @else {{__('Add a new card')}} @endif
                    </h1>
                </div>
                <div class="bg-base-200 dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">

                    <x-info-message/>
                <form method="POST" action="@if($card->id !== 0) {{route('cards.update', [$post->id, $card->id])}} @else {{route('cards.store', $post->id)}} @endif">
                @csrf
                @if($card->id !== 0) @method('put') @endif
                  <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                          <label for="front" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Front')}}</label>
                          <div class="mt-2">
                            <textarea id="front" name="front" rows="3" class="textarea textarea-bordered textarea-primary w-full">{{ old('front', $card->front) }}</textarea>
                          </div>
                        </div>
                        <div class="sm:col-span-3">
                          <label for="back" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Back')}}</label>
                          <div class="mt-2">
                            <textarea id="back" name="back" rows="3" class="textarea textarea-primary textarea-bordered w-full">{{ old('back', $card->back) }}</textarea>
                          </div>
                        </div>
                        <div class="sm:col-span-6">
                        <div class="mt-2">
                        <h2 class="text-xl">{{__('How to underline text ?')}}</h2>
                           {{__('You can use tags to underline part of your cards. To choose the color, put the text between the tags, for exemple :')}} [YEL]<span class="underline decoration-2 decoration-warning object-scale-down">{{__('Text in yellow')}}</span>[/] ; 
                           [RED]<span class="underline decoration-2 decoration-error  object-scale-down">{{__('Text in red')}}</span>[/] ; [GRE]<span class="underline decoration-2 decoration-success object-scale-down">{{__('Text in green')}}</span>[/] ; [PUR]<span class="underline decoration-primary decoration-2 object-scale-down">{{__('Text in purple')}}</span>[/]
                          </div>
                          <div class="sm:col-span-6">
                        <div class="mt-2">
                          <h2 class="text-xl">{{__('How to add image ?')}}</h2>
                           {{__('To add image, first upload it as complemnetary document (select card image for the type of document) and note the image ID, such as "id.name.extension". Then, just put it between [IMG] and [/IMG]. Example : [IMG]18.cool_image.svg[/IMG].')}}
                          </div>
                        </div>
                        <div class="sm:col-span-6">
                        <div class="mt-2">
                          <h2 class="text-xl">{{__('How to use maths formula ?')}}</h2>
                           {{__('You can use maths written in LaTeX between \( and \). Example : \( a^2+\sqrt{2} = \frac{1}{2} \)')}}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a wire:navigate href="{{route('cards.index', $post->id)}}" class="link">{{__('Cancel')}}</a>
                    <button type="submit" class="btn btn-primary">@if($card->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
                 </div>
                </form>
                </div>

                            </div>
                        </div>
                    </div>
</x-app-layout>
