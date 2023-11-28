<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class=" decoration-4 underline decoration-warning text-2xl font-medium text-gray-900 dark:text-white">
        {{$level->name}} - {{$course->name}}
    </h1>
    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-2">
          <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Search')}}</label>
          <div class="mt-2">
            <input wire:model.live.debounce.400ms="search" type="text" name="title" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full">
          </div>

          <div>
            </div>

        </div>
        
        <div class="sm:col-span-2">
            <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Particularities')}}</label>
            <div class="form-control w-52">
    <label class="cursor-pointer label">
      <span class="label-text">{{__('Only with Dark version')}}</span> 
      <input wire:model.live="dark" type="checkbox" class="toggle toggle-primary" />
    </label>
  </div>
  <div class="form-control w-52">
    <label class="cursor-pointer label">
      <span class="label-text">{{__('Only with Quizlet')}}</span> 
      <input wire:model.live="quizlet" type="checkbox" class="toggle toggle-primary"  />
    </label>
  </div>
  <div class="form-control w-52">
    <label class="cursor-pointer label">
      <span class="label-text">{{__('Only with Cards')}}</span> 
      <input wire:model.live="cards" type="checkbox" class="toggle toggle-primary" />
    </label>
  </div>
            
        </div>
        <div class="sm:col-span-2">
         <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Type')}}</label>
        <div class="form-control">
  <label class="cursor-pointer label">
    <span class="label-text">{{__('Mindmap')}}</span>
    <input value="mindmap" wire:model.live="types" type="checkbox" class="checkbox checkbox-success" />
  </label>
</div>
<div class="form-control">
  <label class="cursor-pointer label">
    <span class="label-text">{{__('Revision sheet')}}</span>
    <input value="revision" wire:model.live="types" type="checkbox" class="checkbox checkbox-info"/>
  </label>
</div>
<div class="form-control">
  <label class="cursor-pointer label">
    <span class="label-text">{{__('Methodology worksheet')}}</span>
    <input value="metodo" wire:model.live="types" type="checkbox" class="checkbox checkbox-warning "/>
  </label>
</div></div>
        
        
    </div>

</div>


</div>

<div class="grid grid-cols-4 gap-4">
@foreach($posts as $post)
<div wire:key="{{ $post->id }}" class="pt-4 col-span-4 lg:col-span-1">
<x-post-card :post=$post/>
                                </div>
                                @endforeach

                              </div>
</div>
