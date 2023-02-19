<?php

namespace Livewire\Commands;

class CpCommand extends CopyCommand
{
    protected $signature = 'livewire:cp {name} {new-name} {--inline} {--force} {--test}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
