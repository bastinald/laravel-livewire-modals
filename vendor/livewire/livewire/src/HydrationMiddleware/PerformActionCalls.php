<?php

namespace Livewire\HydrationMiddleware;

use Livewire\Livewire;
use function Livewire\str;

use Illuminate\Validation\ValidationException;
use Livewire\Exceptions\DirectlyCallingLifecycleHooksNotAllowedException;

class PerformActionCalls implements HydrationMiddleware
{
    public const PROTECTED_METHODS = [
        'mount',
        'hydrate*',
        'dehydrate*',
        'updating*',
        'updated*',
    ];

    public static function hydrate($unHydratedInstance, $request)
    {
        try {
            foreach ($request->updates as $update) {
                if ($update['type'] !== 'callMethod') continue;

                $id = $update['payload']['id'];
                $method = $update['payload']['method'];
                $params = $update['payload']['params'];

                throw_if(
                    str($method)->is(static::PROTECTED_METHODS),
                    new DirectlyCallingLifecycleHooksNotAllowedException($method, $unHydratedInstance->getName())
                );

                $unHydratedInstance->callMethod($method, $params, function ($returned) use ($unHydratedInstance,$method, $id) {
                    Livewire::dispatch('action.returned', $unHydratedInstance, $method, $returned, $id);
                });
            }
        } catch (ValidationException $e) {
            Livewire::dispatch('failed-validation', $e->validator, $unHydratedInstance);

            $unHydratedInstance->setErrorBag($e->validator->errors());
        }
    }

    public static function dehydrate($instance, $response)
    {
        //
    }
}
