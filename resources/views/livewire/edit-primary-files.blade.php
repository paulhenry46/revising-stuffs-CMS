<div class="bg-base-200 dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
@if($update)
<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white pb-3">{{__('Update Informations')}}</h2>
@php
    $update_type_options = [
        [
            'id' => 'none',
            'name' => __('Select an option'),
            'disabled' => true,
        ],
        [
            'id' => 'bugfix',
            'name' => __('Fix issue(s)')
        ],
        [
            'id' => 'news',
            'name' => __('Add new content')
        ]
    ];
@endphp
 
<x-mary-select label="{{__('Update Type')}}" :options="$update_type_options" wire:model="update_type" />
<div class="py-3">
<x-mary-textarea
    label="{{__('Update Content')}}"
    wire:model="update_content"
    placeholder="{{__('Explain why you are updating your post.')}}"
    hint=""
    rows="3"
    inline />
</div>
</div>
@endif
<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
      <h2 class="pt-6 pb-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">@if($update){{__('Update Primary Files')}}@else{{__('Create Primary Files')}}@endif</h2>
<div class=" grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
<div class="sm:col-span-3 ">
<x-mary-image-library
    wire:model="light_image_files"                 {{-- Temprary files --}}
    wire:library="light_image_library"             {{-- Library metadata property --}}
    :preview="$light_image_library"                {{-- Preview control --}}
    label="{{__('Images of light version')}}"
    hint="{{__('Images Only')}}" 
    change-text="{{__('Change')}}"
    crop-text="{{__('Crop')}}"
    remove-text="{{__('Remove')}}"
    crop-title-text="{{__('Crop image')}}"
    crop-cancel-text="{{__('Cancel')}}"
    crop-save-text="{{__('Crope')}}"
    add-files-text="{{__('Add images')}}"
    />
    <div class="divider">{{__('OR')}}</div>
    <x-mary-file wire:model="light_file" label="{{__('Light File')}}" hint="{{__('Only PDF')}}" accept="application/pdf"/>
</div>
@if($dark_version)
<div class="sm:col-span-3">
<x-mary-image-library
    wire:model="dark_image_files"                 {{-- Temprary files --}}
    wire:library="dark_image_library"             {{-- Library metadata property --}}
    :preview="$dark_image_library"                {{-- Preview control --}}
    label="{{__('Images of dark version')}}"
    hint="{{__('Images Only')}}" 
    change-text="{{__('Change')}}"
    crop-text="{{__('Crop')}}"
    remove-text="{{__('Remove')}}"
    crop-title-text="{{__('Crop image')}}"
    crop-cancel-text="{{__('Cancel')}}"
    crop-save-text="{{__('Crope')}}"
    add-files-text="{{__('Add images')}}" />
    <div class="divider">OR</div>
    <x-mary-file wire:model="dark_file" label="{{__('Dark File')}}" hint="{{__('Only PDF')}}" accept="application/pdf" />
</div>
@endif
<x-mary-toast />
</div>
</div>
<div class="mt-6 flex items-center justify-end gap-x-6">
@if(!$update)
<a href="{{route('cards.index', $post->id)}}" class="btn btn-secondary" wire:navigate>{{__('Pass this step and upload cards')}}</a>
@endif
<button class="btn btn-primary" wire:click="save">{{__('Save')}}</button>
</div>
</div>
