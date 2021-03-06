<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateWebhookProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $scope;
    private $type;
    private $id;

    /**
     * Create a new job instance.
     *
     * @param $scope
     * @param $type
     * @param $id
     */
    public function __construct($scope, $type, $id)
    {
        $this->scope = $scope;
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \App\WebhookProcess::updateOrCreate([
            'bigcommerce_id' => $this->id,
            'scope'          => $this->scope
        ], [
            'type' => $this->type
        ]);
    }
}
