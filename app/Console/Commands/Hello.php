<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('say:hello {--name= : The name to greet} {--surname= : The surname to greet}')]
#[Description('Greets a person.')]
class Hello extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name');
        $surname = $this->option('surname');
        $this->info('Hello ' . $name . ' ' . $surname);
    }
}
