<?php

namespace Livewire\Testing\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\Constraints\SeeInOrder;
use Livewire\Component;
use Livewire\Features\SupportRootElementTracking;
use PHPUnit\Framework\Assert as PHPUnit;

trait MakesAssertions
{
    public function assertSet($name, $value, $strict = false)
    {
        $actual = $this->get($name);

        if (! is_string($value) && is_callable($value)) {
            PHPUnit::assertTrue($value($actual));
        } else {
            $strict ? PHPUnit::assertSame($value, $actual) : PHPUnit::assertEquals($value, $actual);
        }

        return $this;
    }

    public function assertNotSet($name, $value, $strict = false)
    {
        $actual = $this->get($name);

        $strict ? PHPUnit::assertNotSame($value, $actual) : PHPUnit::assertNotEquals($value, $actual);

        return $this;
    }

    public function assertCount($name, $value)
    {
        PHPUnit::assertCount($value, $this->get($name));

        return $this;
    }

    public function assertPayloadSet($name, $value)
    {
        if (is_callable($value)) {
            PHPUnit::assertTrue($value(data_get($this->payload['serverMemo']['data'], $name)));
        } else {
            PHPUnit::assertEquals($value, data_get($this->payload['serverMemo']['data'], $name));
        }

        return $this;
    }

    public function assertPayloadNotSet($name, $value)
    {
        if (is_callable($value)) {
            PHPUnit::assertFalse($value(data_get($this->payload['serverMemo']['data'], $name)));
        } else {
            PHPUnit::assertNotEquals($value, data_get($this->payload['serverMemo']['data'], $name));
        }

        return $this;
    }

    public function assertSee($values, $escape = true)
    {
        foreach (Arr::wrap($values) as $value) {
            PHPUnit::assertStringContainsString(
                $escape ? e($value): $value,
                $this->stripOutInitialData($this->lastRenderedDom)
            );
        }

        return $this;
    }

    public function assertDontSee($values, $escape = true)
    {
        foreach (Arr::wrap($values) as $value) {
            PHPUnit::assertStringNotContainsString(
                $escape ? e($value): $value,
                $this->stripOutInitialData($this->lastRenderedDom)
            );
        }

        return $this;
    }

    public function assertSeeHtml($values)
    {
        foreach (Arr::wrap($values) as $value) {
            PHPUnit::assertStringContainsString(
                $value,
                $this->stripOutInitialData($this->lastRenderedDom)
            );
        }

        return $this;
    }

    public function assertDontSeeHtml($values)
    {
        foreach (Arr::wrap($values) as $value) {
            PHPUnit::assertStringNotContainsString(
                $value,
                $this->stripOutInitialData($this->lastRenderedDom)
            );
        }

        return $this;
    }

    public function assertSeeHtmlInOrder(array $values)
    {
        PHPUnit::assertThat(
            $values,
            new SeeInOrder($this->stripOutInitialData($this->lastRenderedDom))
        );

        return $this;
    }

    public function assertSeeInOrder(array $values)
    {
        PHPUnit::assertThat(
            array_map('e', ($values)),
            new SeeInOrder($this->stripOutInitialData($this->lastRenderedDom))
        );

        return $this;
    }

    protected function stripOutInitialData($subject)
    {
        $subject = preg_replace('/((?:[\n\s+]+)?wire:initial-data=\".+}"\n?|(?:[\n\s+]+)?wire:id=\"[^"]*"\n?)/m', '', $subject);

        return SupportRootElementTracking::stripOutEndingMarker($subject);
    }

    public function assertEmitted($value, ...$params)
    {
        $result = $this->testEmitted($value, $params);

        PHPUnit::assertTrue($result['test'], "Failed asserting that an event [{$value}] was fired{$result['assertionSuffix']}");

        return $this;
    }

    public function assertNotEmitted($value, ...$params)
    {
        $result = $this->testEmitted($value, $params);

        PHPUnit::assertFalse($result['test'], "Failed asserting that an event [{$value}] was not fired{$result['assertionSuffix']}");

        return $this;
    }

    public function assertEmittedTo($target, $value, ...$params)
    {
        $this->assertEmitted($value, ...$params);
        $result = $this->testEmittedTo($target, $value);

        PHPUnit::assertTrue($result, "Failed asserting that an event [{$value}] was fired to {$target}.");

        return $this;
    }

    public function assertEmittedUp($value, ...$params)
    {
        $this->assertEmitted($value, ...$params);
        $result = $this->testEmittedUp($value);

        PHPUnit::assertTrue($result, "Failed asserting that an event [{$value}] was fired up.");

        return $this;
    }

    protected function testEmitted($value, $params)
    {
        $assertionSuffix = '.';

        if (empty($params)) {
            $test = collect(data_get($this->payload, 'effects.emits'))->contains('event', '=', $value);
        } elseif (! is_string($params[0]) && is_callable($params[0])) {
            $event = collect(data_get($this->payload, 'effects.emits'))->first(function ($item) use ($value) {
                return $item['event'] === $value;
            });

            $test = $event && $params[0]($event['event'], $event['params']);
        } else {
            $test = (bool) collect(data_get($this->payload, 'effects.emits'))->first(function ($item) use ($value, $params) {
                return $item['event'] === $value
                    && $item['params'] === $params;
            });

            $encodedParams = json_encode($params);
            $assertionSuffix = " with parameters: {$encodedParams}";
        }

        return [
            'test'            => $test,
            'assertionSuffix' => $assertionSuffix,
        ];
    }

