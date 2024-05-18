<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
                    <div class="sm:flex sm:items-center">
                      <div class="sm:flex-auto">
                        <h1 class="text-2xl font-semibold leading-6 text-gray-900 dark:text-white">{{__('Cards attached to')}} {{$post->title}}</h1>
                      </div>
                     <div class="flex items-stretch justify-end mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <a wire:navigate href="{{route('post.public.cards.learn', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('Learn mode')}}</a>
                        <a wire:navigate href="{{route('post.public.cards.show', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('View')}}</a>
                      </div>
                    </div>
                </div>

                <div class="bg-base-200 dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message/>
<div x-data="flashCards"
    x-init="start()"
    class="grid grid-cols-3 gap-4">
  <div class="col-span-3">
    <div @click="returnCard()" class="stack w-full h-64 sm:h-80">
    <div x-bind:data-state="cardState" class=" duration-500 data-[state=success]:bg-success dark:data-[state=success]:bg-success data-[state=fail]:bg-warning dark:data-[state=fail]:bg-warning transition ease-in-out swap grid w-full h-full rounded dark:bg-base-100 bg-white text-dark dark:text-white place-content-center">
      
      <div x-transition x-show="!recto" x-html="cards[activeCard].back" id="back" class="place-content-center align-middle flex flex-col  text-center"></div>
      <div x-transition x-show="recto" x-html="cards[activeCard].front" id="front" class="place-content-center align-middle flex flex-col  text-center"></div>
</div>
<div class=" w-full h-full rounded bg-primary  place-content-center"></div>
<div class=" w-full h-full rounded bg-success place-content-center"></div>

</div>
</div>
<div class="col-span-3">
  <div class="grid grid-cols-12 gap-4 mt-2">
  <div class="col-span-2">
  <button class="btn btn-primary w-full"  
          x-show="playButtons"
          x-bind:disabled="disabledPreviousButton"
          @click="previous()"  >
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="48">
      <path d="M289-200q-13 0-21.5-8.5T259-230q0-13 8.5-21.5T289-260h280q70 0 120.5-46.5T740-422q0-69-50.5-115.5T569-584H274l93 93q9 9 9 21t-9 21q-9 9-21 9t-21-9L181-593q-5-5-7-10t-2-11q0-6 2-11t7-10l144-144q9-9 21-9t21 9q9 9 9 21t-9 21l-93 93h294q95 0 163.5 64T800-422q0 94-68.5 158T568-200H289Z"/>
    </svg>
  </button>
    </div>
    <div class="col-span-5">
      <button x-show="playButtons" @click="fail()" class="btn btn-warning w-full">{{__('Fail')}}</button>
    </div>
    <div class="col-span-5">
      <button x-show="playButtons" @click="success()" class="btn btn-success w-full">{{__('OK')}}</button>
    </div>
    <div class="col-span-6">
      <button class="btn btn-success w-full" x-show="stats" @click="nextCycle()" type="button">{{__('Retry misses')}}</button>
    </div>
    <div class="col-span-6">
      <button class="btn btn-info w-full" x-show="stats" @click="reinitialize()" type="button">{{__('Retry all')}}</button>
    </div>
  </div>
</div>
<div class="col-span-3">
<div class="w-full flex items-center justify-center px-4">
      <template x-for="(card, index) in cards" :key="card.id">
        <button
          class="flex-1 w-4 h-2 mt-4 mx-1 mb-0 rounded-full overflow-hidden hover:shadow-lg"
          :class="{ 
              'bg-warning': card.state === 'fail',
              'bg-success': card.state === 'success',
              'bg-neutral': card.state === 'pending'
          }"
        ></button>
      </template>
      

    </div>
                            <div id="successRate" class="mb-2">
                            <div x-show="stats" class="card-body p-3">
    <dchartiv class="grid grid-cols-4 gap-4 mt-2">
      <div class="col-span-2 text-center">
        <div class="flex justify-center h-56 chart">
          <canvas id="myChart" class=" h-56 chart-canvas" style="display: block; box-sizing: border-box; height: 224px; width: 224px;" width="224" height="224"></canvas>
        </div>
        
      </div>
      <div class=" col-span-2">
        <div class="table-responsive">
          <table class="table align-items-center mb-0">
            <tbody>
<tr>
                <td>
                  <div class="d-flex px-2 py-0">
                    
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm">{{__('Known')}}</h6>
                    </div>
                  </div>
                </td>
                <td class="align-middle text-center text-sm">
                  <span x-text="statsKnown" class="badge badge-info"></span>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="d-flex px-2 py-0">
                    
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm">{{__('Working')}}</h6>
                    </div>
                  </div>
                </td>
                <td class="align-middle text-center text-sm">
                  <span x-text="statsFail" class="badge badge-warning"></span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div x-show="congrats" class="card-body p-3">
    <div class="grid grid-cols-1 gap-4 mt-2">
      <button class="btn btn-info" @click="reinitialize" >{{__('Retry all')}}</button>
    </div>
                            </div>
