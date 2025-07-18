<div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-linear-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Comments which need to be validated')}}
    </h1>

</div>

<div class="bg-gray-200/25 dark:bg-gray-800/25 gap-6 lg:gap-8 p-6 lg:p-8">
    <x-info-message/>
<div class="px-4 sm:px-6 lg:px-8">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Comments')}}</h1>
    </div>
  </div>
  <div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <table class="min-w-full divide-y divide-gray-300">
          <thead>
            <tr>
              <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Type')}}</th>
              <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Author')}}</th>
              <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Content')}}</th>
              <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Post')}}</th>
              <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-0">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:bg-gray-800 bg-white">
            @foreach($comments as $comment)
            <tr>
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium dark:text-gray-100 text-gray-900 sm:pl-0">
                	@if($comment->type == "comment")
                	<div class="text-green-500">
                	<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
                </div>
                	@else
                	<div class="text-red-500">
                	<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
                </div>
                	@endif
                </td>
                <td class="flex items-center whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">
                	@if($comment->user_id == 1)
                	<div class="text-red-500">
                	<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M608-522 422-708q14-6 28.5-9t29.5-3q59 0 99.5 40.5T620-580q0 15-3 29.5t-9 28.5ZM234-276q51-39 114-61.5T480-360q18 0 34.5 1.5T549-354l-88-88q-47-6-80.5-39.5T341-562L227-676q-32 41-49.5 90.5T160-480q0 59 19.5 111t54.5 93Zm498-8q32-41 50-90.5T800-480q0-133-93.5-226.5T480-800q-56 0-105.5 18T284-732l448 448ZM480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-155.5t86-127Q252-817 325-848.5T480-880q83 0 155.5 31.5t127 86q54.5 54.5 86 127T880-480q0 82-31.5 155t-86 127.5q-54.5 54.5-127 86T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-60Z"/></svg>
                </div>
                	<div class="pl-2 truncate text-sm font-medium leading-6 text-white">{{ $comment->pseudo }}</div>
                	@else
                	<div class="flex items-center gap-x-4">
            <img src="{{$comment->user->profile_photo_url}}" alt="" class="h-8 w-8 rounded-full bg-gray-800">
            <div class="pl-2 truncate text-sm font-medium leading-6 text-white">{{ $comment->user->name }}</div>
          </div>
                	@endif
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">{{ $comment->content }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">{{ $comment->post->title }}</td>
                <td class="flex items-stretch justify-end relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                    <a href="{{route('post.public.view', [$comment->post->slug, $comment->post->id])}}" class="text-indigo-600 hover:text-indigo-400">

                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg> <span class="sr-only">, {{ $comment->name }}</span></a>
                  <form action="{{route('comments.valid', $comment->id)}}" method="POST">
                  @csrf
                  <button type="submit" class="ml-4 text-green-600 hover:text-green-400">
                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg></button></form>
                  <form action="{{route('comments.destroy', $comment->id)}}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="ml-4 text-red-600 hover:text-red-400">

                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m376-300 104-104 104 104 56-56-104-104 104-104-56-56-104 104-104-104-56 56 104 104-104 104 56 56Zm-96 180q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg></button></form>
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>