<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('Available courses'),NULL)
);
     @endphp   
     <x-breadcrumb :items=$breadcrumb/>    
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
<h1 class=" decoration-4 underline decoration-warning text-2xl font-medium text-gray-900 dark:text-white">
    {{__('All courses')}}
</h1>
<p>{{__('See all the course available')}}</p>
</div>
</div>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 py-2">

            @foreach($courses as $course)
            <div onclick="my_modal_{{$course->id}}.showModal()" class="cursor-pointer col-span-1 flex rounded-md shadow-sm">
            <div class="flex w-16 flex-shrink-0 items-center justify-center bg-{{str_replace('500', '500',$course->color)}} rounded-l-md text-sm font-medium text-white">{{strtoupper(mb_substr($course->name, 0, 1))}}</div>
            <div class="flex flex-1 items-center justify-between truncate rounded-r-md  dark:bg-gray-800 bg-white">
                <div class="flex-1 truncate px-4 py-2 text-sm">
                    <a class="font-medium text-gray-900 dark:text-white">{{$course->name}}</a>
                    <p class="text-gray-500">{{$course->posts->count()}}{{__(' Resources')}}</p>
                </div>
                <div class="flex-shrink-0 pr-3 flex items-stretch relative   font-medium sm:pr-0">
                </div>
            </div>
        </div>
        <dialog id="my_modal_{{$course->id}}" class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">{{$course->name}}</h3>
                        <p class="py-4">Please chose your level !</p>
                        @foreach($levels as $level)
                        <a wire:navigate href="{{route('post.public.courseView', ['level_chosen' => $level->slug, 'course_chosen' => $course->slug])}}" class="btn btn-primary">{{$level->name}}</a>
                        @endforeach
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
            </dialog>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
<x-nav-bottom :active=1/>
</x-app-layout>