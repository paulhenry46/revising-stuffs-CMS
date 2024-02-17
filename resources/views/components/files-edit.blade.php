<div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
         {{__('Add a new file')}}
    </h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>

<form method="POST" action="@if($file->id !== 0) {{route('files.update', ['post' => $post->id, 'file' => $file->id])}} @else {{route('files.store', $post->id)}} @endif" enctype="multipart/form-data">
    @csrf
  <div class="space-y-12">
    <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('General Informations')}}</h2>
      <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('This information is used to classify and recognise your file.')}}</p>
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
        <label for="type" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Type')}}</label>
        <select id="type" name="type" class="form-select mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10">
                    <option  @if(old('type') == 'source') selected @endif value="source">{{__('Source File')}}</option>
                    <option  @if(old('type') == 'exercise') selected @endif value="exercise">{{__('Exercise')}}</option>
                    <option  @if(old('type') == 'exercise correction') selected @endif value="exercise correction">{{__('Exercise correction')}}</option>
  </select>
        </div>
        <div class="sm:col-span-3">
          <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Title')}}</label>
          <div class="mt-2">
            <input value="{{ old('name', $file->name) }}" type="text" name="name" id="name" autocomplete="given-name" class=" form-input dark:bg-white/5 dark:text-white block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-900 disabled:text-gray-500 disabled:ring-gray-900" value="{{ old('name', $file->name) }}">
          </div>
        </div>
      </div>
    </div>
    <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Upload your file')}}</h2>
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
      <div class="col-span-full">
          <label for="file" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Your file')}}</label>
          <div class="mt-2">
            <input type="file" name="file" class="file-input file-input-sm file-input-bordered w-full max-w-xs" />
          </div>
        </div>
      </div>
    </div>
      </div>
      <div class="mt-6 flex items-center justify-end gap-x-6">
    <a href="{{route('files.index', $post->id)}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{__('Create')}}</button>
  </div>
  </div>
  
  
</form>
</div>
