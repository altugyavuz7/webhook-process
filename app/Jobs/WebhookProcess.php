<?php

namespace App\Jobs;

use App\Notifications\WebhookErrorNotification;
use App\Product;
use App\SyncData;
use App\WebhookProcessError;
use Bigcommerce\Api\Client as Bigcommerce;
use Elasticsearch\ClientBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\WebhookProcess as WebhookProcessModel;
use Illuminate\Support\Facades\Notification;

class WebhookProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $scope;
    private $type;
    private $productId;

    public $timeout = 0;
    /**
     * @var WebhookProcessModel|null
     */
    private $process;

    /**
     * Create a new job instance.
     *
     * @param $scope
     * @param $type
     * @param $productId
     * @param WebhookProcessModel|null $process
     */
    public function __construct($scope, $type, $productId, ?WebhookProcessModel $process = null)
    {
        $this->scope     = $scope;
        $this->type      = $type;
        $this->productId = $productId;
        $this->process   = $process;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->connection();
        $productID = $this->productId;

        if ($this->scope == 'store/product/deleted') {
            $this->deleteProduct($productID);
        } else {
            if ($this->type == 'product') {
                $this->updateProduct($productID);
            }
        }
    }

    /**
     * Bigcommerce Client Connection Configuration
     */
    public function connection()
    {
        Bigcommerce::configure(array(
            'client_id'  => config('bigcommerce.client_id'),
            'auth_token' => config('bigcommerce.auth_token'),
            'store_hash' => config('bigcommerce.store_hash')
        ));
    }

    /**
     * @param $productID
     */
    public function updateProduct($productID)
    {
        try {
            //112
            $service = new \App\Services\Providers\Bigcommerce();
            $product = $service->getProductDetails((int)$productID);
            if (!empty($product)) {
                if (SyncData::findBySource('product', $productID) != null) {
                    $contentData               = $product[0]->getCreateFields();
                    $contentData->date_created = $product[0]->date_created;

                    $sync          = SyncData::findBySource('product', $productID);
                    $sync->content = json_encode($contentData);
                    $sync->save();
                } else {
                    $contentData               = $product[0]->getCreateFields();
                    $contentData->date_created = $product[0]->date_created;

                    $sync              = new SyncData();
                    $sync->source_id   = $productID;
                    $sync->source_name = 'product';
                    $sync->content     = json_encode($contentData);
                    $sync->save();
                }
                if ($product[0]->is_visible == true && $product[0]->availability !== 'disabled') {
                    $cats = '';
                    foreach ($product[0]->categories as $category) {
                        $cats .= $category . ",";
                    }
                    $service = new \App\Services\Providers\Bigcommerce();
                    $brand   = $service->getProductBrandFromProvider($product[0]->brand_id);

                    $save = Product::where('ProductID', $product[0]->id)->with('syncData')->first();
                    if (!$save) {
                        $save = new Product();
                    }
                    $save->ProductID    = $product[0]->id;
                    $save->ProductCode  = $product[0]->sku;
                    $save->ProductName  = $product[0]->name;
                    $save->CategoryTree = $cats;
                    $save->brand        = $brand->data->name ?? null;

                    if ($save->save()) {
                        SaveCustomFieldDataJob::withChain([
                            new SetProductSelectedOptions($save),
                            new CorrectSizeOptionsJob($save),
                            new IndexOneProduct($save)
                        ])->dispatch($save);
                    }
                } else {
                    $this->deleteProduct($productID);
                }
            }

            if (null !== $this->process) {
                $this->process->delete();
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            if (null !== $this->process) {
                $this->createProcessErrorRecord($exception);
            }

        }

    }

    /**
     * @param $productID
     */
    public function deleteProduct($productID)
    {

        try {
            $product = Product::where('ProductID', (int)$productID)->first();
            if ($product != null) {
                if ($product->delete()) {
                    $productSyncData = SyncData::findBySource('product', $productID);
                    $productSyncData->delete();
                    $client           = ClientBuilder::create()->build();
                    $searchProperties = [
                        'id'    => $productID,
                        'index' => config('elastic_index.index_name'),
                        'type'  => '_doc',
                    ];
                    $client->delete($searchProperties);
                }
            }

            if (null !== $this->process) {
                $this->process->delete();
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            if (null !== $this->process) {
                $this->createProcessErrorRecord($exception);
            }
        }
    }

    /**
     * @param \Exception $exception
     */
    private function createProcessErrorRecord(\Exception $exception)
    {
        $this->process->error = true;
        $this->process->save();

        $errorRecord             = new WebhookProcessError();
        $errorRecord->process_id = $this->process->id;
        $errorRecord->line       = $exception->getLine();
        $errorRecord->file       = $exception->getFile();
        $errorRecord->message    = $exception->getMessage();
        $errorRecord->code       = $exception->getCode();
        $errorRecord->save();

        if (config('webhook.slack_notification')) {
            $slackUrl = config('webhook.slack_hook_url');
            if ($slackUrl) {
                Notification::route('slack', $slackUrl)
                    ->notify(new WebhookErrorNotification($exception, $this->process));
            }

        }
    }
}
