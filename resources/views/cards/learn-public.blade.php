<x-app-layout>
  <div class='py-6 max-w-7xl mx-auto sm:px-6 lg:px-8'>
<div class=" rounded-lg navbar bg-base-100 mt-8">
          <div class="flex-1">
            <a class="btn btn-ghost text-xl">{{__('Cards')}}</a>
          </div>
          <div class="flex-none"> @guest <button onclick="csv_guest.showModal()" class=" ml-4 btn btn-ghost ">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M480-337q-8 0-15-2.5t-13-8.5L308-492q-12-12-11.5-28t11.5-28q12-12 28.5-12.5T365-549l75 75v-286q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v286l75-75q12-12 28.5-11.5T652-548q11 12 11.5 28T652-492L508-348q-6 6-13 8.5t-15 2.5ZM240-160q-33 0-56.5-23.5T160-240v-80q0-17 11.5-28.5T200-360q17 0 28.5 11.5T240-320v80h480v-80q0-17 11.5-28.5T760-360q17 0 28.5 11.5T800-320v80q0 33-23.5 56.5T720-160H240Z" />
              </svg>
              <!-- {{__('CSV Export')}}-->
            </button> @endguest @auth <a href="{{route('post.public.cards.export', [$post->slug, $post->id])}}" class=" ml-4 btn btn-ghost ">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M480-337q-8 0-15-2.5t-13-8.5L308-492q-12-12-11.5-28t11.5-28q12-12 28.5-12.5T365-549l75 75v-286q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v286l75-75q12-12 28.5-11.5T652-548q11 12 11.5 28T652-492L508-348q-6 6-13 8.5t-15 2.5ZM240-160q-33 0-56.5-23.5T160-240v-80q0-17 11.5-28.5T200-360q17 0 28.5 11.5T240-320v80h480v-80q0-17 11.5-28.5T760-360q17 0 28.5 11.5T800-320v80q0 33-23.5 56.5T720-160H240Z" />
              </svg>
              <!--{{__('CSV Export')}}-->
            </a> @endauth <a href="{{route('post.public.cards.show', [$post->slug, $post->id])}}" class="btn btn-ghost ml-4">{{__('See')}}</a>
            <a href="{{route('post.public.cards.quiz', [$post->slug, $post->id])}}" class=" btn btn-ghost ml-4">{{__('Quiz Mode')}}</a>
          </div> @guest <dialog id="csv_guest" class="modal">
            <div class="modal-box">
              <h3 class="font-bold text-lg">{{('Warning')}}</h3>
              <p class="py-4">{{('You must be logged to export cards to CSV.')}}</p>
              <div class="modal-action">
                <form method="dialog">
                  <!-- if there is a button in form, it will close the modal -->
                  <button class="btn">{{ __('Close') }}</button>
                </form>
              </div>
            </div>
          </dialog> @endguest
        </div>

  <div x-data="cards_component">

    <div class="w-full px-4 mt-8">
            <div class="relative w-full">
                <div class="flex w-full items-center gap-2">
                  <template x-for='i in number_bar'>
                    <div class='w-full'>
                      <template x-if="i < current_cycle">
                        <div class="duration-500 transition h-5 flex-1 rounded-md" :class='gold_state ? "bg-emerald-700" : "bg-success"'></div>
                      </template>
                      <template x-if="i > current_cycle">
                        <div class="h-5 flex-1 rounded-md bg-[#4B5563] "></div>
                      </template>
                      <template x-if="i == current_cycle">
                        <div class="relative h-5 flex-1 rounded-md  bg-[#4B5563]">
                          <div class="transition-all duration-500 absolute h-5 flex-1 rounded-md bg-success w-full z-10" :style="{ width: (cards_learned_in_current_cycle.length/number_cards_in_cycle * 100) + '%' }">
                          </div>
                          <div :style="{ left: (cards_learned_in_current_cycle.length/number_cards_in_cycle * 95) + '%' }" class="absolute top-1/2 -translate-x-1/2 -translate-y-1/2 transition-all duration-500 z-10">
                            <div x-text='cards_remain' class="flex h-10 w-10 items-center justify-center rounded-full bg-[#22C5A7] text-xl font-bold text-white shadow-lg">
                            </div>
                          </div>
                        </div>
                      </template>
                    </div>
                  </template>
                </div>
                <div class="absolute right-0 top-1/2 -translate-y-1/2">
                    <div x-text='cards.length' class="flex h-8 w-8 items-center justify-center rounded-full bg-[#16181b] text-xl font-bold text-white shadow-lg">
                        
                    </div>
                </div>
              </div>
            </div>
    <div>
      <div>
        <div>
        <div x-show='state != "end_cycle"' class=" bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
          <x-info-message />
          <div class=" gap-4">
            <div>
              <div class="stack w-full h-64 sm:h-80 mb-3">
                <label class="swap grid w-full h-full rounded dark:bg-base-100 bg-white place-content-center text-dark dark:text-white text-black">
                  <input type="checkbox" />
                  <div x-html="current_card.back" class="place-content-center align-middle /*flex*/ flex-col swap-on text-center"></div>
                  <div x-html="current_card.front" class="place-content-center align-middle /*flex*/ flex-col swap-off text-center"></div>
                </label>
                <div  class="bg-accent text-accent-content grid h-20 w-full place-content-center rounded">2</div>
                <div class="bg-secondary text-secondary-content grid h-20 w-full place-content-center rounded">
              </div>
            </div>
            <div class=" grid grid-cols-2 space-x-3">
              <div>
                <button @click='bad_answer(); $nextTick(() => { MathJax.typeset() });' class="btn btn-warning w-full">{{__('Can\'t remind')}}</button>
              </div>
              <div>
                <button @click='good_answer(); $nextTick(() => { MathJax.typeset() });' class="btn btn-success w-full">{{__('OK')}}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
        <div x-show="state == 'end_cycle'" class=" w-full h-full rounded place-content-center text-dark dark:text-white text-black" >
                  <div role="alert" class="alert alert-success mt-3">
  <svg class='text-success-content w-10' xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"  fill="currentColor"><path d="M108-255q-12-12-11.5-28.5T108-311l211-214q23-23 57-23t57 23l103 104 208-206h-64q-17 0-28.5-11.5T640-667q0-17 11.5-28.5T680-707h160q17 0 28.5 11.5T880-667v160q0 17-11.5 28.5T840-467q-17 0-28.5-11.5T800-507v-64L593-364q-23 23-57 23t-57-23L376-467 164-255q-11 11-28 11t-28-11Z"/></svg>
  <span x-show='cards.length >0' class='text-2xl'>{{__('Keep up the good work, you\'ll get there! Start the next cycle whenever you want.')}}</span>
  <span x-show='cards.length == 0' class='text-2xl'>{{__('Congratulations, you\'ve learned all the cards! It wasn\'t that complicated after all. You deserve a break!')}}</span>