    protected function testEmittedTo($target, $value)
    {
        $target = is_subclass_of($target, Component::class)
            ? $target::getName()
            : $target;

        return (bool) collect(data_get($this->payload, 'effects.emits'))->first(function ($item) use ($target, $value) {
            return $item['event'] === $value
                && $item['to'] === $target;
        });

    }

    protected function testEmittedUp($value)
    {
        return (bool) collect(data_get($this->payload, 'effects.emits'))->first(function ($item) use ($value) {
            return $item['event'] === $value
                && $item['ancestorsOnly'] === true;
        });
    }

    public function assertDispatchedBrowserEvent($name, $data = null)
    {
        $assertionSuffix = '.';

        if (is_null($data)) {
            $test = collect(data_get($this->payload, 'effects.dispatches'))->contains('event', '=', $name);
        } elseif (is_callable($data)) {
            $event = collect(data_get($this->payload, 'effects.dispatches'))->first(function ($item) use ($name) {
                return $item['event'] === $name;
            });

            $test = $event && $data($event['event'], $event['data']);
        } else {
            $test = (bool) collect(data_get($this->payload, 'effects.dispatches'))->first(function ($item) use ($name, $data) {
                return $item['event'] === $name
                    && $item['data'] === $data;
            });
            $encodedData = json_encode($data);
            $assertionSuffix = " with parameters: {$encodedData}";
        }

        PHPUnit::assertTrue($test, "Failed asserting that an event [{$name}] was fired{$assertionSuffix}");

        return $this;
    }

    public function assertNotDispatchedBrowserEvent($name)
    {
        if (! array_key_exists('dispatches', $this->payload['effects'])){
            $test = false;
        } else {
            $test = collect($this->payload['effects']['dispatches'])->contains('event', '=', $name);
        }

        PHPUnit::assertFalse($test, "Failed asserting that an event [{$name}] was not fired");

        return $this;
    }


    public function assertHasErrors($keys = [])
    {
        $errors = $this->lastErrorBag;

        PHPUnit::assertTrue($errors->isNotEmpty(), 'Component has no errors.');

        $keys = (array) $keys;

        foreach ($keys as $key => $value) {
            if (is_int($key)) {
                PHPUnit::assertTrue($errors->has($value), "Component missing error: $value");
            } else {
                $failed = optional($this->lastValidator)->failed() ?: [];
                $rules = array_keys(Arr::get($failed, $key, []));

                foreach ((array) $value as $rule) {
                    if (Str::contains($rule, ':')){
                        $rule = Str::before($rule, ':');
                    }

                    PHPUnit::assertContains(Str::studly($rule), $rules, "Component has no [{$rule}] errors for [{$key}] attribute.");
                }
            }
        }

        return $this;
    }

    public function assertHasNoErrors($keys = [])
    {
        $errors = $this->lastErrorBag;

        if (empty($keys)) {
            PHPUnit::assertTrue($errors->isEmpty(), 'Component has errors: "'.implode('", "', $errors->keys()).'"');

            return $this;
        }

        $keys = (array) $keys;

        foreach ($keys as $key => $value) {
            if (is_int($key)) {
                PHPUnit::assertFalse($errors->has($value), "Component has error: $value");
            } else {
                $failed = optional($this->lastValidator)->failed() ?: [];
                $rules = array_keys(Arr::get($failed, $key, []));

                foreach ((array) $value as $rule) {
                    if (Str::contains($rule, ':')){
                        $rule = Str::before($rule, ':');
                    }

                    PHPUnit::assertNotContains(Str::studly($rule), $rules, "Component has [{$rule}] errors for [{$key}] attribute.");
                }
            }
        }

        return $this;
    }

    public function assertRedirect($uri = null)
    {
        PHPUnit::assertArrayHasKey(
            'redirect',
            $this->payload['effects'],
            'Component did not perform a redirect.'
        );

        if (! is_null($uri)) {
            PHPUnit::assertSame(url($uri), url($this->payload['effects']['redirect']));
        }

        return $this;
    }

    public function assertNoRedirect()
    {
        PHPUnit::assertTrue(! isset($this->payload['effects']['redirect']));

        return $this;
    }

    public function assertViewIs($name)
    {
        PHPUnit::assertEquals($name, $this->lastRenderedView->getName());

        return $this;
    }

    public function assertViewHas($key, $value = null)
    {
        if (is_null($value)) {
            PHPUnit::assertArrayHasKey($key, $this->lastRenderedView->gatherData());
        } elseif ($value instanceof \Closure) {
            PHPUnit::assertTrue($value($this->lastRenderedView->gatherData()[$key]));
        } elseif ($value instanceof Model) {
            PHPUnit::assertTrue($value->is($this->lastRenderedView->gatherData()[$key]));
        } else {
            PHPUnit::assertEquals($value, $this->lastRenderedView->gatherData()[$key]);
        }

        return $this;
    }

    public function assertFileDownloaded($filename = null, $content = null, $contentType = null)
    {
        $downloadEffect = data_get($this->lastResponse, 'original.effects.download');

        if ($filename) {
            PHPUnit::assertEquals(
                $filename,
                data_get($downloadEffect, 'name')
            );
        } else {
            PHPUnit::assertNotNull($downloadEffect);
        }

        if ($content) {
            $downloadedContent = data_get($this->lastResponse, 'original.effects.download.content');

            PHPUnit::assertEquals(
                $content,
                base64_decode($downloadedContent)
            );
        }

        if ($contentType) {
            PHPUnit::assertEquals(
                $contentType,
                data_get($this->lastResponse, 'original.effects.download.contentType')
            );
        }

        return $this;
    }
}
