<?php

namespace Bastinald\LaravelLivewireModals\Components;

use Livewire\Component;
use Livewire\LivewireManager;

class ModalComponent extends Component
{
    public $title;
    public $component;
    public $data = [];

    protected $listeners = ['showModal', 'resetModal'];

    public function showModal($component, ...$data)
    {
        $this->title = app((new LivewireManager)->getClass($component))->title;
        $this->component = $component;
        $this->data = $data;
        $this->emit('showBootstrapModal');
    }

    public function resetModal()
    {
        $this->reset();
    }

    public function render()
    {
        return view('laravel-livewire-modals::modal-component');
    }
}
