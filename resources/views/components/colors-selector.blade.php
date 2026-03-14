@props(['course'])
<div x-data="{colors: [
          { value: 'gray-500', label: 'Gray' },
          { value: 'blue-500', label: 'Blue' },
          { value: 'purple-500', label: 'Purple' },
          { value: 'pink-500', label: 'Pink' },
          { value: 'red-500', label: 'Red' },
          { value: 'orange-500', label: 'Orange' },
          { value: 'yellow-500', label: 'Yellow' },
          { value: 'green-500', label: 'Green' }
        ],
        selectedColor: '{{ old('color', $course->color) }}',}" class="w-full">
        <legend class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Choose a label color')}}</legend>
        <div class="mt-4 flex items-center space-x-3 fieldset">
          <template x-for="color in colors" :key="color.value">
            <label :class="(selectedColor === color.value ? 'ring-2' : '') + ' relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-' + color.value">
              <input type="radio" name="color" :value="color.value" class="sr-only" :checked="selectedColor === color.value" @change="selectedColor = color.value">
              <span class="sr-only" x-text="color.label"></span>
              <span aria-hidden="true" :class="'h-8 w-8 bg-' + color.value + ' rounded-full'"></span>
            </label>
          </template>
        </div>
        <input type="hidden" name="color" :value="selectedColor">
        <div class="border-blue-500 border-green-500 border-purple-500 border-yellow-500 border-orange-500 border-gray-500 border-pink-500 border-red-500
                             checked:border-blue-500 checked:border-green-500 checked:border-purple-500 checked:border-yellow-500 checked:border-orange-500 checked:border-gray-500 checked:border-pink-500 checked:border-red-500
                             [--chkbg:var(--color-blue-500)] [--chkbg:var(--color-green-500)] [--chkbg:var(--color-purple-500)] [--chkbg:var(--color-yellow-500)] [--chkbg:var(--color-orange-500)] [--chkbg:var(--color-gray-500)] [--chkbg:var(--color-pink-500)] [--chkbg:var(--color-red-500)]
                             bg-blue-100 bg-green-100 bg-purple-100 bg-yellow-100 bg-orange-100 bg-gray-100 bg-pink-100 bg-red-100
                             bg-blue-700 bg-green-700 bg-purple-700 bg-yellow-700 bg-orange-700 bg-gray-700 bg-pink-700 bg-red-700" style="display:none;"></div>
      </div>