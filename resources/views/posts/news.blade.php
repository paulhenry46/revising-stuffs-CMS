<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('New posts'),NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/>  
    </x-slot>
    <div class="py-12">
        @livewire('new-posts')
        </div>
    </div>
<x-nav-bottom :active=0/>
</x-app-layout>
