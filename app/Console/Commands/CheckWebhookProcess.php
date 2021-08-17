<?php

namespace App\Console\Commands;

use App\WebhookProcess;
use Illuminate\Console\Command;

class CheckWebhookProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:webhooks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check waiting webhook processes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $processes = WebhookProcess::where('error', 0)
            ->orderBy('created_at')
            ->limit((60 / 3) * 5 - 1)
            ->get();

        foreach ($processes as $key => $process) {
            dispatch(new \App\Jobs\WebhookProcess($process->scope, $process->type, $process->bigcommerce_id, $process))
                ->delay($key * 3);
        }

        return 0;

    }
}
