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
            <div class="bg-white/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
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
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="{{__('Schools')}}" />
                    <div role="tabpanel" class="tab-content p-10">
                    <div class="px-4 sm:px-6 lg:px-8">
<div class="sm:flex sm:items-center">
<div class="sm:flex-auto">
  <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Schools')}}</h1>
  <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of the schools')}}</p>
</div>
<div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
  <a href="{{route('schools.create')}}" class="btn btn-primary">{{__('New school')}}</a>
</div>
</div>
<div class="mt-8 flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
    <table class="table table-zebra">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Curricula')}}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      @foreach($schools as $school)
        <tr>
            <td>{{ $school->id }}</td>
            <td>{{$school->name}}</td>
            <td>
           
              @foreach($school->curricula as $curriculum)
              
              <div class="badge badge-outline">{{$curriculum->name}}</div>
              @endforeach
            </td>
            <td class="flex items-stretch justify-end relative  text-right">
                <a href="{{route('schools.edit', $school->id)}}" class="link link-primary"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/>
                  </svg><span class="sr-only">, {{ $school->name }}</span></a>
  
                <form action="{{route('schools.destroy', $school->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                <button type="submit" class="ml-4 link link-error"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg><span class="sr-only">, {{ $curriculum->name }}</span></button></form>
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
                <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="{{__('Curricula')}}" />
                <div role="tabpanel" class="tab-content p-10">
                <div class="px-4 sm:px-6 lg:px-8">
<div class="sm:flex sm:items-center">
<div class="sm:flex-auto">
  <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Curricula')}}</h1>
  <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of the curricula')}}</p>
</div>
<div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
  <a href="{{route('curricula.create')}}" class="btn btn-primary">{{__('New curriculum')}}</a>
</div>
</div>
<div class="mt-8 flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
    <table class="table table-zebra">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Schools')}}</th>
          <th>{{__('Levels')}}</th>
          <th>{{__('Subdomain')}}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($curricula as $curriculum)
        <tr>
            <td>{{ $curriculum->id }}</td>
            <td>{{$curriculum->name}}</td>
            <td>
              @foreach($curriculum->schools as $school)
              <div class="badge badge-outline">{{$school->name}}</div>
              @endforeach
            </td>
            <td>
              @foreach($curriculum->levels as $level)
              <div class="badge badge-outline">{{$level->name}}</div>
              @endforeach
            </td>
            <td>
              @if($curriculum->subdomain)
                <div class="badge {{ $curriculum->subdomain_enabled ? 'badge-success' : 'badge-warning' }} badge-outline">
                  {{$curriculum->subdomain}}
                  @if(!$curriculum->subdomain_enabled) ({{__('disabled')}}) @endif
                </div>
              @else
                <span class="text-gray-400 text-sm">—</span>
              @endif
            </td>
            <td class="flex items-stretch justify-end relative  text-right">
                <a href="{{route('curricula.edit', $curriculum->id)}}" class="link link-primary"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/>
                  </svg><span class="sr-only">, {{ $curriculum->name }}</span></a>
  
                <form action="{{route('curricula.destroy', $curriculum->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                <button type="submit" class="ml-4 link link-error"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg><span class="sr-only">, {{ $curriculum->name }}</span></button></form>
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


                <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="{{__('Welcome Pages')}}" />
                    <div role="tabpanel" class="tab-content p-10">
                      <div class="px-4 sm:px-6 lg:px-8">
                        <div class="sm:flex sm:items-center">
                          <div class="sm:flex-auto">
                            <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Pending Welcome Pages')}}</h1>
                            <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of pending welcome pages')}}</p>
                          </div>
                        </div>
                      </div>
                      <livewire:admin.pending-welcome-pages/>
                    </div>


                <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="{{__('Email Domain Rules')}}" />
                <div role="tabpanel" class="tab-content p-10">
                <div class="px-4 sm:px-6 lg:px-8">
<div class="sm:flex sm:items-center">
<div class="sm:flex-auto">
  <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Email Domain Rules')}}</h1>
  <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('Automatically assign a role to users who verify their email address with a specific domain. Only contributor and co-admin roles are available.')}}</p>
</div>
</div>


