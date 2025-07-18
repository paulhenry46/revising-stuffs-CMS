<div class="mt-6 mb-3 card bg-base-100 shadow-xl" x-data="{ open: false, new_deck: false, close(){this.open = false; this.new_deck = false; $wire.name = ''; $wire.color = '';}, open_item(id){$wire.setDeck(id); open = true; my_modal_1.showModal();}, new_item(){this.new_deck = true; this.open = true; my_modal_1.showModal();} }">
    <div class="card-body">
        <div class="flex justify-between gap-x-2">
            <h2 class="card-title">{{ ('Your decks') }}</h2>
            <button x-on:click='new_item()' class="btn btn-primary">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-440v120q0 17 11.5 28.5T480-280q17 0 28.5-11.5T520-320v-120h120q17 0 28.5-11.5T680-480q0-17-11.5-28.5T640-520H520v-120q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640v120H320q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440h120ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Z"></path></svg>
                    {{ __('Create') }}
            </button>
            </div>
        <div class='grid grid-cols-3 gap-x-2'>
        @forelse ($decks as $deck)
            <div  x-on:click='open_item({{$deck->id}})' class="col-span-3 cursor-pointer md:col-span-1 flex rounded-md shadow-xs border-{{$deck->color}}-500 border-2">
            <div class="flex w-16 shrink-0 items-center justify-center bg-{{$deck->color}}-500 rounded-l-smd text-sm font-medium text-white">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/>
                  </svg>
            </div>
            <div class="flex flex-1 items-center justify-between truncate rounded-r-md  dark:bg-base-200 bg-white">
                <div class="flex-1 truncate px-4 py-2 text-sm">
                    <a class="font-medium text-gray-900 dark:text-white">{{ $deck->name }}</a>
                    <p class="text-gray-500">{{ $deck->cards->count() }} {{ __('cards') }}</p>
                </div>
            </div>
        </div>
        @empty
            No decks
        @endforelse
    </div>
        
    </div>

    <dialog id="my_modal_1" class="modal" wire:ignore>
  <div class="modal-box">
    <h3 class="text-lg font-bold">{{ __('Edit deck') }}</h3>
    
    <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Name')}}</label>
    <div class="mt-2">
        <input wire:model='name' type="text" name="title" id="name"  class="input input-primary w-full max-w">
    </div>
    <label for="color" class="block text-sm font-medium leading-6 dark:text-white text-gray-900 mt-2">{{__('Color')}}</label>

         <div x-data="{colorChosen : $wire.entangle('color')}">
                        <fieldset>
                          <div class="mt-4 flex items-center gap-2">
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-red-500" x-on:click="colorChosen = 'red'" :class="colorChosen == 'red' ? 'ring-2' : ''"> <input type="radio" name="color-choice" value=" red " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-red-50"> red </span> <span aria-hidden="true" class="h-8 w-8 bg-red-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-orange-500" x-on:click="colorChosen ='orange'" :class="colorChosen ==' orange' ? 'ring-2' : ''"> <input type="radio" name="color-choice" value=" orange " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-orange-50"> orange </span> <span aria-hidden="true" class="h-8 w-8 bg-orange-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-amber-500" x-on:click="colorChosen = 'amber'" :class="colorChosen == ' amber' ? 'ring-2' : ''"> <input type="radio" name="color-choice" value=" amber " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-amber-50"> amber </span> <span aria-hidden="true" class="h-8 w-8 bg-amber-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-yellow-500" x-on:click="colorChosen = 'yellow'" :class="colorChosen == 'yellow' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" yellow " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-yellow-50"> yellow </span> <span aria-hidden="true" class="h-8 w-8 bg-yellow-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-lime-500" x-on:click="colorChosen = 'lime'" :class="colorChosen == 'lime' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" lime " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-lime-50"> lime </span> <span aria-hidden="true" class="h-8 w-8 bg-lime-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-green-500" x-on:click="colorChosen = 'green'" :class="colorChosen == 'green' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" green " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-green-50"> green </span> <span aria-hidden="true" class="h-8 w-8 bg-green-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-emerald-500" x-on:click="colorChosen = 'emerald '" :class="colorChosen == 'emerald '? 'ring-2': ''"> <input type="radio" name="color-choice" value=" emerald " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-emerald-50"> emerald </span> <span aria-hidden="true" class="h-8 w-8 bg-emerald-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-teal-500" x-on:click="colorChosen = 'teal'" :class="colorChosen == 'teal' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" teal " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-teal-50"> teal </span> <span aria-hidden="true" class="h-8 w-8 bg-teal-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-cyan-500" x-on:click="colorChosen = 'cyan'" :class="colorChosen == 'cyan' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" cyan " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-cyan-50"> cyan </span> <span aria-hidden="true" class="h-8 w-8 bg-cyan-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-sky-500" x-on:click="colorChosen = 'sky'" :class="colorChosen == 'sky' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" sky " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-sky-50"> sky </span> <span aria-hidden="true" class="h-8 w-8 bg-sky-500 rounded-full border border-black/10"></span> </label>
                           </div>
                          <div class="mt-4 flex items-center gap-2">
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-blue-500" x-on:click="colorChosen = 'blue'" :class="colorChosen == 'blue' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" blue " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-blue-50"> blue </span> <span aria-hidden="true" class="h-8 w-8 bg-blue-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-indigo-500" x-on:click="colorChosen = 'indigo'" :class="colorChosen == 'indigo' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" indigo " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-indigo-50"> indigo </span> <span aria-hidden="true" class="h-8 w-8 bg-indigo-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-violet-500" x-on:click="colorChosen = 'violet'" :class="colorChosen == 'violet' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" violet " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-violet-50"> violet </span> <span aria-hidden="true" class="h-8 w-8 bg-violet-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-purple-500" x-on:click="colorChosen = 'purple'" :class="colorChosen == 'purple' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" purple " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-purple-50"> purple </span> <span aria-hidden="true" class="h-8 w-8 bg-purple-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-fuchsia-500" x-on:click="colorChosen = 'fuchsia'" :class="colorChosen == 'fuchsia' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" fuchsia " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-fuchsia-50"> fuchsia </span> <span aria-hidden="true" class="h-8 w-8 bg-fuchsia-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-pink-500" x-on:click="colorChosen = 'pink'" :class="colorChosen == 'pink' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" pink " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-pink-50"> pink </span> <span aria-hidden="true" class="h-8 w-8 bg-pink-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-rose-500" x-on:click="colorChosen = 'rose'" :class="colorChosen == 'rose' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" rose " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-rose-50"> rose </span> <span aria-hidden="true" class="h-8 w-8 bg-rose-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-slate-500" x-on:click="colorChosen = 'slate'" :class="colorChosen == 'slate' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" slate " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-slate-50"> slate </span> <span aria-hidden="true" class="h-8 w-8 bg-slate-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-gray-500" x-on:click="colorChosen = 'gray'" :class="colorChosen == 'gray' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" gray " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-gray-50"> gray </span> <span aria-hidden="true" class="h-8 w-8 bg-gray-500 rounded-full border border-black/10"></span> </label>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-zinc-500" x-on:click="colorChosen = 'zinc'" :class="colorChosen == 'zinc' ? 'ring-2': ''"> <input type="radio" name="color-choice" value=" zinc " class="sr-only" aria-labelledby="color-choice-4-label"> <span id="color-choice-4-label" class="sr-only bg-zinc-50"> zinc </span> <span aria-hidden="true" class="h-8 w-8 bg-zinc-500 rounded-full border border-black/10"></span> </label>
                          </div>
                        </fieldset>
                        <x-input-error for="color" class="mt-2" />
                      </div>

    <div class="modal-action">
      <form method="dialog">
        <button @click='close()' class="btn">{{ __('Close') }}</button>
        <template x-if='new_deck'>
            <button wire:click='create()' class="btn btn-primary">{{ __('Save') }}</button>
        </template>
        <template x-if='!new_deck'>
            <button wire:click='edit()' class="btn btn-primary">{{ __('Save') }}</button>
        </template>
        <template x-if='!new_deck'>
            <button wire:click='delete()' class="btn btn-error">{{ __('Remove') }}</button>
        </template>

      </form>
    </div>
  </div>
</dialog>

</div>