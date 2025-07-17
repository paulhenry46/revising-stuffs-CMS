<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('profile.show')}}">
      {{__('Profile')}}
    </a></li>
    <li>
      {{__('Update cursus information')}}
    </li>
  </ul>
</div>
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    {{__('Edit Cursus informations')}}
</h1>

</div>

<div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="{{route('user.update-cursus')}}">
@csrf
<div class="space-y-12">

<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  @livewire('user-cursus', ['user'=> $user])
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('posts.index')}}" class="link link-error">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">{{__('Update')}}</button>
</div>
</form>
</div>
            </div>
        </div>
    </div>
</x-app-layout>
