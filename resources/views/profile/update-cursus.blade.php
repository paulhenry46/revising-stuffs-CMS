<x-app-layout>
    <x-slot name="header">
   
     @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Profil'),'profile.show'),
  array(__('Update cursus information'),NULL)
        );
      @endphp
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    {{__('Edit Cursus informations')}}
</h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="{{route('user.update-cursus')}}">
@csrf
<div class="space-y-12">

<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  @livewire('user-cursus', ['user'=> $user])
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('posts.index')}}" class="btn btn-error">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">{{__('Update')}}</button>
</div>
</form>
</div>
            </div>
        </div>
    </div>
</x-app-layout>
