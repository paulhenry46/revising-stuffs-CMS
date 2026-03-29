<div class=" mb-3 card bg-base-100">
    <div class="card-body">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="card-title">{{__('Downloads overview')}}</h2>
            <div class="flex flex-wrap gap-3 items-center">
                {{-- Period selector --}}
                <div class="form-control">
                    <label class="label pb-0">
                        <span class="label-text text-xs">{{__('Period')}}</span>
                    </label>
                    <select wire:model.live="period" class="select select-bordered select-sm">
                        <option value="month">{{__('By month')}}</option>
                        <option value="week">{{__('By week')}}</option>
                    </select>
                </div>

                {{-- Course filter --}}
                <div class="form-control">
                    <label class="label pb-0">
                        <span class="label-text text-xs">{{__('Course')}}</span>
                    </label>
                    <select wire:model.live="course_id" class="select select-bordered select-sm">
                        <option value="">{{__('All courses')}}</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Level filter --}}
                <div class="form-control">
                    <label class="label pb-0">
                        <span class="label-text text-xs">{{__('Level')}}</span>
                    </label>
                    <select wire:model.live="level_id" class="select select-bordered select-sm">
                        <option value="">{{__('All levels')}}</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <x-mary-chart wire:model="HistoryChart" class="min-h-60 md:min-h-72" />
    </div>
</div>
