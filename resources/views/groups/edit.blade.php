<x-app-layout>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($group->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('groups.index')}}">
      {{__('Groups')}}
    </a></li>
    <li>
      {{__('New group')}}
    </li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('groups.index')}}">
      {{__('Groups')}}
    </a></li>
    <li>
      {{__('Edit group : ')}}{{$group->name}}
    </li>
  </ul>
</div>
@endif
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($group->id !== 0) {{__('Edit a group')}} @else {{__('Add a new group')}} @endif
</h1>

</div>

<div class="bg-base-200 dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>

<livewire:group-edit :$group :$schools/>

  

</div>

            </div>
        </div>
    </div>
</x-app-layout>
