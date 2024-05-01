<x-app-layout>
    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if($post->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Posts')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('New post')}}
    </a></li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Posts')}}
    </a></li>
    <li><a wire:navigate href="{{route('post.public.view', [$post->slug, $post->id])}}">
      {{__('Edit post : ')}}{{$post->title}}
    </a></li>
  </ul>
</div>
@endif
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
            <h1 class=" text-2xl font-medium text-gray-900 dark:text-white"> @if($post->id !== 0) {{__('Edit a post')}} @else {{__('Add a new post')}} @endif </h1>
          </div>
          <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
            <x-info-message />
            <form method="POST" action="@if($post->id !== 0) {{route('posts.update', $post->id)}} @else {{route('posts.store')}} @endif"> @csrf @if($post->id !== 0) @method('put') @endif <div class="space-y-12">
                <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
                  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('General Informations')}}</h2>
                  <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('This information is used to classify and recognise your posts.')}}</p>
                  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Title')}}</label>
                      <div class="mt-2">
                        <input value="{{ old('title', $post->title) }}" type="text" name="title" id="name" autocomplete="given-name" class="input input-bordered w-full max-w" value="{{ old('name', $post->name) }}">
                      </div>
                    </div>
                  </div> @if($post->id !== 0) @livewire('level-course-dropdown', ['level'=>old('level_id', $post->level->id), 'course'=>old('course_id', $post->course->id), 'type'=> old('type_id', $post->type_id)]) @else @livewire('level-course-dropdown', ['level'=>old('level_id', NULL), 'course'=>old('course_id', NULL), 'type'=> old('type_id', NULL)]) @endif
                </div>
                <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
                  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Detailed Informations')}}</h2>
                  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="col-span-full">
                      <label for="about" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Description')}}</label>
                      <div class="mt-2">
                        <textarea id="about" name="description" rows="3" class="textarea textarea-bordered w-full">{{ old('description', $post->description) }}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                      <div class="">
                        <div class="text-sm leading-6">
                          <label for="public" class="font-medium text-gray-900 dark:text-white">{{__('Visibility')}}</label>
                          <p id="public-description" class="text-gray-500 dark:text-gray-400">{{__('Choose who can see this post.')}}</p>
                          <div class="form-control">
                            <label class="label cursor-pointer">
                              <span class="btn btn-ghost">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                  <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-40-82v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm276-102q20-22 36-47.5t26.5-53q10.5-27.5 16-56.5t5.5-59q0-98-54.5-179T600-776v16q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q17 0 28.5 11.5T600-440v120h40q26 0 47 15.5t29 40.5Z" />
                                </svg> {{__('Public')}}
                              </span>
                              <input value="2" {{ (old('visibility', $post->group_id) == 2) ? 'checked' : '' }} type="radio" name="visibility" class="radio checked:bg-success" />
                            </label>
                          </div>
                          <div class="form-control">
                            <label class="label cursor-pointer">
                              <span class="btn btn-ghost ">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                  <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z" />
                                </svg> {{__('Private')}}
                              </span>
                              <input value="1" {{ (old('visibility', $post->group_id) == 1) ? 'checked' : '' }} type="radio" name="visibility" class="radio checked:bg-error" />
                            </label>
                          </div> @foreach ($groups as $group) <div class="form-control">
                            <label class="label cursor-pointer">
                              <span class="btn btn-ghost">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                  <path d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q32-20 76.5-30t96.5-10q53 0 97.5 10t76.5 30q32 20 49 46.5t17 58.5v65H240Zm540 0v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780Zm-455-80h311q-10-20-55.5-35T480-370q-55 0-100.5 15T325-320ZM160-440q-33 0-56.5-23.5T80-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-440Zm640 0q-33 0-56.5-23.5T720-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-440Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-600q0 50-34.5 85T480-480Zm0-80q17 0 28.5-11.5T520-600q0-17-11.5-28.5T480-640q-17 0-28.5 11.5T440-600q0 17 11.5 28.5T480-560Zm1 240Zm-1-280Z" />
                                </svg> {{__('Group')}} : {{$group->name}}
                              </span>
                              <input value="{{$group->id}}" {{ (old('visibility', $post->group_id) == $group->id) ? 'checked' : '' }} type="radio" name="visibility" class="radio checked:bg-primary" />
                            </label>
                          </div> @endforeach
                        </div>
                      </div>
                    </div>
                    <div class="sm:col-span-3">
                      <fieldset class="mb-4">
                        <legend class="sr-only">Notifications</legend>
                        <div class="space-y-5">
                          <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                              <input {{ (old('dark_version', $post->dark_version) == 1 or old('dark_version', $post->dark_version) == 'on') ? 'checked' : '' }} id="dark_version" aria-describedby="dark_version-description" name="dark_version" type="checkbox" checked="checked" class="checkbox checkbox-primary checkbox-sm" />
                            </div>
                            <div class="ml-3 text-sm leading-6">
                              <label for="dark_version" class="font-medium text-gray-900 dark:text-white">{{__('Dark Version')}}</label>
                              <p id="dark_version-description" class="text-gray-500 dark:text-gray-400">{{__('You provide a dark version of this ressource.')}}</p>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <label for="quizlet_url" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">{{__('Link of an Quizlet Asset')}}</label>
                      <div class="mt-2">
                        <input value="{{ old('quizlet_url', $post->quizlet_url) }}" type="text" name="quizlet_url" id="quizlet_url" class="input input-bordered w-full">
                      </div>
                    </div>
                  </div>
                </div> @can('publish own posts') <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
                  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Advanced options')}}</h2>
                  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                      <fieldset>
                        <legend class="sr-only">Notifications</legend>
                        <div class="space-y-5">
                          <div class="sm:col-span-3">
                            <div class="relative flex items-start">
                              <div class="flex h-6 items-center">
                                <input {{ (old('published', $post->published) === 1) ? 'checked' : '' }} id="published" aria-describedby="dark_version-description" name="published" type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
                              </div>
                              <div class="ml-3 text-sm leading-6">
                                <label for="published" class="font-medium text-gray-900 dark:text-white">{{__('Publish post')}}</label>
                                <p id="published-description" class="text-gray-500 dark:text-gray-400">{{__('Publish this article if all is fine.')}}</p>
                              </div>
                            </div>
                          </div> @can('manage all posts') <div class="sm:col-span-3">
                            <div class="relative flex items-start">
                              <div class="flex h-6 items-center">
                                <input {{ (old('pinned', $post->pinned) === 1) ? 'checked' : '' }} id="pinned" aria-describedby="public-description" name="pinned" type="checkbox" class="checkbox checkbox-warning checkbox-sm" />
                              </div>
                              <div class="ml-3 text-sm leading-6">
                                <label for="pinned" class="font-medium text-gray-900 dark:text-white">{{__('Pin post')}}</label>
                                <p id="pinned-description" class="text-gray-500 dark:text-gray-400">{{__('Highlight this post on the news page')}}</p>
                              </div>
                            </div>
                          </div>
                          <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Date')}}</label>
                            <div class="mt-2">
                              <input value="{{ old('date', $post->created_at) }}" type="datetime-local" name="date" id="date" class="input input-bordered max-w">
                            </div>
                          </div> @endcan
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </div> @endcan <div class="mt-6 flex items-center justify-end gap-x-6">
                  <a href="{{route('posts.index')}}" class="link link-hover link-error">{{__('Cancel')}}</a>
                  <button type="submit" class="btn btn-primary">@if($post->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</x-app-layout>
