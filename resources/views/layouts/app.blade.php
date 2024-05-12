<!DOCTYPE html>
<html data-theme="dark" class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    @if(Route::is('files.*') )
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

    {{-- Sortable.js --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.1/Sortable.min.js"></script>
@endif
@if((Route::is('post.public.view')) or (Route::is('dashboard')) )
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
      <li><a @if(Route::is('post.public.news')) class="active" @endif wire:navigate href="{{route('post.public.news')}}">{{__('New posts')}}</a></li>
      <li><a @if((Route::is('post.public.library')) or (Route::is('post.public.courseView'))) class="active" @endif wire:navigate href="{{route('post.public.library')}}" >{{__('Library')}}</a></li>
      <li><a @if(Route::is('post.public.favorites')) class="active" @endif wire:navigate  href="{{route('post.public.favorites')}}" >{{__('Suggested posts')}}</a></li>
        <li>
          <a>{{__('Your profile')}}</a>
          <ul class="p-2">
          @auth
            <li><a @if(Route::is('dashboard')) class="active" @endif wire:navigate href="{{route('dashboard')}}" >{{__('Dashboard')}}</a></li>
            <li><a @if(Route::is('profile.show')) class="active" @endif wire:navigate href="{{route('profile.show')}}" >{{__('Settings')}}</a></li>
            <li><a @if(Route::is('posts.create')) class="active" @endif wire:navigate href="{{route('posts.create')}}" >{{__('Create a post')}}</a></li>
            @endauth
            @guest
            <li><a @if(Route::is('login')) class="active" @endif wire:navigate href="{{route('login')}}" >{{__('Login')}}</a></li>
            <li><a @if(Route::is('register')) class="active" @endif wire:navigate href="{{route('register')}}" >{{__('Register')}}</a></li>
            @endguest
          </ul>
        </li>
      </ul>
    </div>
    <a wire:navigate href="{{route('welcome')}}" class="btn btn-ghost text-xl">{{env('APP_NAME')}}</a>
  </div>
  <div class="navbar-center hidden lg:flex">
    <ul class="menu menu-horizontal px-1">
      <li><a @if(Route::is('post.public.news')) class="active" @endif wire:navigate href="{{route('post.public.news')}}">{{__('New posts')}}</a></li>
      <li><a @if((Route::is('post.public.library')) or (Route::is('post.public.courseView'))) class="active" @endif wire:navigate href="{{route('post.public.library')}}" >{{__('Library')}}</a></li>
      <li><a @if(Route::is('post.public.favorites')) class="active" @endif wire:navigate  href="{{route('post.public.favorites')}}" >{{__('Suggested posts')}}</a></li>
      <li>
        <details>
          <summary>{{__('Your profile')}}</summary>
          <ul class="p-2 z-10">
          @auth
            <li><a @if(Route::is('dashboard')) class="active" @endif wire:navigate href="{{route('dashboard')}}" >{{__('Dashboard')}}</a></li>
            <li><a @if(Route::is('profile.show')) class="active" @endif wire:navigate href="{{route('profile.show')}}" >{{__('Settings')}}</a></li>
            <li><a @if(Route::is('posts.create')) class="active" @endif wire:navigate href="{{route('posts.create')}}" >{{__('Create a post')}}</a></li>
            @endauth
            @guest
            <li><a @if(Route::is('login')) class="active" @endif wire:navigate href="{{route('login')}}" >{{__('Login')}}</a></li>
            <li><a @if(Route::is('register')) class="active" @endif wire:navigate href="{{route('register')}}" >{{__('Register')}}</a></li>
            @endguest
          </ul>
        </details>
      </li>
      
    </ul>
  </div>
  <div class="navbar-end">

  <label class="cursor-pointer grid place-items-center">
  <input onclick="editTheme()" type="checkbox" class="toggle theme-controller bg-base-content row-start-1 col-start-1 col-span-2"/>
  <svg class="col-start-1 row-start-1 stroke-base-100 fill-base-100" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/></svg>
  <svg class="col-start-2 row-start-1 stroke-base-100 fill-base-100" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
</label>
  </div>
</div>

        <div class="min-h-screen /*bg-gray-200*/ /*bg-gray-100*/ dark:bg-base-300 bg-gray-100">
            

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
<footer class="footer footer-center p-10 bg-base-100 dark:bg-base-200 text-base-content rounded">
<x-application-mark />
<nav class="">
    <p>{{env('APP_NAME')}}</p>
    <p>{{__('Version')}} {{env('APP_VERSION')}}</p></br>
    <div class="grid grid-flow-col gap-4">
    <a wire:navigate href="{{route('about.about')}}" class="link link-hover">{{__('About us')}}</a>
    <a wire:navigate href="{{route('about.licensing')}}" class="link link-hover">{{__('License')}}</a>
    <a wire:navigate href="{{route('about.legal')}}" class="link link-hover">{{__('Legal')}}</a>
</div> 
</nav>
  <aside>
    <p>{{__('Works thanks to ')}}<a class="link link-primary link-hover" href="https://github.com/paulhenry46/revising-stuffs-CMS">RSCMS</a>{{__(', under MIT license')}}</p>
  </aside>
</footer>
      
</html>
