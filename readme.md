# Laravel Livewire Modals

[![Latest Version on Packagist](https://img.shields.io/packagist/v/smirltech/livewire-modals.svg?style=flat-square)](https://packagist.org/packages/smirltech/livewire-modals)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/smirltech/livewire-modals/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/smirltech/livewire-modals/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/smirltech/livewire-modals/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/smirltech/livewire-modals/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/smirltech/livewire-modals.svg?style=flat-square)](https://packagist.org/packages/smirltech/livewire-modals)

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

Install Bootstrap 5 and Popper.js 2 in your app. See
the [Bootstrap 5 docs](https://getbootstrap.com/docs/5.0/getting-started/introduction/#js) for more information.

  ```console
  npm install bootstrap @popperjs/core
  ```

Require `bootstrap` and `popper.js` packages in your
app javascript file. Then import the `Modal` class from `bootstrap` and add it to the `window` object.

Using `mix`

  ```javascript
  require('@popperjs/core');
require('bootstrap');

import {Modal} from 'bootstrap';

window.Modal = Modal;
  ```

Using `vite`

  ```javascript
  import('@popperjs/core');
import('bootstrap');

import {Modal} from 'bootstrap';

window.Modal = Modal;
  ```

## Installation

Require the package:

```console
composer require smirltech/livewire-modals
```

Add `livewire:modals` and `x-modals::scripts` components to your layout:

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

Alternatively, you can use the `x-modals::base` or `x-modals::form` component:

```html

<x-modals::base>
    <x-slot:title>Modal title</x-slot:title>
    <p>Modal body text goes here.</p>
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
    </x-slot:footer>
</x-modals::base>
```

### Showing Modals

Show a modal by emitting the `showModal` event with the component alias:

```html

<button type="button" wire:click="$emit('showModal', 'auth.profile-update')">
    {{ __('Update Profile') }}
</button>
```

Outside of Livewire components, you can use the `Livewire.emit` method:

```html

<script>
    Livewire.emit('showModal', 'auth.profile-update');
</script>
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
