                <li class="pl-1 pr-1">
                  <div class="relative p-6 shadow-xs dark:ring-0  rounded-xl bg-white dark:bg-base-100">
                    <div class="grid sm:grid-cols-12 sm:divide-x-2 space-y-6 sm:space-y-0 dark:divide-neutral-500">
                      <div class="sm:col-span-4 sm:flex sm:justify-center sm:items-center sm:mx-3">
                        <!--<span class="text-xs text-gray-500 uppercase">{{__('Term')}}</span>-->
                        <p class="gap-3 align-middle /*flex */flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->front!!}</p>
                      </div>
                      <div class=" sm:hidden px-6">
                        <div class=" inset-0 flex items-center" aria-hidden="true">
                          <div class="w-full border-t border-gray-300"></div>
                        </div>
                      </div>
                      <div class="sm:col-span-8 sm:flex sm:justify-center sm:items-center sm:pl-3">
                        <!--<span class="text-xs text-gray-500 uppercase">{{__('Definition')}}</span>-->
                        <p class="gap-3 align-middle /*flex*/ flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->back!!}</p>
                      </div>
                    </div>
                    @if($user)
                    <livewire:deck-card-selector :card="$card" :user="$user" lazy/>
                    @endif
                  </div>
                </li>