<x-app-layout>
    <div class="py-12">
        <div class="sm:px-6 px-2">
            <div class=" dark:bg-gray-900 overflow-hidden dark:shadow-xl sm:rounded-lg">
                
                <div class="">
                    <x-info-message/>
                    <div class="justify-items-center grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-3 col-span-1 h-full ">
                            <h1 class="text-4xl text-center dark:text-white">{{__('Welcome to')}} {{env('APP_NAME')}}</h1>
                            <p class="sm:px-12 text-2xl text-center dark:text-white max-w-4xl">{{__('New resources are added throughout the year to support your revision. Here you\'ll find mind maps, summary sheets and flashcards.')}}</p>
                        </div>

                        
                    <div class="col-span-1 col-span-1 h-full ">
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
                    <div class="col-span-1 col-span-1">
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
                    <div class="col-span-1 col-span-1 h-full">
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
                </div>
            </div>
        </div>
</x-app-layout>
