<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
        <div>
            <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Level')}}</label>
            <div class="mt-2">
                <select wire:model.live="level" id="location" name="level_id" class="select select-bordered w-full">
                <option>{{__('Select Level')}}</option>
                    @foreach($levels as $level)
                        <option @if($user->level == $level->id) selected @endif value="{{ $level->id }}">{{$level->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="sm:col-span-3">
            <label for="location" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Course')}}</label>
            <div class="mt-2">
            @foreach($courses as $course)
            <div wire:key="type_{{ $course->id }}" class="form-control">
                <label class="cursor-pointer label">
                    <span class="label-text">{{$course->name}}</span>
                    <input name="course_{{$course->id}}" @if($user->hasCourse($course->id)) checked="yes" @endif value="{{$course->id}}"  type="checkbox" 
                    class="checkbox  border-{{$course->color}} checked:border-{{$course->color}} [--chkbg:theme(colors.{{str_replace('-', '.',$course->color)}})] [--chkfg:black]"
                    />
                </label>
            </div>
            @endforeach
            </div>
    </div>
</div>