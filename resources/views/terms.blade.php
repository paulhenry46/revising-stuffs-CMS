<x-guest-layout>
    <div class="pt-4 bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg prose dark:prose-invert">
                {!! $terms !!}
                <div class="col-span-3">
            <progress id="bucketA" class="progress progress-error w-full" value="50" max="100"></progress>
        <!--<div id="percent">Card 1 of 10</div>-->
</div>
<div class="col-span-3">
            <progress id="bucketB" class="progress progress-warning w-full" value="50" max="100"></progress>
</div>
<div class="col-span-6">
<progress id="bucketC" class="progress progress-success w-full" value="50" max="100"></progress>
</div>
            </div>
        </div>
    </div>
</x-guest-layout>
