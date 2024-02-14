<x-app-layout>
    <x-slot name="header">
    
     @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__($post->title),'posts.index'),
  array(__('Files'),NULL),
  array(__('Create'),NULL)
        );
      @endphp
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    {{__('Sort the file')}}
</h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="{{route('files.primary.handle', $post->id)}}" enctype="multipart/form-data">
@csrf
<input type="hidden" id="file_type" name="file_type" value="image" />
    <input type="hidden" id="step" name="step" value="2" />
    
<div class="space-y-12">

<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Choose the order of the images')}}</h2>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
  <div class="col-span-full">
      <div class="mt-2">
@foreach($imagesDatas as $name => $id)
      <div class="mt-5 card card-side bg-base-100 shadow-xl">
  <figure><img class="object-cover h-full w-48" src="{{url('storage/temp/img/'.$name.'')}}" alt="Image"/></figure>
  <div class="card-body">
    
    @for ($i = 1; $i < count($imagesDatas)+1; $i++)
    <div class="form-control">
  <label class="label cursor-pointer">
    <span class="label-text">{{$i}}</span> 
    <input value="{{$i}}" type="radio" name="{{$name}}" class="radio checked:bg-red-500" />
  </label>
</div>
@endfor
  </div>
</div>
     @endforeach 

      </div>
    </div>
  </div>
</div>
  </div>
  <div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('files.index', $post->id)}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">{{__('Create')}}</button>
</div>
</div>
</form>
</div>

            </div>
        </div>
    </div>
</x-app-layout>