</div>
                </div>

                            </div>
                        </div>
                    </div>
<dialog id="masterclass" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">{{__('Congrats')}}</h3>
    <p class="py-4">{{__('You know all the cards !')}}</p>
    <div class="modal-action">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn">{{__('Close')}}</button>
      </form>
    </div>
  </div>
</dialog>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('flashCards', () => ({
            //-----------The cards-------------
            cards: {!!$cards!!},
            savedCards: [],
            nextCards: [],
            activeCard: 0,
            //-----------UI-------------
            recto : false,
            cardState : 'default',
            ////---------Quiz---------////
            playButtons: true,
            nextButton: false,
            disabledPreviousButton: true,
            ////---------Stats + Next Cycle of Quiz---------////
            stats: false,
            statsChart: null,
            statsKnown: 0,
            statsFail: 0,
            //////-Congrats
            congrats: false,
            chart() {
                //Chart Settings   
                Chart.overrides.doughnut.plugins.legend.display = false;
                const ctx = document.getElementById('myChart');
                const data = {
                    labels: [
                        '{{__('Known')}}',
                        '{{__('Working ')}}'
                    ],
                    datasets: [{
                        label: 'Répartition',
                        data: [this.statsKnown, this.statsFail],
                        backgroundColor: [
                            'rgb(54, 211, 153)',
                            'rgb(255, 190, 0)'
                        ],
                        hoverOffset: 4
                    }]
                };
                //Initialise Chart
                this.statsChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                });
            },

            start() {
              this.cards = this.shuffle(this.cards);
                for (const card of this.cards) {
                    card.state = 'pending'
                }
                this.savedCards = this.cards;
            },

            next() {
                if (this.activeCard === ((this.cards.length) - 1)) {
                  this.sendResult();
                    //Disable buttons
                    this.nextButton = true;
                    this.playButtons = false;
                    this.disabledPreviousButton = true;

                    //Prepares values for this cycle
                    statsFail = 0;
                    this.cards.forEach(function(item, index) {
                        if (item.state == 'fail') {
                            statsFail++;
                        }
                    });
                    this.statsFail = statsFail;
                    this.statsKnown = this.savedCards.length - this.statsFail;
                    //Check if all cards are known
                    if (this.statsFail == 0) {
                        this.congrats = true;
                        masterclass.showModal();
                    } else {
                        //Show chart and buttons
                        this.chart();
                        this.stats = true;
                    }

                } else {
                    this.activeCard++
                    this.disabledPreviousButton = false;
                }
                this.$nextTick(() => { 
                  setTimeout(() => {this.resetDataState();}, "130");
 
                });
            },

            previous() {
                if (this.activeCard === 0) {
                    this.activeCard = 0;
                } else {
                    this.activeCard = (this.activeCard - 1);
                }
                this.cards[this.activeCard].state = 'pending';
                if (this.activeCard === 0) {
                    this.disabledPreviousButton = true;
                }
            },

            success() {
              this.cardState = 'success';
                this.cards[this.activeCard].state = 'success'
                this.next()
            },
            
            fail() {
              this.cardState = 'fail';
                this.cards[this.activeCard].state = 'fail'
                this.next()
            },

            nextCycle() {
                nextCards = [];
                this.cards.forEach(function(item, index) {
                    if (item.state == 'fail') {
                        nextCards.push(item)
                    }
                });
                this.cards = nextCards
                this.activeCard = 0

                this.stats = false,
                    this.playButtons = true
                this.statsChart.destroy();
                this.statsFail = 0;
                this.statsKnown = 0;

                for (const card of this.cards) {
                    card.state = 'pending'
                }
            },

            reinitialize() {
                this.cards = this.savedCards
                this.activeCard = 0
                for (const card of this.cards) {
                    card.state = 'pending'
                }
                this.stats = false,
                    this.congrats = false,
                    this.playButtons = true
                if (this.statsChart !== null) {
                    this.statsChart.destroy();
                }
                this.statsFail = 0;
                this.statsKnown = 0;
            },
            
            returnCard(){
              this.recto = !this.recto;
            },

            resetDataState(){
              this.cardState = 'default'
            },

            shuffle(arr) {
              for (var i = arr.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var temp = arr[i];
                arr[i] = arr[j];
                arr[j] = temp;
              }
              return arr;
            },
            @auth
            firstTry:true,
            @endauth
            @guest
            firstTry:true,
            @endguest

            sendResult(){
              if(this.firstTry == true){
              success = 0;
              for (const card of this.cards) {
                if(card.state == 'success'){
                  success++;
                }
                }
                percent = (success/this.cards.length)*100;

              body = JSON.stringify({
                postId: '{{$post->id}}',
                _token: document.head.querySelector('meta[name=csrf-token]').content,
                percent: percent,
              }),

              
                fetch('{{route('step.add')}}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: body
                });
                this.firstTry = false;
              }
            }
        }))
    });
  </script>
</x-app-layout>
