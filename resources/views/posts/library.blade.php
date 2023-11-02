<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('All posts'),NULL)
);
     @endphp   
     <x-breadcrumb :items=$breadcrumb/>    
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <livewire:library />
            </div>
        </div>
    </div>
<x-nav-bottom :active=1/>
</x-app-layout>