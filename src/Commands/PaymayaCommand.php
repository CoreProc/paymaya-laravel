<?php

namespace Coreproc\PaymayaLaravel\Commands;

use Illuminate\Console\Command;

class PaymayaCommand extends Command
{
    public $signature = 'paymaya-laravel';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
