<?php

namespace SmirlTech\LivewireModals\Providers;

use SmirlTech\LivewireModals\Components\Modals;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireModalsProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'livewire-modals');

        $this->publishes(
            [__DIR__ . '/../../resources/views' => resource_path('views/vendor/livewire-modals')],
            ['livewire-modals', 'livewire-modals:views']
        );

        Livewire::component('modals', Modals::class);
    }
}
