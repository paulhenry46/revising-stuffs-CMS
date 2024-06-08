<x-app-layout>
    <div class="py-12">
        <div class="sm:px-6 px-2">
            <div class=" dark:bg-gray-800 overflow-hidden  sm:rounded-lg">
                
                <div class="">
                    <x-info-message/>
                    <!--<div class="justify-items-center grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-3 col-span-1 h-full ">
                            <h1 class="text-4xl text-center dark:text-white">{{__('Welcome to')}} {{env('APP_NAME')}}</h1>
                            <p class="sm:px-12 text-2xl text-center dark:text-white max-w-4xl">{{__('New resources are added throughout the year to support your revision. Here you\'ll find mind maps, summary sheets and flashcards.')}}</p>
                        </div>

                        
                    <div class="col-span-1 h-full ">
                        <div class="card w-full bg-primary text-primary-content h-full">
                            <div class="card-body">
                              <h2 class="card-title">{{__('News')}}</h2>
                              <p>{{__('In urgent need of revision? This is where you\'ll find the latest resources.')}}</p>
                              <div class="card-actions justify-end">
                                <a wire:navigate href="{{route('post.public.news')}}" class="btn">{{__('See')}}</a>
                              </div>
                            </div>
                          </div>
                    </div>
                    <div class="col-span-1">
                        <div class="card w-full bg-secondary text-success-content">
                            <div class="card-body">
                              <h2 class="card-title">{{__('Library')}}</h2>
                              <p>{{__('Need to consult the resources already added to get a head start or review a concept? Just click here!')}}</p>
                              <div class="card-actions justify-end">
                                <a wire:navigate href="{{route('post.public.library')}}" class="btn">{{__('See')}}</a>
                              </div>
                            </div>
                          </div>
                    </div>
                    <div class="col-span-1 h-full">
                        <div class="card w-full h-full bg-success text-warning-content">
                            <div class="card-body">
                              <h2 class="card-title">{{__('Participate')}}</h2>
                              <p>{{__('Want to suggest resources? Create an account and let\'s get started!')}}</p>
                              <div class="card-actions justify-end">
                                <a wire:navigate href="{{route('register')}}" class="btn">{{__('Register')}}</a>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>-->
                <div class="relative isolate">
      <svg class="absolute inset-x-0 top-0 -z-10 h-[64rem] w-full stroke-gray-200 dark:stroke-gray-900 [mask-image:radial-gradient(32rem_32rem_at_center,white,transparent)]" aria-hidden="true">
        <defs>
          <pattern id="1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
            <path d="M.5 200V.5H200" fill="none" />
          </pattern>
        </defs>
        <svg x="50%" y="-1" class="overflow-visible fill-gray-50 dark:fill-gray-600">
          <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z" stroke-width="0" />
        </svg>
        <rect width="100%" height="100%" stroke-width="0" fill="url(#1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84)" />
      </svg>
      <div class="absolute left-1/2 right-0 top-0 -z-10 -ml-24 transform-gpu overflow-hidden blur-3xl lg:ml-24 xl:ml-48" aria-hidden="true">
        <div class="aspect-[801/1036] w-[50.0625rem] bg-gradient-to-tr from-primary to-[#9089fc] opacity-30" style="clip-path: polygon(63.1% 29.5%, 100% 17.1%, 76.6% 3%, 48.4% 0%, 44.6% 4.7%, 54.5% 25.3%, 59.8% 49%, 55.2% 57.8%, 44.4% 57.2%, 27.8% 47.9%, 35.1% 81.5%, 0% 97.7%, 39.2% 100%, 35.2% 81.4%, 97.2% 52.8%, 63.1% 29.5%)"></div>
      </div>
      <div class="overflow-hidden">
        <div class="mx-auto max-w-7xl px-6 pb-32 pt-36 sm:pt-60 lg:px-8 lg:pt-32">
          <div class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-center">
            <div class="w-full max-w-xl lg:shrink-0 xl:max-w-2xl">
              <h1 class="text-4xl font-bold tracking-tight dark:text-white text-gray-900 sm:text-6xl">{{__('We help students with their revision work')}}</h1>
              <p class="relative mt-6 text-lg leading-8 text-gray-600 dark:text-gray-100 sm:max-w-md lg:max-w-none">{{__('New resources especially made for your curriculum are added throughout the year to support your revision. Here you\'ll find mind maps, summary sheets and flashcards.')}}</p>
              <div class="mt-10 flex items-center gap-x-6">
                <a wire:navigate href="{{route('login')}}" class="btn btn-primary">{{__('Login')}}</a>
                <a wire:navigate href="{{route('post.public.library')}}" class="btn btn-outline">{{__('See all posts')}}</a>
                <a wire:navigate href="{{route('contributor')}}" class="btn btn-outline">{{__('Become a contributor')}}</a>
              </div>
            </div>
            <div class="mt-14 flex justify-end gap-8 sm:-mt-44 sm:justify-start sm:pl-20 lg:mt-0 lg:pl-0">
              <div class="ml-auto w-44 flex-none space-y-8 pt-32 sm:ml-0 sm:pt-80 lg:order-last lg:pt-36 xl:order-none xl:pt-80">
                <div class="relative">
                  <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
                  <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                </div>
              </div>
              <div class="mr-auto w-44 flex-none space-y-8 sm:mr-0 sm:pt-52 lg:pt-36">
                <div class="relative">
                  <img src="https://images.unsplash.com/photo-1485217988980-11786ced9454?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
                  <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                </div>
                <div class="relative">
                  <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=focalpoint&fp-x=.4&w=396&h=528&q=80" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
                  <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                </div>
              </div>
              <div class="w-44 flex-none space-y-8 pt-32 sm:pt-0">
                <div class="relative">
                  <img src="https://unsplash.com/photos/XkKCui44iM0/download?ixid=M3wxMjA3fDB8MXxzZWFyY2h8MjB8fGxlYXJufGZyfDB8fHx8MTcxNzY2MTE1MXww&force=true&w=640" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
                  <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                </div>
                <div class="relative">
                  <img src="https://images.unsplash.com/photo-1670272505284-8faba1c31f7d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
                  <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
            </div>
        </div>
        <div class="py-2">
    <div class="bg-primary py-24 sm:py-32">
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-2xl lg:mx-0">
      <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{__('A wealth of resources to help you achieve excellence')}}</h2>
      <p class="mt-6 text-base leading-7 text-white">{{__('Written by students for students, they focus on the difficulties they may have encountered in their learning.')}}</p>
    </div>
    <div class="mx-auto mt-16 flex max-w-2xl flex-col gap-8 lg:mx-0 lg:mt-20 lg:max-w-none lg:flex-row lg:items-end">
      <div class="flex flex-col-reverse justify-between gap-x-16 gap-y-8 rounded-2xl bg-gray-50 p-8 sm:w-3/4 sm:max-w-md sm:flex-row-reverse sm:items-end lg:w-72 lg:max-w-none lg:flex-none lg:flex-col lg:items-start">
        <livewire:contributors-count />
        <div class="sm:w-80 sm:shrink lg:w-auto lg:flex-none">
          <p class="text-lg font-semibold tracking-tight text-gray-900">{{__('Contibutors on the platform')}}</p>
          <p class="mt-2 text-base leading-7 text-gray-600">{{__('Who add resources to their curriculum every day')}}</p>
        </div>
      </div>
      <div class="flex flex-col-reverse justify-between gap-x-16 gap-y-8 rounded-2xl bg-gray-900 p-8 sm:flex-row-reverse sm:items-end lg:w-full lg:max-w-sm lg:flex-auto lg:flex-col lg:items-start lg:gap-y-44">
        <p class="flex-none text-3xl font-bold tracking-tight text-white"><livewire:posts-count /></p>
        <div class="sm:w-80 sm:shrink lg:w-auto lg:flex-none">
          <p class="text-lg font-semibold tracking-tight text-white">{{__('We\'re proud to already have')}} <livewire:posts-count /> {{__('posts with')}} <livewire:courses-count /> {{__('courses covered!')}}</p>
          <p class="mt-2 text-base leading-7 text-gray-400">{{__('And that\'s just for starters!')}}</p>
        </div>
      </div>
      <div class="flex flex-col-reverse justify-between gap-x-16 gap-y-8 rounded-2xl bg-indigo-600 p-8 sm:w-11/12 sm:max-w-xl sm:flex-row-reverse sm:items-end lg:w-full lg:max-w-none lg:flex-auto lg:flex-col lg:items-start lg:gap-y-28">
        <p class="flex-none text-3xl font-bold tracking-tight text-white"><livewire:cards-count /></p>
        <div class="sm:w-80 sm:shrink lg:w-auto lg:flex-none">
          <p class="text-lg font-semibold tracking-tight text-white">{{__('Flashcards linked to resources')}}</p>
          <p class="mt-2 text-base leading-7 text-indigo-200">{{__('Perfect for learning vocabulary words, definitions or complex formulas, they enable long-term memorization.')}}</p>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
    <div class="">
  <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:flex lg:items-center lg:justify-between lg:px-8">
    <h2 class="text-3xl font-bold tracking-tight dark:text-white text-gray-900 sm:text-4xl">{{__('Ready to dive in?')}}<br>{{__('Start learning right away!')}}</h2>
    <div class="mt-10 flex items-center gap-x-6 lg:mt-0 lg:flex-shrink-0">
      <a href="{{route('login')}}" class="btn btn-primary">{{__('Login')}}</a>
                <a href="{{route('post.public.library')}}" class="btn btn-outline">{{__('See all posts')}}</a>
    </div>
  </div>
</div>
</x-app-layout>
