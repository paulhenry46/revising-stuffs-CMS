<x-app-layout>
    <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li>
      {{__('Levels, courses and types')}}
   </li>
  </ul>
</div>
<div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">
                <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
                    {{__('Manage levels, courses and specific types of post')}}
                </h1>
            </div>
            <div class="bg-white dark:bg-base-100 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
                <x-info-message/>
                <div role="tablist" class="tabs tabs-bordered">
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Levels" checked />
                    <div role="tabpanel" class="tab-content p-10">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('levels')}}</h1>
                                    <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of all levels registered')}}</p>
                                </div>
                                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                    <a href="{{route('levels.create')}}" class="btn btn-primary">{{__('New level')}}</a>
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
                                                    <th>{{__('Curriculum')}}</th>
                                                    <th>{{__('Courses')}}</th>
                                                    <th>{{__('Number of posts')}}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($levels as $level)
                                                <tr>
                                                    <td>{{ $level->id }}</td>
                                                    <td>{{ $level->name }}</td>
                                                    @if(!empty($level->curriculum))
                                                    <td>{{ $level->curriculum->name }}</td>
                                                    @else
                                                    <td><span class="badge badge-error">{{__('No curriculum')}}</span></td>
                                                    @endif
                                                    <td>
                                                        @foreach ($level->courses()->get() as $course)
                                                        <span class="ml-1 text-white badge bg-{{$course->color}} badge-xm">{{$course->name}}</span>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $level->posts()->count() }}</td>
                                                    <td class="flex items-stretch justify-end relative  text-right">
                                                        <a href="{{route('levels.edit', $level->id)}}" class="link link-primary">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                                                            </svg>
                                                            <span class="sr-only">, {{ $level->name }}
                                                            </span>
                                                        </a>
                                                        <form action="{{route('levels.destroy', $level->id)}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="mr-4 ml-4 link link-error ">
                                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                                                </svg>
                                                                <span class="sr-only">, {{ $level->name }}</span>
                                                            </button>
                                                        </form>
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
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Courses" />
                    <div role="tabpanel" class="tab-content p-10">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Courses')}}</h1>
                                    <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of all courses registered')}}</p>
                                </div>
                                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                    <a href="{{route('courses.create')}}" class="btn btn-primary">{{__('New course')}}</a>
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
                                                    <th>{{__('Color')}}</th>
                                                    <th>{{__('Levels')}}</th>
                                                    <th>{{__('Number of posts')}}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($courses as $course)
                                                <tr>
                                                    <td>{{ $course->id }}</td>
                                                    <td>{{ $course->name }}</td>
                                                    <td>
                                                        <label class="relative flex rounded-full focus:outline-none ring-{{ $course->color }}">
                                                        <span class="h-8 w-8 bg-{{ $course->color }} rounded-full border border-black border-opacity-10">
                                                        </span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        @foreach ($course->levels()->get() as $level)
                                                        <span class="ml-1 badge badge-outline">{{$level->name}}</span>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $course->posts()->count() }}</td>
                                                    <td class="flex items-stretch justify-end relative  text-right">
                                                        <a href="{{route('courses.edit', $course->id)}}" class="link link-primary">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                                                            </svg>
                                                            <span class="sr-only">, {{ $course->name }}
                                                            </span>
                                                        </a>
                                                        <form action="{{route('courses.destroy', $course->id)}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="mr-4 ml-4 link link-error ">
                                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                                                </svg>
                                                                <span class="sr-only">, {{ $course->name }}</span>
                                                            </button>
                                                        </form>
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
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Types" />
                    <div role="tabpanel" class="tab-content p-10">
                    <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Types')}}</h1>
                                    <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of all types of posts registered')}}</p>
                                </div>
                                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                    <a href="{{route('types.create')}}" class="btn btn-primary">{{__('New Type')}}</a>
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
                                                    <th>{{__('Color')}}</th>
                                                    <th>{{__('Course')}}</th>
                                                    <th>{{__('Number of posts')}}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($types as $type)
                                                <tr>
                                                    <td>{{ $type->id }}</td>
                                                    <td>{{ $type->name }}</td>
                                                    <td>
                                                        <label class="relative flex rounded-full focus:outline-none ring-{{ $course->color }}">
                                                        <span class="h-8 w-8 bg-{{ $type->color }} rounded-full border border-black border-opacity-10">
                                                        </span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        @if($type->course_id == 1)
                                                        All courses
                                                        @else
                                                        <span class="ml-1 badge badge-outline">{{$type->course->name}}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $type->posts()->count() }}</td>
                                                    <td class="flex items-stretch justify-end relative  text-right">
                                                        <a href="{{route('types.edit', $type->id)}}" class="link link-primary">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                                                            </svg>
                                                            <span class="sr-only">, {{ $type->name }}
                                                            </span>
                                                        </a>
                                                        <form action="{{route('types.destroy', $type->id)}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="mr-4 ml-4 link link-error ">
                                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                                                </svg>
                                                                <span class="sr-only">, {{ $type->name }}</span>
                                                            </button>
                                                        </form>
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
    </div>
</div>

</x-app-layout>
