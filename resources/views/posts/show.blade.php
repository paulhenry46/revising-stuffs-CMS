<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index')
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/> 
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    {{__('Manage your posts')}}
</h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<div class="px-4 sm:px-6 lg:px-8">
<div class="sm:flex sm:items-center">
<div class="sm:flex-auto">
  <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Posts')}}</h1>
  <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of your posts')}}</p>
</div>
<div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
  <a href="{{route('posts.create')}}" class="btn btn-primary">{{__('New post')}}</a>
</div>
</div>
<div class="mt-8 flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
    
    @if($posts->isEmpty())
    <div class="sm:col-span-4 rounded-box border-base-300 text-base-content/30 flex h-72 flex-col items-center justify-center gap-6 border-2 border-dashed p-10 text-center [text-wrap:balance]">
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M680-39q-17 0-28.5-12T640-80q0-17 11.5-28.5T680-120q66 0 113-47t47-113q0-17 12-29t29-12q17 0 28.5 12t11.5 29q0 100-70.5 170.5T680-39ZM80-640q-17 0-29-11.5T39-680q0-100 70.5-170.5T280-921q17 0 29 11.5t12 28.5q0 17-12 29t-29 12q-66 0-113 47t-47 113q0 17-11.5 28.5T80-640Zm689-143q12 12 12 28t-12 28L515-472q-11 12-27.5 11.5T459-472q-12-12-12-28.5t12-28.5l254-254q12-12 28-12t28 12Zm71 127q12 12 12 28.5T840-599L614-373q-11 11-27.5 11T558-373q-12-12-12.5-28.5T557-430l226-226q12-12 28.5-12t28.5 12ZM211-211q-91-91-91-219t91-219l92-92q12-12 28-12t28 12l31 31q7 7 12 14.5t10 15.5l148-149q12-12 28.5-12t28.5 12q12 12 12 28.5T617-772L444-599l-85 84 19 19q46 46 44 110t-49 111l-1 1q-11 11-27.5 11T316-274q-12-12-12-28.5t12-28.5q23-23 25.5-54.5T321-440l-47-46q-12-12-12-28.5t12-28.5l57-56q12-12 12-28.5T331-656l-64 64q-68 68-68 162.5T267-267q68 68 163 68t163-68l239-240q12-12 28.5-12t28.5 12q12 12 12 28.5T889-450L649-211q-91 91-219 91t-219-91Zm219-219Z"/></svg>
        <div>{{__('You don\'t have created any post. To create your first post, just click the button below !')}}<br/>
          <a href="{{route('posts.create')}}" class="mt-5 btn btn-primary">{{__('Create your firts post !')}}</a>
        </div> 
    </div>
    @else
    <table class="table table-zebra">
      <thead>
        <tr>
          <th>{{__('Title')}}</th>
          <th>{{__('State')}}</th>
          <th>{{__('Thanks')}}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($posts as $post)
        <tr>
            <td>
            
            <div class="flex items-center gap-3">
            <div>
              <div class="font-bold">{{ $post->title }}</div>
              <div class="text-sm opacity-50">{{ $post->course->name }}</div>
            </div>
          </div>
            </td>
            <td class="align-middle">
            <div class="flex items-center whitespace-nowrap text-sm">
              @if ($post->files->where('type', '=', 'primary light')->count() == 0)
              <div class="text-red-500">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-800v640-640 200-200Zm80 400h147q11-23 25.5-43t32.5-37H280v80Zm0 160h123q-3-20-3-40t3-40H280v80ZM200-80q-33 0-56.5-23.5T120-160v-640q0-33 23.5-56.5T200-880h320l240 240v92q-19-6-39-9t-41-3v-40H480v-200H200v640h227q11 23 25.5 43T485-80H200Zm480-400q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480Zm0 320q11 0 18.5-7.5T706-186q0-11-7.5-18.5T680-212q-11 0-18.5 7.5T654-186q0 11 7.5 18.5T680-160Zm-18-76h36v-10q0-11 6-19.5t14-16.5q14-12 22-23t8-31q0-29-19-46.5T680-400q-23 0-41.5 13.5T612-350l32 14q3-12 12.5-21t23.5-9q15 0 23.5 7.5T712-336q0 11-6 18.5T692-302q-6 6-12.5 12T668-276q-3 6-4.5 12t-1.5 14v14Z"/></svg>
              </div>
              @endif
              @if($post->published) 
              
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>
            
              @else 
              <div class="text-red-500">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/></svg>
            
   @endif  
              @if($post->pinned)
              <div class="ml-2 text-orange-500">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m640-480 80 80v80H520v240l-40 40-40-40v-240H240v-80l80-80v-280h-40v-80h400v80h-40v280Zm-286 80h252l-46-46v-314H400v314l-46 46Zm126 0Z"/></svg></div>
        @endif </div>
            </td>
            <td>{{ $post->thanks }}</td>
            <td class="align-middle">
            <div class="flex items-stretch justify-end relative  text-right">
            <a href="{{route('post.public.view', [$post->slug, $post->id])}}" class="ml-4 link link-primary">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
              <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
            </svg>
                </a>
                <a href="{{route('posts.edit', $post->id)}}" class=" ml-4 link link-primary">
                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/>
                  </svg>
                  <span class="sr-only">, {{ $post->name }}</span>
                </a>
                <a href="{{route('files.index', $post->id)}}" class="ml-4 link link-primary">
                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm300-263-37-37 37 37ZM620-140h38l121-122-18-19-19-18-122 121v38ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v120h-80v-80H520v-200H240v640h240v80H240Zm280-400Zm241 199-19-18 37 37-18-19Z"/>
                  </svg>
                </a>
                <a href="{{route('cards.index', $post->id)}}" class="ml-4 link link-primary">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="m608-368 46-166-142-98-46 166 142 98ZM160-207l-33-16q-31-13-42-44.5t3-62.5l72-156v279Zm160 87q-33 0-56.5-24T240-201v-239l107 294q3 7 5 13.5t7 12.5h-39Zm206-5q-31 11-62-3t-42-45L245-662q-11-31 3-61.5t45-41.5l301-110q31-11 61.5 3t41.5 45l178 489q11 31-3 61.5T827-235L526-125Zm-28-75 302-110-179-490-301 110 178 490Zm62-300Z"/>
                </svg>
                </a>
                <form action="{{route('posts.destroy', $post->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                <button type="submit" class="ml-4 link link-error">
                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                </svg>
                <span class="sr-only">, 
                  {{ $post->name }}
                </span></button></form>
</div>
            </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{$posts->links()}}
    @endif
    <div class="text-blue-500 text-red-500 text-orange-500 text-green-500 text-yellow-500 text-purple-500  text-pink-500" style="display: none;"></div>
  </div>
</div>
</div>
</div>
</div>
            </div>
        </div>
    </div>
</x-app-layout>
