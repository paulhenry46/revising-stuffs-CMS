<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__('Cards'),NULL));
     @endphp   
     <x-breadcrumb :items=$breadcrumb/> 
        
    </x-slot>

    

      <livewire:cards-table :post=$post lazy/>
    

</x-app-layout>
