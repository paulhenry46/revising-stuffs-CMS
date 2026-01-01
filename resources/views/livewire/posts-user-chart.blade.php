<div class="mt-6 mb-3 card bg-base-100">
    <div class="card-body">
      <div class='flex justify-between'>
        <h2 class="card-title">{{__('Downloads by months')}}</h2>
      </div>
        <x-mary-chart wire:model="HistoryChart" class="min-h-60 md:min-h-72" />
    </div>
</div>
