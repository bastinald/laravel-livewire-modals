<?php

namespace Bastinald\LaravelLivewireModals\Providers;

use Bastinald\LaravelLivewireModals\Components\Modals;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LaravelLivewireModalsProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'laravel-livewire-modals');

        $this->publishes(
            [__DIR__ . '/../../resources/views' => resource_path('views/vendor/laravel-livewire-modals')],
            ['laravel-livewire-modals', 'laravel-livewire-modals:views']
        );

        Livewire::component('modals', Modals::class);
    }
}
