<x-app-layout>
    <x-slot name="header">
    @if($course->id !== 0)
    @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Courses'),'courses.index'),
  array(__('Edit'),NULL)
        );
      @endphp
    @else
     @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Courses'),'courses.index'),
  array(__('Create'),NULL)
        );
      @endphp
    @endif
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($course->id !== 0) {{__('Edit a course')}} @else {{__('Add a new course')}} @endif
</h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($course->id !== 0) {{route('courses.update', $course->id)}} @else {{route('courses.store')}} @endif">
@csrf
@if($course->id !== 0) @method('put') @endif
<div class="space-y-12">

<div class="border-b border-gray-900/10 pb-12">

  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Name')}}</label>
      <div class="mt-2">
        <input type="text" name="name" id="name" autocomplete="given-name" class="input input-bordered max-w w-full" value="{{ old('name', $course->name) }}">
      </div>
    </div>

    <div class="sm:col-span-3">
      <fieldset>
<legend class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Choose a label color')}}</legend>
<div class="mt-4 flex items-center space-x-3">
<label class="@if(old('color', $course->color)=='gray-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-gray-500">
  <input type="radio" name="color" value="gray-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $course->color)=='gray-500') checked @endif >
  <span id="color-2-label" class="sr-only">Gray</span>
  <span aria-hidden="true" class="h-8 w-8 bg-gray-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $course->color)=='blue-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-blue-500">
  <input type="radio" name="color" value="blue-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $course->color)=='blue-500') checked @endif >
  <span id="color-2-label" class="sr-only">Blue</span>
  <span aria-hidden="true" class="h-8 w-8 bg-blue-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $course->color)=='purple-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-purple-500">
  <input type="radio" name="color" value="purple-500" class="sr-only" aria-labelledby="color-1-label" @if(old('color', $course->color)=='purple-500') checked @endif >
  <span id="color-1-label" class="sr-only">Purple</span>
  <span aria-hidden="true" class="h-8 w-8 bg-purple-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $course->color)=='pink-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-pink-500">
  <input type="radio" name="color" value="pink-500" class="sr-only" aria-labelledby="color-0-label" @if(old('color', $course->color)=='pink-500') checked @endif >
  <span id="color-0-label" class="sr-only">Pink</span>
  <span aria-hidden="true" class="h-8 w-8 bg-pink-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $course->color)=='red-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-red-500">
  <input type="radio" name="color" value="red-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $course->color)=='red-500') checked @endif >
  <span id="color-2-label" class="sr-only">Red</span>
  <span aria-hidden="true" class="h-8 w-8 bg-red-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $course->color)=='orange-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-orange-500">
  <input type="radio" name="color" value="orange-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $course->color)=='orange-500') checked @endif >
  <span id="color-2-label" class="sr-only">Orange</span>
  <span aria-hidden="true" class="h-8 w-8 bg-orange-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $course->color)=='yellow-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-yellow-500">
  <input type="radio" name="color" value="yellow-500" class="sr-only" aria-labelledby="color-4-label" @if(old('color', $course->color)=='yellow-500') checked @endif >
  <span id="color-4-label" class="sr-only">Yellow</span>
  <span aria-hidden="true" class="h-8 w-8 bg-yellow-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $course->color)=='green-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-green-500">
  <input type="radio" name="color" value="green-500" class="sr-only" aria-labelledby="color-3-label" @if(old('color', $course->color)=='green-500') checked @endif >
  <span id="color-3-label" class="sr-only">Green</span>
  <span aria-hidden="true" class="h-8 w-8 bg-green-500 rounded-full border border-black border-opacity-10"></span>
</label>
</div>
</fieldset>
    </div>
    <div class="sm:col-span-3">
    <label for="lang" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Language')}}</label>
      <div class="mt-2">
        <input type="text" name="lang" id="lang" class="input input-bordered max-w w-full" value="{{ old('lang', $course->lang) }}">
      </div>
      </div>
  </div>
</div>
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('courses.index')}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
<button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">@if($course->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
</div>
</form>
<script async>
let form = document.querySelector( "fieldset" );

form.addEventListener( "change", ( evt ) => {
let trg = evt.target,
  trg_par = trg.parentElement;

if ( trg.type === "radio" && trg_par &&
   trg_par.tagName.toLowerCase() === "label" ) {

let prior = form.querySelector( 'label.ring-2 input[name="' +
                                trg.name + '"]' );

if ( prior ) {
  prior.parentElement.classList.remove( "ring-2" );
}

trg_par.classList.add( "ring-2" );

}
}, false );
</script>
</div>

            </div>
        </div>
    </div>
</x-app-layout>
