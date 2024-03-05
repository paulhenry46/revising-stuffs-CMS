<!DOCTYPE html>
<html data-theme="dark" class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
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
        <x-banner />

        <div class="min-h-screen bg-gray-200 /*bg-gray-100*/ dark:bg-gray-900">
            @livewire('navigation-menu')

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
