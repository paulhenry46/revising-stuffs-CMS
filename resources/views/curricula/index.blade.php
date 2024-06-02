<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li>
      {{__('Curricula')}}
    </li>
  </ul>
</div>
            <div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    {{__('Manage the curricula')}}
</h1>

</div>

<div class="bg-white dark:bg-base-100 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<div class="px-4 sm:px-6 lg:px-8">
<div class="sm:flex sm:items-center">
<div class="sm:flex-auto">
  <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Curricula')}}</h1>
  <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of the curricula')}}</p>
</div>
<div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
  <a href="{{route('curricula.create')}}" class="btn btn-primary">{{__('New curriculum')}}</a>
</div>
</div>
<div class="mt-8 flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
    <table class="table table-zebra">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Schools')}}</th>
          <th>{{__('Levels')}}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($curricula as $curriculum)
        <tr>
            <td>{{ $curriculum->id }}</td>
            <td>{{$curriculum->name}}</td>
            <td>
              @foreach($curriculum->schools as $school)
              <div class="badge badge-outline">{{$school->name}}</div>
              @endforeach
            </td>
            <td>
              @foreach($curriculum->levels as $level)
              <div class="badge badge-outline">{{$level->name}}</div>
              @endforeach
            </td>
            <td class="flex items-stretch justify-end relative  text-right">
                <a href="{{route('curricula.edit', $curriculum->id)}}" class="link link-primary"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/>
                  </svg><span class="sr-only">, {{ $curriculum->name }}</span></a>
  
                <form action="{{route('curricula.destroy', $curriculum->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                <button type="submit" class="ml-4 link link-error"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg><span class="sr-only">, {{ $curriculum->name }}</span></button></form>
            </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
</div>
</div>
</div>
            </div>
        </div>
    </div>
</x-app-layout>
