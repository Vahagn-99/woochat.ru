<?php

namespace App\Console\Commands;

use App\Services\AmoCRM\Core\Facades\Amo;
use App\Services\AmoCRM\Dirty\Filters\Email;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test any service';

    /**
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function handle(): void
    {
        $doubles = Amo::privateApi()->contacts(new Email('widget.dev@dicitech.com'));

        if (! isset($doubles['response']['contacts'])) {
            $this->error('No contacts found');
            $this->info(json_encode($doubles['response'], JSON_PRETTY_PRINT));

            return;
        }

        $last_double = Arr::last($doubles['response']['contacts']);
        dd($last_double);
    }
}
