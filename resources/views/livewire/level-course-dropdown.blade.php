<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
        <div>
            <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Level')}}</label>
            <div class="mt-2">
                <select wire:model.live="level" id="location" name="level_id" class="select select-bordered w-full">
                    
                <option>{{__('Select Level')}}</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="sm:col-span-3">
        <div>
            <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Course')}}</label>
            <div class="mt-2">
                <select wire:model.live="course" id="location" name="course_id" class="select select-bordered w-full">
                        <option>{{__('Select Course')}}</option>
                    @foreach($courses as $course)
                        <option  value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="sm:col-span-3">
        <div>
            <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Type')}}</label>
            <div class="mt-2">
                <select wire:model.live="type" id="location" name="type_id" class="select select-bordered w-full">
                    @foreach($types as $type)
                        <option  value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>