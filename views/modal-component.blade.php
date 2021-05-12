<div id="laravel-livewire-modals" class="modal fade" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __($title) }}</h5>
                <button type="button" class="btn-close" wire:click="$emit('hideModal')"></button>
            </div>
            <div class="modal-body">
                @if($component)
                    @livewire($component, $data)
                @endif
            </div>
        </div>
    </div>
</div>
