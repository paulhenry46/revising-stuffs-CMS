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
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($courses as $course)
                    <div class="col-span-1 col-span-1 h-full ">
                        <div class="card w-full bg-{{$course->color}} text-white h-full">
                            <div class="card-body">
                              <h2 class="card-title">{{$course->name}}</h2>
                              <div class="card-actions justify-end">
                                <a wire:navigate href="{{route('post.public.news')}}" class="btn">{{__('See')}}</a>
                              </div>
                            </div>
                          </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
<x-nav-bottom :active=1/>
</x-app-layout>