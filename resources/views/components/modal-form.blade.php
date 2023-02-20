@props(['title' => null, 'footer' => null])
<div class="modal-dialog">
    <form wire:submit.prevent="submit">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{$slot}}
                <div class="modal-footer">
                    @isset($footer)
                        {{$footer}}
                    @else
                        <x-form::button-secondary type="button" data-bs-dismiss="modal">Fermer
                        </x-form::button-secondary>
                        <x-form::button-primary type="submit">Enregistrer</x-form::button-primary>
                    @endisset
                </div>
            </div>

        </div>
    </form>
</div>