</div>
                  <div class='grid grid-cols-2 mt-5'>
                    <div class='col-span-2 md:col-span-1'>
                      <h3 class='flex items-center text-xl mt-1 decoration-4 underline decoration-success font-medium'>
                        <svg class='text-success' xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M400-240q-33 0-56.5-23.5T320-320v-50q-57-39-88.5-100T200-600q0-117 81.5-198.5T480-880q117 0 198.5 81.5T760-600q0 69-31.5 129.5T640-370v50q0 33-23.5 56.5T560-240H400Zm0 160q-17 0-28.5-11.5T360-120v-40h240v40q0 17-11.5 28.5T560-80H400Z"/></svg>
                        {{ __('Learned cards') }}
                      </h3>

                      <div class="inline-block min-w-full py-2 align-middle ">
                        <ul class="space-y-4">
                          <template x-for="card in cards_learned_in_current_cycle">
                          <li class="pl-1 pr-1">
                            <div class="relative p-6 shadow-sm dark:ring-0  rounded-xl bg-white dark:bg-base-100">
                              <div class="grid sm:grid-cols-12 sm:divide-x-2 space-y-6 sm:space-y-0 dark:divide-neutral-500">
                                <div class="sm:col-span-4 sm:flex sm:justify-center sm:items-center sm:mx-3">
                                  <p class="gap-3 align-middle /*flex */flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2" x-html="card.front"></p>
                                </div>
                                <div class=" sm:hidden px-6">
                                  <div class=" inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-300"></div>
                                  </div>
                                </div>
                                <div class="sm:col-span-8 sm:flex sm:justify-center sm:items-center sm:pl-3">
                                  <p class="gap-3 align-middle /*flex*/ flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2" x-html="card.back"></p>
                                </div>
                              </div>
                            </div>
                          </li>
                        </template>
                        </ul>
                      </div>
                    </div>
                    <div class='col-span-2 md:col-span-1' x-show='cards_revised_in_current_cycle.length > 0'>
                      <h3 class='flex items-center text-xl mt-1 decoration-4 underline decoration-warning font-medium'>
                        <svg  class='text-warning' xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M480-120q-126 0-223-76.5T131-392q-4-15 6-27.5t27-14.5q16-2 29 6t18 24q24 90 99 147t170 57q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h70q17 0 28.5 11.5T360-600q0 17-11.5 28.5T320-560H160q-17 0-28.5-11.5T120-600v-160q0-17 11.5-28.5T160-800q17 0 28.5 11.5T200-760v54q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm40-376 100 100q11 11 11 28t-11 28q-11 11-28 11t-28-11L452-452q-6-6-9-13.5t-3-15.5v-159q0-17 11.5-28.5T480-680q17 0 28.5 11.5T520-640v144Z"/></svg>
                        {{ __('Revised cards') }}
                      </h3>
                      <div class="inline-block min-w-full py-2 align-middle">
                        <ul class="space-y-4">
                          <template x-for="card in cards_revised_in_current_cycle">
                          <li class="pl-1 pr-1">
                            <div class="relative p-6 shadow-sm dark:ring-0  rounded-xl bg-white dark:bg-base-100">
                              <div class="grid sm:grid-cols-12 sm:divide-x-2 space-y-6 sm:space-y-0 dark:divide-neutral-500">
                                <div class="sm:col-span-4 sm:flex sm:justify-center sm:items-center sm:mx-3">
                                  <p class="gap-3 align-middle /*flex */flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2" x-html="card.front"></p>
                                </div>
                                <div class=" sm:hidden px-6">
                                  <div class=" inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-300"></div>
                                  </div>
                                </div>
                                <div class="sm:col-span-8 sm:flex sm:justify-center sm:items-center sm:pl-3">
                                  <p class="gap-3 align-middle /*flex*/ flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2" x-html="card.back"></p>
                                </div>
                              </div>
                            </div>
                          </li>
                        </template>
                        </ul>
                      </div>
                    </div>
                  </div>
                   <button x-show='(state == "end_cycle") && (cards.length > 0)' @click='new_cycle(); $nextTick(() => { MathJax.typeset() });' class="mt-3 btn btn-primary w-full">{{__('Continue')}}</button>
                </div>
    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.data('cards_component', () => ({
            cards : [],//All cards
            current_card : null,
            cards_ok : [],//Cards learnt
            cards_bad : [],//Cards not learnt in this cycle. Normally, it is 0 when a cycle is finished
            cards_learned_in_current_cycle : [],//History of cards learned in a cycle
            cards_revised_in_current_cycle : [],//History of cards revised (from cards_ok) in a cycle
            indic_progress : 0,//1 to 7//For controlling position of
            number_bar : 0,//1 to 5
            current_cycle : 1,
            number_cards_in_cycle : 7,
            user_number_cards_in_cycle : 7,
            state : 'not_init',
            proba_old_card: 0.3,
            cards_remain : 0,
            gold_state : false,
            init(){
              this.cards = {!!$cards!!};
              this.number_bar = Math.floor(this.cards.length/this.number_cards_in_cycle)+1;
              if(this.number_bar > 5){
                this.number_bar = 5;
              }
              if(this.cards.length < this.number_cards_in_cycle){
                this.number_cards_in_cycle = this.cards.length;
              }

              this.current_card = this.cards[0];
              this.indic_progress = 1;
              //this.cards_learned_in_current_cycle.push(this.current_card);
              this.state  = 'new_card';
              this.$nextTick(() => { MathJax.typeset(); });

              this.cards_remain = 0;
              
            },
            good_answer(){
              if(this.state == 'new_card'){
                this.cards = this.cards.filter(card => card !== this.current_card);
                this.cards_ok.push(this.current_card);
                this.indic_progress++;
                this.cards_remain++;
                this.cards_learned_in_current_cycle.push(this.current_card);
              }else if(this.state == 'old_card'){
                this.cards_ok = this.cards_ok.filter(card => card !== this.current_card);
                this.cards_revised_in_current_cycle.push(this.current_card);
                this.gold_state = true;
                this.$nextTick(() => {
                  setTimeout(() => {this.gold_state = false}, "730");
                  });

              }else if(this.state == 'bad_card'){
                this.cards_bad = this.cards_bad.filter(card => card !== this.current_card);
                this.cards_ok.push(this.current_card);
                this.cards_learned_in_current_cycle.push(this.current_card);
                this.cards_remain++;
              }
              this.next_action();
            },
            bad_answer(){
              if(this.state == 'new_card'){
                this.cards = this.cards.filter(card => card !== this.current_card);
                this.cards_bad.push(this.current_card);
              }else if(this.state == 'old_card'){
                this.cards.push(this.current_card);
              }
              this.next_action();
            },
            next_action(){
              if(this.cards_learned_in_current_cycle.length == this.number_cards_in_cycle || this.cards.length == 0){
                this.state = 'end_cycle';
              }else{
                this.next_card();
              }
            },
            next_card(){
              if(this.cards_learned_in_current_cycle.length + this.cards_bad.length == this.number_cards_in_cycle){
                this.current_card = this.cards_bad[Math.floor(Math.random() * this.cards_bad.length)];
                this.state = 'bad_card';
              }else{
                if(Math.random() > this.proba_old_card ||  this.current_cycle<=1){
                  this.current_card = this.cards[Math.floor(Math.random() * this.cards.length)]
                  this.state  = 'new_card';
                }else{
                  this.current_card = this.cards_ok[Math.floor(Math.random() * this.cards_ok.length)]
                  this.state  = 'old_card';
                }
              }
            },
            new_cycle(){
              this.cards_learned_in_current_cycle = [];
              this.cards_revised_in_current_cycle = [];
              this.current_cycle++;
              if(this.cards.length < this.number_cards_in_cycle){
                this.number_cards_in_cycle = this.cards.length;
              }
              this.next_card();
            }
        }))
        MathJax.typeset();
         });
</script>
</div>
</x-app-layout>
