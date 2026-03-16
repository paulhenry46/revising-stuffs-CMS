<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

@if(isset($subdomainCurriculum))
<div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg mb-6 border border-primary">
    <div class="p-6 lg:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{__('You are browsing') }} <span class="text-primary">{{ $subdomainCurriculum->name }}</span>
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{__('To explore all available curricula, visit the main site.')}}
            </p>
        </div>
        <a href="{{ config('app.url') }}/library" class="btn btn-outline btn-primary shrink-0">
            {{__('Go to main site')}}
        </a>
    </div>
</div>
@endif

@foreach($curricula as $curriculum)
<div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg mt-6">
            <div class="p-6 lg:p-8 flex justify-between">
                <div>
                    <h1 class=" decoration-4 underline decoration-warning text-2xl font-medium text-gray-900 dark:text-white">
                        {{$curriculum->name}}
                    </h1>

                    <p>{{__('Taught at')}} @foreach($curriculum->schools as $school) {{$school->name}} @endforeach</p>
                </div>
                @if($curriculum->subdomain && !isset($subdomainCurriculum))
    <a href="https://{{$curriculum->subdomain}}.{{parse_url(config('app.url'), PHP_URL_HOST)}}" target="_blank" class="btn  btn-primary mt-2">
        {{ __('Go to curriculum site') }}
    </a>
@endif
            </div>
</div>
        <div class="">

            @foreach($curriculum->levels as $level)
            <div class="collapse bg-base-100 collapse-arrow mt-2 rounded-lg">
  <input type="radio" name="my-accordion-1" @if ($loop->first) checked="checked" @endif/> 
  <div class="collapse-title text-xl font-medium">
    {{$level->name}}
  </div>
  <div class="collapse-content"> 
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 py-2">
  @foreach($level->courses as $course)
            <div wire:navigate href="{{route('post.public.courseView', ['curriculum_chosen' => $curriculum->slug, 'level_chosen' => $level->slug, 'course_chosen' => $course->slug])}}" class="cursor-pointer col-span-1 flex rounded-md shadow-xs border-{{$course->color}} border-2">
            <div class="flex w-16 shrink-0 items-center justify-center bg-{{str_replace('500', '500',$course->color)}} rounded-l-smd text-sm font-medium text-white">{{strtoupper(mb_substr($course->name, 0, 1))}}</div>
            <div class="flex flex-1 items-center justify-between truncate rounded-r-md  dark:bg-base-200 bg-white">
                <div class="flex-1 truncate px-4 py-2 text-sm">
                    <a class="font-medium text-gray-900 dark:text-white">{{$course->name}}</a>
                    <p class="text-gray-500">{{$course->postsOfLevel($level)->count()}}{{__(' Resources')}}</p>
                </div>
            </div>
        </div>
            @endforeach
</div>
  </div>
</div>
            
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>

</x-app-layout>