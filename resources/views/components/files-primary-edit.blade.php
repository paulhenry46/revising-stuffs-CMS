<div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
        @if($state == 'update') {{__('Update the primary files')}} @else {{__('Add the primary files')}} @endif
    </h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($state == 'update') {{route('files.update', $post->id)}} @else {{route('files.primary.store', $post->id)}} @endif" enctype="multipart/form-data">
    @csrf
    @if($state == 'update') @method('put') @endif
  <div class="space-y-12">
    @if($state == 'update')
    <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Update Informations')}}</h2>
      <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('You are updating the primary file. Please, explain why to the users. This will create an History item.')}}</p>
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
        <label for="type" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">
        {{ __('Type of update') }}</label>
  <select id="type" name="update_type" class="form-select mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10">
                    <option  @if(old('update_type') == 'bugfix') selected @endif value="bugfix">Error fix</option>
                    <option  @if(old('update_type') == 'news') selected @endif value="news">New content</option>
                    
  </select>
        </div>
        <div class="sm:col-span-3">
          <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Content of the update')}}</label>
          <div class="mt-2">
            <textarea id="update_content" name="update_content" rows="3" class="form-textarea block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">{{ old('update_content') }}</textarea>
          </div>
        </div>
      </div>
    </div>
    @endif
    <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Upload your file')}}</h2>
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
      <div class="col-span-full">
          <label for="file" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Your light primary file')}}</label>
          <div class="mt-2">
            <input type="file" name="file_light" class="file-input file-input-sm file-input-bordered w-full max-w-xs" />
          </div>
        </div>
      </div>
    </div>
    @if($post->dark_version)
    <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Upload your file')}}</h2>
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
      <div class="col-span-full">
          <label for="file" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Your dark primary file')}}</label>
          <div class="mt-2">
            <input type="file" name="file_dark" class="file-input file-input-sm file-input-bordered w-full max-w-xs" />
          </div>
        </div>
      </div>
    </div>
    @endif
      </div>
      <div class="mt-6 flex items-center justify-end gap-x-6">
    <a href="{{route('files.index', $post->id)}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
    <button type="submit" class="btn btn-primary">@if($state == 'update') {{__('Update')}} @else {{__('Create')}} @endif</button>
  </div>
  </form>
  @if($state != 'update' and $post->dark_version == 0)
  
  
  <form method="POST" action="@if($state == 'update') {{route('files.update', $post->id)}} @else {{route('files.primary.handle', $post->id)}} @endif" enctype="multipart/form-data">
    @csrf
    @if($state == 'update') @method('put') @endif
  <div class="space-y-12">
    @if($state == 'update')
    <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Update Informations')}}</h2>
      <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('You are updating the primary file. Please, explain why to the users. This will create an History item.')}}</p>
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
        <label for="type" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">
        {{ __('Type of update') }}</label>
  <select id="type" name="update_type" class="form-select mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10">
                    <option  @if(old('update_type') == 'bugfix') selected @endif value="bugfix">Error fix</option>
                    <option  @if(old('update_type') == 'news') selected @endif value="news">New content</option>
                    
  </select>
        </div>
        <div class="sm:col-span-3">
          <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Content of the update')}}</label>
          <div class="mt-2">
            <textarea id="update_content" name="update_content" rows="3" class="form-textarea block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">{{ old('update_content') }}</textarea>
          </div>
        </div>
      </div>
    </div>
    @endif
    <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Using photos of handwritten resources')}}</h2>
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
      <div class="col-span-full">
          <label for="file" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Your Images')}}</label>
          <div class="mt-2">
            <input multiple="multiple" type="file" name="files[]" class="file-input file-input-sm file-input-bordered w-full max-w-xs" />
          </div>
        </div>
      </div>
    </div>
      </div>
      <div class="mt-6 flex items-center justify-end gap-x-6">
    <a href="{{route('files.index', $post->id)}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
    <button type="submit" class="btn btn-primary">@if($state == 'update') {{__('Update')}} @else {{__('Create')}} @endif</button>
  </div>
  </div>
  
</form>
@endif

  </div>
  

</div>
