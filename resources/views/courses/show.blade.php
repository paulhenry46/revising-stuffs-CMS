<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Courses'),'courses.index')
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/> 
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    {{__('Manage your courses')}}
</h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<div class="px-4 sm:px-6 lg:px-8">
<div class="sm:flex sm:items-center">
<div class="sm:flex-auto">
  <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Courses')}}</h1>
  <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of all courses registered')}}</p>
</div>
<div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
  <a href="{{route('courses.create')}}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{__('New course')}}</a>
</div>
</div>
<div class="mt-8 flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
    <table class="min-w-full divide-y divide-gray-300">
      <thead>
        <tr>
          <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500 sm:pl-0">ID</th>
          <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Name')}}</th>
          <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Color')}}</th>
          <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Number of posts')}}</th>
          <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-0">
            <span class="sr-only">Edit</span>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 dark:bg-gray-800 bg-white">
        @foreach($courses as $course)
        <tr>
            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium dark:text-gray-100 text-gray-900 sm:pl-0">{{ $course->id }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">{{ $course->name }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">
                <label class="relative flex rounded-full focus:outline-none ring-{{ $course->color }}">
                    <span class="h-8 w-8 bg-{{ $course->color }} rounded-full border border-black border-opacity-10">
                    </span>
                </label>
            </td>
            <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">{{ $course->posts()->count() }}</td>
            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                <a href="{{route('courses.edit', $course->id)}}" class="text-indigo-600 hover:text-indigo-400">{{__('Edit')}}<span class="sr-only">, {{ $course->name }}</span></a>
                <form action="{{route('courses.destroy', $course->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-300">{{__('Delete')}}<span class="sr-only">, {{ $course->name }}</span></button></form>
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
