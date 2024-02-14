<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SlackTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esa:slack-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to test slack notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::channel('slack')->info(config('app.name') . ': Test Slack Notification.');
    }
}
