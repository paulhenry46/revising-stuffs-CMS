<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('Your favorite posts'),NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
    <h1 class=" decoration-4 underline decoration-success text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Your favorites posts')}}
    </h1>
    <p>{{__('See all your favorite posts')}}</p>
    @guest
    <div class="mt-2">
    <div class="alert alert-warning">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
      <div>
        <h3 class="font-bold">{{ __('You should consider this.') }}</h3>
        <ul class="mt-3 list-disc list-inside text-sm">
          <li>{{__('You must be connected to view and manage your favorite posts')}}</li>
        </ul>
      </div> 
    </div>
    </div>
    @endguest
</div>
</div>
<div class="grid grid-cols-4 gap-4">
    @auth
@foreach($posts as $post)
<div class="pt-4 col-span-4 lg:col-span-1">
<x-post-card :post=$post :starred=false/>
                                </div>
                                @endforeach
                                @endauth
                              </div>
            </div>
            </div>
        </div>
    </div>
<x-nav-bottom :active=2/>
</x-app-layout>