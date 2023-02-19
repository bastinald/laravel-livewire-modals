<?php

namespace SmirlTech\LivewireModals\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Modals extends Component
{
    public mixed $alias;
    public array $params = [];

    protected $listeners = ['showModal', 'resetModal'];

    public function render(): Factory|View|Application
    {
        return view('modals::modals');
    }

    public function showModal($alias, ...$params): void
    {
        $this->alias = $alias;
        $this->params = $params;

        $this->emit('showBootstrapModal');
    }

    public function resetModal(): void
    {
        $this->reset();
    }
}
