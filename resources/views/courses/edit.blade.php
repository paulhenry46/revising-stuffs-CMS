<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($course->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('courses.index')}}">
      {{__('Courses')}}
    </a></li>
    <li>
      {{__('New course')}}
    </li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('courses.index')}}">
      {{__('Posts')}}
    </a></li>
    <li>
      {{__('Edit course : ')}}{{$course->name}}
    </li>
  </ul>
</div>
@endif
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($course->id !== 0) {{__('Edit a course')}} @else {{__('Add a new course')}} @endif
</h1>

</div>

<div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($course->id !== 0) {{route('courses.update', $course->id)}} @else {{route('courses.store')}} @endif">
@csrf
@if($course->id !== 0) @method('put') @endif
<div class="space-y-12">

<div class="border-b border-gray-900/10 pb-12">
<div class="border-blue-500 border-green-500 border-purple-500 border-yellow-500 border-orange-500 border-gray-500 border-pink-500 border-red-500
 checked:border-blue-500 checked:border-green-500 checked:border-purple-500 checked:border-yellow-500 checked:border-orange-500 checked:border-gray-500 checked:border-pink-500 checked:border-red-500 
 [--chkbg:var(--color-blue-500)] [--chkbg:var(--color-green-500)] [--chkbg:var(--color-purple-500)] [--chkbg:var(--color-yellow-500)] [--chkbg:var(--color-orange-500)] [--chkbg:var(--color-gray-500)] [--chkbg:var(--color-pink-500)] [--chkbg:var(--color-red-500)]
 bg-blue-100 bg-green-100 bg-purple-100 bg-yellow-100 bg-orange-100 bg-gray-100 bg-pink-100 bg-red-100 
 bg-blue-700 bg-green-700 bg-purple-700 bg-yellow-700 bg-orange-700 bg-gray-700 bg-pink-700 bg-red-700" style="display:none;"></div>
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
<div class="mt-4 flex items-center space-x-3 fieldset">
<label class="@if(old('color', $course->color)=='gray-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-gray-500">
  <input type="radio" name="color" value="gray-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $course->color)=='gray-500') checked @endif >
  <span id="color-2-label" class="sr-only">Gray</span>
  <span aria-hidden="true" class="h-8 w-8 bg-gray-500 rounded-full "></span>
</label>
<label class="@if(old('color', $course->color)=='blue-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-blue-500">
  <input type="radio" name="color" value="blue-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $course->color)=='blue-500') checked @endif >
  <span id="color-2-label" class="sr-only">Blue</span>
  <span aria-hidden="true" class="h-8 w-8 bg-blue-500 rounded-full "></span>
</label>
<label class="@if(old('color', $course->color)=='purple-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-purple-500">
  <input type="radio" name="color" value="purple-500" class="sr-only" aria-labelledby="color-1-label" @if(old('color', $course->color)=='purple-500') checked @endif >
  <span id="color-1-label" class="sr-only">Purple</span>
  <span aria-hidden="true" class="h-8 w-8 bg-purple-500 rounded-full "></span>
</label>
<label class="@if(old('color', $course->color)=='pink-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-pink-500">
  <input type="radio" name="color" value="pink-500" class="sr-only" aria-labelledby="color-0-label" @if(old('color', $course->color)=='pink-500') checked @endif >
  <span id="color-0-label" class="sr-only">Pink</span>
  <span aria-hidden="true" class="h-8 w-8 bg-pink-500 rounded-full "></span>
</label>
<label class="@if(old('color', $course->color)=='red-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-red-500">
  <input type="radio" name="color" value="red-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $course->color)=='red-500') checked @endif >
  <span id="color-2-label" class="sr-only">Red</span>
  <span aria-hidden="true" class="h-8 w-8 bg-red-500 rounded-full "></span>
</label>
<label class="@if(old('color', $course->color)=='orange-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-orange-500">
  <input type="radio" name="color" value="orange-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $course->color)=='orange-500') checked @endif >
  <span id="color-2-label" class="sr-only">Orange</span>
  <span aria-hidden="true" class="h-8 w-8 bg-orange-500 rounded-full "></span>
</label>
<label class="@if(old('color', $course->color)=='yellow-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-yellow-500">
  <input type="radio" name="color" value="yellow-500" class="sr-only" aria-labelledby="color-4-label" @if(old('color', $course->color)=='yellow-500') checked @endif >
  <span id="color-4-label" class="sr-only">Yellow</span>
  <span aria-hidden="true" class="h-8 w-8 bg-yellow-500 rounded-full "></span>
</label>
<label class="@if(old('color', $course->color)=='green-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-green-500">
  <input type="radio" name="color" value="green-500" class="sr-only" aria-labelledby="color-3-label" @if(old('color', $course->color)=='green-500') checked @endif >
  <span id="color-3-label" class="sr-only">Green</span>
  <span aria-hidden="true" class="h-8 w-8 bg-green-500 rounded-full "></span>
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
<a href="{{route('courses.index')}}" class="link">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">@if($course->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
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
