<?php

namespace App\Http\Controllers;

use App\Jobs\CreateWebhookProcess;
use App\Jobs\WebhookProcess;
use App\Product;
use App\Services\BigcommerceApiService as BigcommerceClient;
use App\Sku;
use App\SyncData;
use Bigcommerce\Api\Client as Bigcommerce;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function listen(Request $request)
    {
        $data      = $request->get('data');
        $productID = $data['id'];
        $type      = $data['type'];
        $scope     = $request->get('scope');

        if (config('webhook.active')) {
            if (config('webhook.create_with_job')) {
                dispatch(new CreateWebhookProcess($scope, $type, $productID));
            } else {
                \App\WebhookProcess::updateOrCreate([
                    'bigcommerce_id' => $productID,
                    'scope'          => $scope
                ], [
                    'type' => $type
                ]);
            }
        } else {
            dispatch(new WebhookProcess($scope, $type, $productID));
        }

        return response('OK');

    }

}
