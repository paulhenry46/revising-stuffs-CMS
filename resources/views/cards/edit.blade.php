<x-app-layout>
    <x-slot name="header">
    @if($card->id !== 0)
    @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__('Cards'),NULL),
  array(__('Edit'),NULL)
        );
      @endphp
    @else
     @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__('Cards'),NULL),
  array(__('Create'),NULL)
        );
      @endphp
    @endif
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
                        @if($card->id !== 0) {{__('Edit a card')}} @else {{__('Add a new card')}} @endif
                    </h1>
                </div>

                <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
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
                            <textarea id="front" name="front" rows="3" class="textarea textarea-bordered w-full">{{ old('front', $card->front) }}</textarea>
                          </div>
                        </div>
                        <div class="sm:col-span-3">
                          <label for="back" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Back')}}</label>
                          <div class="mt-2">
                            <textarea id="back" name="back" rows="3" class="textarea textarea-bordered w-full">{{ old('back', $card->back) }}</textarea>
                          </div>
                        </div>
                        <div class="sm:col-span-6">
                        <div class="mt-2">
                           {{__('You can use HTML tags to add images, MathML commands, use Tailwind CSS. However, there is forbiden tags wich are escaped :')}} &lt;div&gt;, &lt;script&gt;, &lt;link&gt;, &lt;th&gt;, &lt;td&gt;, &lt;a&gt;, &lt;iframe&gt;</br>
                           {{('For example, to underline a part of text,')}} <span class="underline decoration-warning">{{__('like')}}</span> <span class="underline decoration-error">{{__('this')}}</span>, use &lt;span class="underline decoration-warning"&gt;{{__('like')}}&lt;/span&gt; &lt;span class="underline decoration-error"&gt;{{__('this')}}&lt;/span&gt;
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a wire:navigate href="{{route('cards.index', $post->id)}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">@if($card->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
                 </div>
                </form>
                </div>

                            </div>
                        </div>
                    </div>
</x-app-layout>
