<nav class="flex" aria-label="Breadcrumb">
  <ol role="list" class="dark:bg-gray-900 flex space-x-4 rounded-md bg-white px-6 shadow-sm">
    <li class="flex">
      <div class="flex items-center">
        <a href="/" class="text-gray-400 hover:text-gray-500">
          <svg class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
          </svg>
          <span class="sr-only">{{__('Home')}}</span>
        </a>
      </div>
    </li>
    @foreach($items as $item)
    <li class="flex">
      <div class="flex items-center">
        <svg class="h-full w-6 shrink-0 text-gray-500" viewBox="0 0 24 44" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
          <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
        </svg>
        <a href="@if($item[1] == NULL) # @else{{route($item[1])}} @endif" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{$item[0]}}</a>
      </div>
    </li>
    @endforeach
  </ol>
</nav>