<div class="mt-8 flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
    <table class="table table-zebra">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{__('Domain')}}</th>
          <th>{{__('Role')}}</th>
          <th>{{__('Curricula')}} <span class="text-xs text-gray-500">({{__('co-admin')}})</span></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($emailDomainRules as $rule)
        <tr>
            <td>{{ $rule->id }}</td>
            <td>{{ $rule->domain }}</td>
            <td>
              @if($rule->role === 'co-admin')
                <span class="badge badge-outline text-purple-500">{{__('Co-Admin')}}</span>
              @else
                <span class="badge badge-outline badge-primary">{{__('Contributor')}}</span>
              @endif
            </td>
            <td>
              @foreach($rule->curricula as $curriculum)
                <div class="badge badge-outline">{{ $curriculum->name }}</div>
              @endforeach
            </td>
            <td class="text-right">
              <button class="link link-primary" onclick="document.getElementById('edit-rule-{{ $rule->id }}').showModal()">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/></svg>
                <span class="sr-only">, {{ $rule->domain }}</span>
              </button>
              <form action="{{route('email-domain-rules.destroy', $rule->id)}}" method="POST" class="inline ml-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="link link-error">
                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                  <span class="sr-only">, {{ $rule->domain }}</span>
                </button>
              </form>

              <dialog id="edit-rule-{{ $rule->id }}" class="modal">
                <div class="modal-box">
                  <form action="{{route('email-domain-rules.update', $rule->id)}}" method="POST" x-data="{ role: '{{ $rule->role }}' }">
                    @csrf
                    @method('PUT')
                    <div class="form-control mt-4">
                      <label class="label" for="domain_{{ $rule->id }}"><span class="label-text">{{__('Email domain')}} <span class="text-error">*</span></span></label>
                      <input type="text" id="domain_{{ $rule->id }}" name="domain" value="{{ $rule->domain }}" class="input input-bordered input-primary w-full" required />
                    </div>
                    <div class="form-control mt-4">
                      <label class="label" for="role_{{ $rule->id }}"><span class="label-text">{{__('Role')}} <span class="text-error">*</span></span></label>
                      <select id="role_{{ $rule->id }}" name="role" class="select select-bordered select-primary w-full" x-model="role" required>
                        <option value="contributor" {{ $rule->role === 'contributor' ? 'selected' : '' }}>{{__('Contributor')}}</option>
                        <option value="co-admin" {{ $rule->role === 'co-admin' ? 'selected' : '' }}>{{__('Co-Admin')}}</option>
                      </select>
                    </div>
                    <div class="form-control mt-4" x-show="role === 'co-admin'" x-transition>
                      <label class="label"><span class="label-text">{{__('Curricula')}} <span class="text-xs text-gray-500">({{__('for co-admin')}})</span></span></label>
                      <div class="flex flex-wrap gap-2">
                        @foreach($curricula as $curriculum)
                        <label class="label cursor-pointer gap-2">
                          <input type="checkbox" name="curricula[]" value="{{ $curriculum->id }}" class="checkbox checkbox-primary"
                            {{ $rule->curricula->contains($curriculum->id) ? 'checked' : '' }} />
                          <span class="label-text">{{ $curriculum->name }}</span>
                        </label>
                        @endforeach
                      </div>
                    </div>
                    <div class="modal-action">
                      <button type="button" class="btn" onclick="document.getElementById('edit-rule-{{ $rule->id }}').close()">{{__('Cancel')}}</button>
                      <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                    </div>
                  </form>
                </div>
              </dialog>
            </td>
        </tr>
        @endforeach
        @if($emailDomainRules->isEmpty())
        <tr><td colspan="5" class="text-center text-gray-400 py-6">{{__('No rules defined yet.')}}</td></tr>
        @endif
      </tbody>
    </table>
  </div>
  <form class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8" action="{{route('email-domain-rules.store')}}" method="POST" x-data="{ role: '{{ old('role', 'contributor') }}' }">
        @csrf
        <div class='flex space-x-2 justify-between'>
        <div class='flex space-x-2'>
        <div class="form-control mt-4">
          <input type="text" id="domain_new" name="domain" value="{{ old('domain') }}" placeholder="@example.com" class="input input-bordered input-primary w-full max-w-xs" required />
          @error('domain') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="form-control mt-4">
          <select id="role_new" name="role" class="select select-bordered select-primary w-full max-w-xs" x-model="role" required>
            <option value="contributor">{{__('Contributor')}}</option>
            <option value="co-admin">{{__('Co-Admin')}}</option>
          </select>
          @error('role') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
        </div>
      </div>
        <div class=" justify-end mt-4">
          <button type="submit" class="btn btn-primary">{{__('Add rule')}}</button>
        </div>
      </div>
        <div class="form-control mt-4" x-show="role === 'co-admin'" x-transition>
          <label class="label"><span class="label-text">{{__('Curricula')}} <span class="text-xs text-gray-500">({{__('for co-admin')}})</span></span></label>
          <div class="flex flex-wrap gap-2">
            @foreach($curricula as $curriculum)
            <label class="label cursor-pointer gap-2">
              <input type="checkbox" name="curricula[]" value="{{ $curriculum->id }}" class="checkbox checkbox-primary"
                {{ in_array($curriculum->id, old('curricula', [])) ? 'checked' : '' }} />
              <span class="label-text">{{ $curriculum->name }}</span>
            </label>
            @endforeach
          </div>
          @error('curricula') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        
      </form>
</div>
</div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
