# Laravel Livewire Modals

Dynamic Laravel Livewire Bootstrap 5 modals.

## Requirements

- Bootstrap 5

## Installation

Require the package:

```console
composer require bastinald/laravel-livewire-modals
```

Add the `livewire:modals` component to your app layout view:

```html
<livewire:scripts/>
<livewire:modals/>
<script src="{{ asset('js/app.js') }}"></script>
```

Require `../../vendor/bastinald/laravel-livewire-modals/js/modals` in your app javascript file:

```javascript
require('@popperjs/core');
require('bootstrap');
require('../../vendor/bastinald/laravel-livewire-modals/js/modals');
```

## Usage

Specify a `title` for the modal in your Livewire component (the body content for the modal comes from the `render` method):

```php
class ProfileUpdate extends Component
{
    public $title = 'Update Profile';

    public function render()
    {
        return view('profile-update');
    }
}
```

Show the modal via `$emit('showModal', 'component-alias')`:

```html
<button type="button" 
    wire:click="$emit('showModal', 'profile-update')">
    {{ __('Update Profile') }}
</button>
```

You can also pass parameters to the component `mount` method:

```html
<button type="button" 
    wire:click="$emit('showModal', 'user-update', {{ $user->id }})">
    {{ __('Update User: ' . $user->name) }}
</button>
```

Hiding the currently open model can be done via `$emit('hideModal')`:

```html
<button type="button" wire:click="$emit('hideModal')">
    {{ __('Close') }}
</button>
```
