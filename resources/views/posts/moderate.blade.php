<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts which need to be validated'),NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/> 
        
    </x-slot>
                <livewire:posts-table />
</x-app-layout>