                <li class="pl-1 pr-1">
                  <div class="relative shadow-xs dark:ring-0  rounded-xl bg-white dark:bg-base-100">
                    <div class="grid sm:grid-cols-12  sm:space-y-0 ">
                      <div class="py-6 sm:col-span-4 sm:flex sm:justify-center sm:items-center sm:mx-3 px-3 sm:px-0">
                        <div class="sm:min-h-full sm:min-w-full flex justify-center sm:items-center sm:pr-2 sm:border-r-2 dark:border-neutral-500">
                          <p class="gap-3 align-middle /*flex */flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->front!!}</p>
                        </div>
                      </div>
                      <div class=" sm:hidden px-6">
                        <div class=" inset-0 flex items-center" aria-hidden="true">
                          <div class="w-full border-t border-gray-300"></div>
                        </div>
                      </div>
                      <div class="py-6 sm:col-span-7 flex justify-center sm:items-center sm:pl-3 px-3 sm:px-0">
                        <p class="gap-3 align-middle /*flex*/ flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->back!!}</p>
                      </div>
                      <div class=" md:col-span-1 md:flex md:justify-center md:items-center  hidden">
                        <div class="grid grid-rows-1 grid-flow-col  min-h-full min-w-full">
                          <div class="  min-h-12 text-primary transition duration-300 dark:hover:text-indigo-800 hover:cursor-pointer hover:text-indigo-400 hover:bg-primary dark:hover:bg-indigo-500 dark:bg-indigo-900 bg-indigo-100 rounded-br-lg sm:rounded-tr-lg sm:rounded-bl-none rounded-bl-lg">
                            <a wire:navigate class="flex justify-center items-center min-h-full min-w-full" href="{{route('cards.edit', [$post->id, $card->id])}}">
                              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                                <path d="M200-200h57l391-391-57-57-391 391v57Zm-40 80q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm600-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                              </svg>
                            </a>
                          </div>
                          <!--<div class=" sm:flex sm:justify-center sm:items-center text-error hover:cursor-pointer hover:text-red-800 hover:bg-error rounded-br-lg"><--<span class="text-xs text-gray-500 uppercase">{{__('Term')}}</span>-><svg fill="currentColor"
																			xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg></div>-->
                        </div>
                      </div>
                      <div class='md:hidden block absolute top-1 right-1' >
                        <a class='btn btn-ghost' wire:navigate href="{{route('cards.edit', [$post->id, $card->id])}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                                <path d="M200-200h57l391-391-57-57-391 391v57Zm-40 80q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm600-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                              </svg>
                            </a>
                      </div>
                    </div>
                    @if($user)
                    <livewire:deck-card-selector :card="$card" :user="$user" lazy/>
                    @endif
                  </div>
                </li>
                