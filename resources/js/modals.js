import {Modal} from 'bootstrap';

let modalsElement = document.getElementById('laravel-livewire-modals');

modalsElement.addEventListener('hidden.bs.modal', () => {
    Livewire.emit('resetModal');
});

Livewire.on('showBootstrapModal', () => {
    let modal = new Modal(modalsElement);

    modal.show();
});

Livewire.on('hideModal', () => {
    let modal = Modal.getInstance(modalsElement);

    modal.hide();
});
