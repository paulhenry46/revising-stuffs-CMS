<form method="post" wire:submit="save">
    <div class="space-y-12">
        <div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
            <div class="grid grid-cols-1 sm:grid-cols-6">
                <div class="sm:col-span-4 sm:mr-3">
                    <x-mary-input label="{{__('Name')}}" inline wire:model="name" />
                </div>
                <div class="sm:col-span-2">
            <x-mary-toggle label="{{__('Private')}}" wire:model="private" right hint="{{__('Make this groupe private to restrict acces to the posts associated to members of the group.')}}" />
                </div>
            </div>
        
        <div class="py-2"></div>
        <x-mary-textarea
    label="{{__('Description')}}"
    wire:model="description"
    placeholder="{{__('Your description ...')}}"
    rows="5"
    inline />
    <div class="py-2"></div>
    
        <x-mary-table
    :headers="$headers"
    :rows="$users"
    wire:model="selected_users"
    selectable />
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
        <a href="{{route('groups.index')}}" class="link link-hover link-error">{{__('Cancel')}}</a>
        <button  class="btn btn-primary">@if($group->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
    </div>
</form>