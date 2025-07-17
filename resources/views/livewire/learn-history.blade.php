
<div class="mt-6 mb-3 card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title">{{__('Progression')}}</h2>
        @if($steps->isEmpty())
        <div class="sm:col-span-4 rounded-box mt-6 dark:border-gray-500 border-base-300 dark:text-gray-500 text-base-content/30 flex h-72 flex-col items-center justify-center gap-6 border-2 border-dashed p-10 text-center text-balance">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-269 314-169q-11 7-23 6t-21-8q-9-7-14-17.5t-2-23.5l44-189-147-127q-10-9-12.5-20.5T140-571q4-11 12-18t22-9l194-17 75-178q5-12 15.5-18t21.5-6q11 0 21.5 6t15.5 18l75 178 194 17q14 2 22 9t12 18q4 11 1.5 22.5T809-528L662-401l44 189q3 13-2 23.5T690-171q-9 7-21 8t-23-6L480-269Z"/></svg>
        <div>{{__('There is no data for now. Try to learn cards of a post.')}}</div> 
    </div>
        @else
        <x-mary-chart wire:model="HistoryChart" class="min-h-60 md:min-h-72" />

       <h2 class="card-title py-3" >{{__('Upcoming posts to review in the coming days')}}</h2>
        <div class="overflow-x-auto">
  <table class="table table-zebra">
    <!-- head -->
    <thead>
      <tr>
        <th>{{__('Post')}}</th>
        <th>{{__('Mastery')}}</th>
        <th>{{__('Revise')}}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($steps as $step)
      <tr>
        <th>
            <div class="flex items-center gap-3">
            <div>
              <div class="font-bold">{{ $step->post->title }}</div>
              <div class="text-sm opacity-50">{{ $step->post->course->name }}</div>
            </div>
          </div>
        </th>
        <td>{{ $step->mastery }}</td>
        <td>@if($step->numberBeforeNextRevision > 0)
        <button  class="disabled btn btn-ghost">
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-640h560v-80H200v80Zm0 0v-80 80Zm0 560q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-40q0-17 11.5-28.5T280-880q17 0 28.5 11.5T320-840v40h320v-40q0-17 11.5-28.5T680-880q17 0 28.5 11.5T720-840v40h40q33 0 56.5 23.5T840-720v187q0 17-11.5 28.5T800-493q-17 0-28.5-11.5T760-533v-27H200v400h232q17 0 28.5 11.5T472-120q0 17-11.5 28.5T432-80H200Zm520 40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Zm20-208v-92q0-8-6-14t-14-6q-8 0-14 6t-6 14v91q0 8 3 15.5t9 13.5l61 61q6 6 14 6t14-6q6-6 6-14t-6-14l-61-61Z"/></svg>
    
    {{__('Next revision in')}} {{$step->numberBeforeNextRevision}} {{__('days')}}.</button>
    @else
    <a href="{{route('post.public.cards.quiz', [$step->post->slug, $step->post->id])}}" class="btn btn-ghost">
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-640h560v-80H200v80Zm0 0v-80 80Zm0 560q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-40q0-17 11.5-28.5T280-880q17 0 28.5 11.5T320-840v40h320v-40q0-17 11.5-28.5T680-880q17 0 28.5 11.5T720-840v40h40q33 0 56.5 23.5T840-720v187q0 17-11.5 28.5T800-493q-17 0-28.5-11.5T760-533v-27H200v400h232q17 0 28.5 11.5T472-120q0 17-11.5 28.5T432-80H200Zm520 40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Zm20-208v-92q0-8-6-14t-14-6q-8 0-14 6t-6 14v91q0 8 3 15.5t9 13.5l61 61q6 6 14 6t14-6q6-6 6-14t-6-14l-61-61Z"/></svg>
    
    {{__('Revise now')}}</a>
    @endif</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif
</div>
        
    </div>
</div>