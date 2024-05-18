<x-app-layout>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($type->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a href="{{route('levels.index')}}">
      {{__('Types')}}
    </a></li>
    <li>
      {{__('New type')}}
    </li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a href="{{route('levels.index')}}">
      {{__('Types')}}
    </a></li>
    <li>
      {{__('Edit type : ')}}{{$type->name}}
    </li>
  </ul>
</div>
@endif
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($type->id !== 0) {{__('Edit a Type')}} @else {{__('Add a new Type')}} @endif
</h1>

</div>

<div class="bg-base-200 dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($type->id !== 0) {{route('types.update', $type->id)}} @else {{route('types.store')}} @endif">
@csrf
@if($type->id !== 0) @method('put') @endif
<div class="space-y-12">

<div class="border-b border-gray-900/10 pb-12">
<div class="bg-blue-100 bg-green-100 bg-purple-100 bg-yellow-100 bg-orange-100 bg-gray-100 bg-pink-100 bg-red-100 bg-blue-700 bg-green-700 bg-purple-700 bg-yellow-700 bg-orange-700 bg-gray-700 bg-pink-700 bg-red-700" style="display:none;"></div>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Name')}}</label>
      <div class="mt-2">
        <input type="text" name="name" id="name" autocomplete="given-name" class="input input-bordered max-w w-full" value="{{ old('name', $type->name) }}">
      </div>
    </div>
    <div class="sm:col-span-3">
        <div>
            <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Course')}}</label>
            <div class="mt-2">
                <select id="location" name="course_id" class="select select-bordered w-full">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="sm:col-span-3">
<div class="relative flex items-start">
  <div class="flex h-6 items-center">
    <input {{ (old('forall', $type->course_id) == 1) ? 'checked' : '' }} id="forall" aria-describedby="forall-description" name="forall" type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
  </div>
  <div class="ml-3 text-sm leading-6">
    <label for="forall" class="font-medium text-gray-900 dark:text-white">{{__('Available for all posts')}}</label>
    <p id="forall-description" class="text-gray-500 dark:text-gray-400">{{__('Check if this type is avaialble for all courses.')}}</p>
  </div>
</div>
</div>
    <div class="sm:col-span-3">
      <fieldset>
<legend class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Choose a label color')}}</legend>
<div class="mt-4 flex items-center space-x-3">
<label class="@if(old('color', $type->color)=='gray-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-gray-500">
  <input type="radio" name="color" value="gray-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $type->color)=='gray-500') checked @endif >
  <span id="color-2-label" class="sr-only">Gray</span>
  <span aria-hidden="true" class="h-8 w-8 bg-gray-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $type->color)=='blue-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-blue-500">
  <input type="radio" name="color" value="blue-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $type->color)=='blue-500') checked @endif >
  <span id="color-2-label" class="sr-only">Blue</span>
  <span aria-hidden="true" class="h-8 w-8 bg-blue-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $type->color)=='purple-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-purple-500">
  <input type="radio" name="color" value="purple-500" class="sr-only" aria-labelledby="color-1-label" @if(old('color', $type->color)=='purple-500') checked @endif >
  <span id="color-1-label" class="sr-only">Purple</span>
  <span aria-hidden="true" class="h-8 w-8 bg-purple-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $type->color)=='pink-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-pink-500">
  <input type="radio" name="color" value="pink-500" class="sr-only" aria-labelledby="color-0-label" @if(old('color', $type->color)=='pink-500') checked @endif >
  <span id="color-0-label" class="sr-only">Pink</span>
  <span aria-hidden="true" class="h-8 w-8 bg-pink-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $type->color)=='red-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-red-500">
  <input type="radio" name="color" value="red-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $type->color)=='red-500') checked @endif >
  <span id="color-2-label" class="sr-only">Red</span>
  <span aria-hidden="true" class="h-8 w-8 bg-red-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $type->color)=='orange-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-orange-500">
  <input type="radio" name="color" value="orange-500" class="sr-only" aria-labelledby="color-2-label" @if(old('color', $type->color)=='orange-500') checked @endif >
  <span id="color-2-label" class="sr-only">Orange</span>
  <span aria-hidden="true" class="h-8 w-8 bg-orange-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $type->color)=='yellow-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-yellow-500">
  <input type="radio" name="color" value="yellow-500" class="sr-only" aria-labelledby="color-4-label" @if(old('color', $type->color)=='yellow-500') checked @endif >
  <span id="color-4-label" class="sr-only">Yellow</span>
  <span aria-hidden="true" class="h-8 w-8 bg-yellow-500 rounded-full border border-black border-opacity-10"></span>
</label>
<label class="@if(old('color', $type->color)=='green-500') ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-green-500">
  <input type="radio" name="color" value="green-500" class="sr-only" aria-labelledby="color-3-label" @if(old('color', $type->color)=='green-500') checked @endif >
  <span id="color-3-label" class="sr-only">Green</span>
  <span aria-hidden="true" class="h-8 w-8 bg-green-500 rounded-full border border-black border-opacity-10"></span>
</label>
</div>
</fieldset>
    </div>
  </div>
</div>
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('types.index')}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
<button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">@if($type->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
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
