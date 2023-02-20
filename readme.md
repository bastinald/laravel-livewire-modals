# Laravel Livewire Modals

This package allows you to dynamically show your Laravel Livewire components inside Bootstrap modals.

## Documentation

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    - [Modal Views](#modal-views)
    - [Showing Modals](#showing-modals)
    - [Mount Parameters](#mount-parameters)
    - [Hiding Modals](#hiding-modals)
    - [Emitting Events](#emitting-events)
- [Publishing Assets](#publishing-assets)
    - [Custom View](#custom-view)

## Requirements

Bootstrap 5 must be installed via webpack first and the `bootstrap` and `popper.js` packages must be required in your
app javascript file.

```javascript
require('@popperjs/core');
require('bootstrap');
```

## Installation

Require the package:

```console
composer require smirltech/livewire-modals
```

Add the `livewire:modals` component to your app layout view:

```html

<livewire:modals/>
<livewire:scripts/>
<script src="{{ asset('js/app.js') }}"></script>
<x-modals::scripts/>
```

## Usage

### Modal Views

Make a Livewire component you want to show as a modal. The view for this component must use the Bootstrap `modal-dialog`
container:

```html

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>Modal body text goes here.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
    </div>
</div>
```

### Showing Modals

Show a modal by emitting the `showModal` event with the component alias:

```html

<button type="button" wire:click="$emit('showModal', 'auth.profile-update')">
    {{ __('Update Profile') }}
</button>
```

### Mount Parameters

Pass parameters to the component `mount` method after the alias:

```html

<button type="button" wire:click="$emit('showModal', 'users.update', '{{ $user->id }}')">
    {{ __('Update User #' . $user->id) }}
</button>
```

The component `mount` method for the example above would look like this:

```php
namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class Update extends Component
{
    public $user;
    
    public function mount(User $user)
    {
        $this->user = $user;
    }
    
    public function render()
    {
        return view('users.update');
    }
}
```

### Hiding Modals

Hide the currently open modal by emitting the `hideModal` event:

```html

<button type="button" wire:click="$emit('hideModal')">
    {{ __('Close') }}
</button>
```

Or by using the Bootstrap `data-bs-dismiss` attribute:

```html

<button type="button" data-bs-dismiss="modal">
    {{ __('Close') }}
</button>
```

### Emitting Events

You can emit events inside your views:

```html

<button type="button" wire:click="$emit('hideModal')">
    {{ __('Close') }}
</button>
```

Or inside your components, just like any normal Livewire event:

```php
public function save()
{
    $this->validate();

    // save the record

    $this->emit('hideModal');
}
```

## Publishing Assets

### Custom View

Use your own modals view by publishing the package view:

```console
php artisan vendor:publish --tag=livewire-modals:views
```

Now edit the view file inside `resources/views/vendor/livewire-modals`. The package will use this view to render the
component.
