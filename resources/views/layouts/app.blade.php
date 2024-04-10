<!DOCTYPE html>
<html data-theme="dark" class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    @if(Route::is('files.*') )
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

    {{-- Sortable.js --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.1/Sortable.min.js"></script>
@endif
@if(Route::is('post.public.view') )
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endif
    
        @if(env('PWA')==true)
        <link rel="manifest" href="/manifest.webmanifest" />
        @endif
    <script type="text/javascript">
        function changeTheme() {
            if (localStorage.theme === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
                document.querySelector('html').setAttribute('data-theme', 'light')
                  document.querySelector('html').classList.remove('dark')
                document.querySelector('html').classList.add('light')
                localStorage.theme = 'light'
 
} else {
  document.querySelector('html').setAttribute('data-theme', 'dark')
  document.querySelector('html').classList.remove('light')
  document.querySelector('html').classList.add('dark')
  localStorage.theme = 'dark'
}
        }

        window.onload = changeTheme;
    
function editTheme(){
    
    if ( localStorage.theme === 'dark'){
        localStorage.theme = 'light'
    }else{
        localStorage.theme = 'dark'
    }
    changeTheme()
}
      </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
    <x-mary-toast />
        <x-banner />
        <div class="navbar bg-base-100">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
      </div>
      <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
        <li><a>{{__('See published posts')}}</a></li>
        <li>
          <a>{{__('Your profile')}}</a>
          <ul class="p-2">
            <li><a>{{__('Dashboard')}}</a></li>
            <li><a>{{__('Create a post')}}</a></li>
          </ul>
        </li>
        <li><a>Item 3</a></li>
      </ul>
    </div>
    <a class="btn btn-ghost text-xl">{{env('APP_NAME')}}</a>
  </div>
  <div class="navbar-center hidden lg:flex">
    <ul class="menu menu-horizontal px-1">
      <li><a @if(Route::is('post.public.news')) class="active" @endif wire:navigate href="{{route('post.public.news')}}">{{__('New posts')}}</a></li>
      <li><a @if((Route::is('post.public.library')) or (Route::is('post.public.courseView'))) class="active" @endif wire:navigate href="{{route('post.public.library')}}" >{{__('Library')}}</a></li>
      <li><a @if(Route::is('post.public.favorites')) class="active" @endif wire:navigate  href="{{route('post.public.favorites')}}" >{{__('Suggested posts')}}</a></li>
      <li>
        <details>
          <summary>{{__('Your profile')}}</summary>
          <ul class="p-2">
            <li><a @if(Route::is('dashboard')) class="active" @endif wire:navigate href="{{route('dashboard')}}" >{{__('Dashboard')}}</a></li>
            <li><a @if(Route::is('profile.show')) class="active" @endif wire:navigate href="{{route('profile.show')}}" >{{__('Settings')}}</a></li>
            <li><a @if(Route::is('posts.create')) class="active" @endif wire:navigate href="{{route('posts.create')}}" >{{__('Create a post')}}</a></li>
          </ul>
        </details>
      </li>
      
    </ul>
  </div>
  <div class="navbar-end">
    
  </div>
</div>

        <div class="min-h-screen /*bg-gray-200*/ /*bg-gray-100*/ dark:bg-gray-900 bg-gray-100">
            

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-gray-300 dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
    <footer class="footer footer-center p-10 bg-neutral text-neutral-content">
        <aside>
            <button onclick="editTheme()" class="btn btn-primary">{{__('Change theme')}}</button>
            <x-application-mark class="block h-9 w-auto" />
          <p class="font-bold">
            {{env('APP_NAME')}}
          </p> 
          <p>Works thanks to <a class="link link-primary" href="https://github.com/paulhenry46/revising-stuffs-CMS">RSCMS</a>, under MIT license</p>
          <p>{{__('Version')}} {{env('APP_VERSION')}}</p>
        </aside> 
        
      </footer>
      
</html>
