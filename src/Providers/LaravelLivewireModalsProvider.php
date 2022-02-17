<?php

namespace Bastinald\LaravelLivewireModals\Providers;

use Bastinald\LaravelLivewireModals\Components\Modals;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LaravelLivewireModalsProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerViews();
        $this->registerLivewireComponents();
        $this->registerDirectives();

        $this->publishes(
            [__DIR__ . '/../../resources/views' => resource_path('views/vendor/laravel-livewire-modals')],
            ['laravel-livewire-modals', 'laravel-livewire-modals:views']
        );
        $this->publishes([
                __DIR__ . '/../resources/js' => public_path('vendor/laravel-livewire-modals'),
         ], 'laravel-livewire-modals:script');


    }
    
    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'laravel-livewire-modals');
    }
    
    private function registerLivewireComponents(): void
    {
         Livewire::component('modals', Modals::class);
    }

    private function registerDirectives()
    {
        Blade::directive('laravelLivewireModalScript', function () {
            return '<script src="' . asset("/vendor/laravel-livewire-modal/modals.js") . '"></script>';
        });
    }

    private function registerPublishables(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/livewire-modal-twitter'),
            ], 'livewire-modal-twitter:views');

            $this->publishes([
                __DIR__ . '/../resources/js' => resource_path('js/vendor/livewire-modal-twitter'),
            ], 'livewire-modal-twitter:script');

            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/livewire-modal-twitter'),
            ], 'livewire-modal-twitter:public');
        }
    }

    

}
