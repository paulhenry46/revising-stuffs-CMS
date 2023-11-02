<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Latest comments on your posts'),NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/> 
        
    </x-slot>
                <livewire:comments-table />
</x-app-layout>