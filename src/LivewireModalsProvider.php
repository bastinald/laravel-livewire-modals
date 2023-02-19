<?php

namespace SmirlTech\LivewireModals;

use Livewire\Livewire;
use SmirlTech\LivewireModals\Components\Modals;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LivewireModalsProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('modals')
            ->hasViews();
    }

    public function packageBooted(): void
    {
        Livewire::component('livewire-modals::modals', Modals::class);
    }
}
