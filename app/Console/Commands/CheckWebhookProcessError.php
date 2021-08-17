<?php

namespace App\Console\Commands;

use App\Jobs\WebhookProcess;
use App\WebhookProcessError;
use Illuminate\Console\Command;

class CheckWebhookProcessError extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:webhook-errors {--ids=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check webhook error records and recreate webhook process again.';

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
        $ids = $this->option('ids');

        $errors = WebhookProcessError::orderBy('created_at')->with('process');

        if (!empty($ids)) {
            $errors = $errors->whereIn('id', $ids);
        }

        $errors = $errors->get();

        foreach ($errors as $key => $error) {
            if ($error->process) {
                dispatch(new WebhookProcess($error->process->scope, $error->process->type, $error->process->bigcommerce_id, $error->process))
                    ->delay($key * 3);
            }
        }

        return 0;
    }
}
