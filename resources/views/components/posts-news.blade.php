<div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class=" decoration-4 underline decoration-primary text-2xl font-medium text-gray-900 dark:text-white">
        {{__('New posts')}}
    </h1>
    <p>See the latest posts created</p>

</div>


</div>
<div class="grid grid-cols-4 gap-4">
@foreach($pinnedPosts as $post)
<div class="pt-4 col-span-4 lg:col-span-1">
<x-post-card :post=$post :starred=false/>
                                </div>
                                @endforeach
@foreach($newPosts as $post)
<div class="pt-4 col-span-4 lg:col-span-1">
<x-post-card :post=$post :starred=false/>
                                </div>
                                @endforeach
                              </div>
