<x-app-layout>
  <div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="rounded-lg navbar bg-base-100">
          
          <div class="navbar-center">
            <a  class="btn btn-ghost text-xl">{{$deck->name}}</a>
          </div>
          
        </div>
        <livewire:step-history :$deck lazy />
        <div class="rounded-lg navbar bg-base-100 mt-8">
          <div class="flex-1">
            <a class="btn btn-ghost text-xl">{{__('Cards')}}</a>
          </div>
          <div class="flex-none">   <a href="{{route('decks.export', [$deck->slug, $deck->id])}}" class=" ml-4 btn btn-ghost ">
              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M480-337q-8 0-15-2.5t-13-8.5L308-492q-12-12-11.5-28t11.5-28q12-12 28.5-12.5T365-549l75 75v-286q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v286l75-75q12-12 28.5-11.5T652-548q11 12 11.5 28T652-492L508-348q-6 6-13 8.5t-15 2.5ZM240-160q-33 0-56.5-23.5T160-240v-80q0-17 11.5-28.5T200-360q17 0 28.5 11.5T240-320v80h480v-80q0-17 11.5-28.5T760-360q17 0 28.5 11.5T800-320v80q0 33-23.5 56.5T720-160H240Z" />
              </svg>
              <!--{{__('CSV Export')}}-->
            </a> <a href="{{route('decks.learn', [$deck->slug, $deck->id])}}" class="btn btn-ghost ml-4">{{__('Learn mode')}}</a>
            <a href="{{route('decks.quiz', [$deck->slug, $deck->id])}}" class=" btn btn-ghost ml-4">{{__('Quiz Mode')}}</a>
          </div> 
        </div>
        <div class="mt-4 flow-root">
          <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
              <ul class="space-y-4"> 
                 @php
                  $user = Auth::user();
                @endphp
                    @foreach ($cards as $card) 
                    <x-flashcard-card :card="$card" :user="$user"/> 
                    @endforeach 
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('livewire:navigated', () => {
      MathJax.typeset();
    })
  </script>
</x-app-layout>