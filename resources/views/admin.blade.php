<x-app-layout>
    <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li>
      {{__('Settings')}}
    </li>
  </ul>
</div>
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
                <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
                    {{__('Admin Settings')}}
                </h1>
            </div>
            <div class="bg-white dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
                <x-info-message/>
                <div role="tablist" class="tabs tabs-bordered">
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="{{__('Backups')}}" checked />
                    <div role="tabpanel" class="tab-content p-10">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Backups')}}</h1>
                                    <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('Create and programate backups')}}</p>
                                </div>
                            </div>
                            <div class="mt-8 flow-root">
                                    <div class="card bg-base-100 shadow-xl">
                                        <div class="card-body">
                                            <h2 class="card-title">{{__('Save Files')}}</h2>
                                            <p>{{__('Download all the files in Public Folder. It includes all the primary files, complementary files and thumbnails.')}}</p>
                                            <div class="card-actions justify-end">
                                            <a href="{{route('settings.downloadFiles')}}" class="btn btn-primary">
                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                <path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q17-72 85-137t145-65q33 0 56.5 23.5T520-716v242l64-62 56 56-160 160-160-160 56-56 64 62v-242q-76 14-118 73.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h480q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-48-22-89.5T600-680v-93q74 35 117 103.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H260Zm220-358Z"/>
                                            </svg>
                                                {{__('Download')}}
                                            </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 card bg-base-100 shadow-xl">
                                        <div class="card-body">
                                            <h2 class="card-title">{{__('Save DataBase')}}</h2>
                                            <p>{{('Export the content of the DataBase used. Only available for mysql/mariaDB and mysqldump must be installed.')}}</p>
                                            <div class="card-actions justify-end">
                                            <a href="{{route('settings.downloadDB')}}" class="btn btn-primary">
                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                <path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q17-72 85-137t145-65q33 0 56.5 23.5T520-716v242l64-62 56 56-160 160-160-160 56-56 64 62v-242q-76 14-118 73.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h480q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-48-22-89.5T600-680v-93q74 35 117 103.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H260Zm220-358Z"/>
                                            </svg>
                                                {{__('Download')}}</a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
