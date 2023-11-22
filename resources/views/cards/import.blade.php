<x-app-layout>
    <x-slot name="header">
    @php
          $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__('Cards'),NULL),
  array(__('Import'),NULL));
      @endphp
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
                        {{__('Import cards')}}
                    </h1>
                </div>

                <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message/>
                <form method="POST" action="{{route('cards.store.import', $post->id)}}">
                @csrf
                  <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                          <label for="front" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Your cards')}}</label>
                          <div class="mt-2">
                            <textarea id="front" name="content" rows="3" class="textarea textarea-bordered w-full" 
placeholder="Mot 1 Définition 1
Mot 2   Définition 2
Mot 3   Définition 3">{{ old('content')}}</textarea>
                          </div>
                        </div>
                        <div class="sm:col-span-3">
    <div>
  <label for="separator_cards" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Separator between cards')}}</label>
  <div class="mt-2">
  <select id="separator_cards" name="separator_cards" class="select select-bordered w-full">
                    <option  @if(old('separator_cards') == 'newline') selected @endif value="tab">{{__('New line')}}</option>
  </select>
  </div>
</div>
        </div>
        <div class="sm:col-span-3">
    <div>
  <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Separator between front and back of a card')}}</label>
  <div class="mt-2">
  <select id="separator_cards" name="separator_cards" class="select select-bordered w-full">
                    <option  @if(old('separator_cards') == 'tab') selected @endif value="tab">{{__('Tabulation')}}</option>
                    <option  @if(old('separator_cards') == 'egual') selected @endif value="egual">{{__('Egual (=)')}}</option>
                    <option  @if(old('separator_cards') == 'semicolon') selected @endif value="semicolon">{{__('SemiColon')}}</option>
  </select>
  </div>
</div>
        </div>
        <div class="sm:col-span-6">
                          <div class="mt-2">
                           {{__('You can use HTML tags to add images, MathML commands, use Tailwind CSS. However, there is forbiden tags wich are escaped : <div>, <script>, <link>, <th>, <td>, <a>, <iframe>')}}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a wire:navigate href="{{route('cards.index', $post->id)}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
                    <button type="submit" class="btn btn-primary">{{__('Import')}}</button>
                 </div>
                </form>
                </div>

                            </div>
                        </div>
                    </div>
</x-app-layout>
