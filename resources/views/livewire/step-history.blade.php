<div class="mt-6 mb-3 card bg-base-100 dark:bg-base-200 @guest z-0 @endguest">
    <div class="card-body @guest opacity-25 @endguest">
        <h2 class="card-title">{{__('Progression')}}</h2>
        <x-mary-chart wire:model="HistoryChart" />

        <div class="flow-root">  
    <p class="float-left">
    @if($numberBeforeNextRevision > 0)
        <button  class="disabled btn btn-ghost">
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-640h560v-80H200v80Zm0 0v-80 80Zm0 560q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-40q0-17 11.5-28.5T280-880q17 0 28.5 11.5T320-840v40h320v-40q0-17 11.5-28.5T680-880q17 0 28.5 11.5T720-840v40h40q33 0 56.5 23.5T840-720v187q0 17-11.5 28.5T800-493q-17 0-28.5-11.5T760-533v-27H200v400h232q17 0 28.5 11.5T472-120q0 17-11.5 28.5T432-80H200Zm520 40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Zm20-208v-92q0-8-6-14t-14-6q-8 0-14 6t-6 14v91q0 8 3 15.5t9 13.5l61 61q6 6 14 6t14-6q6-6 6-14t-6-14l-61-61Z"/></svg>
    
    {{__('Next revision in')}} {{$numberBeforeNextRevision}} {{__('days')}}.</button>
    @else
    <a href="{{route('post.public.cards.quiz', [$post->slug, $post->id])}}" class="btn btn-ghost">
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-640h560v-80H200v80Zm0 0v-80 80Zm0 560q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-40q0-17 11.5-28.5T280-880q17 0 28.5 11.5T320-840v40h320v-40q0-17 11.5-28.5T680-880q17 0 28.5 11.5T720-840v40h40q33 0 56.5 23.5T840-720v187q0 17-11.5 28.5T800-493q-17 0-28.5-11.5T760-533v-27H200v400h232q17 0 28.5 11.5T472-120q0 17-11.5 28.5T432-80H200Zm520 40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Zm20-208v-92q0-8-6-14t-14-6q-8 0-14 6t-6 14v91q0 8 3 15.5t9 13.5l61 61q6 6 14 6t14-6q6-6 6-14t-6-14l-61-61Z"/></svg>
    
    {{__('Revise now')}}</a>
    @endif
</p> 
    <p class="float-right"><button class="btn dark:bg-base-100" onclick="my_modal_1.showModal()">{{__('Reinitialize your progress')}}</button></p>
</div>

        
    </div>
<dialog id="my_modal_1" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">{{__('Are you sure ?')}}</h3>
    <p class="py-4">{{__('You will lose your progress on this post. We\'ll have to start all over again.')}}</p>
    <div class="modal-action">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn">{{__('Close')}}</button> <button wire:click=reinitialize class="ml-2 btn btn-error ">{{__('Reinitialize')}}</button>
      </form>
    </div>
  </div>
</dialog>
@guest
<div class="absolute inset-0 flex justify-center items-center z-10">
      <p class="text-xl">{{__('To save your progress on this post, you must be connected.')}}</p>
    </div>
    @endguest
</div>