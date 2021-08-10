<div id="laravel-livewire-modals" class="modal fade" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false" wire:ignore.self>
    @if($alias)
        @livewire($alias, $params)
    @endif
</div>
