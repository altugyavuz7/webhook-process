<?php

namespace App\Http\Controllers;

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
            try {
                $process                 = new \App\WebhookProcess();
                $process->type           = $type;
                $process->scope          = $scope;
                $process->bigcommerce_id = $productID;
                $process->save();
            } catch (\Exception $exception) {

            }
        } else {
            dispatch(new WebhookProcess($scope, $type, $productID));
        }

        return response('OK');

    }

}
