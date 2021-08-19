<?php

namespace App\Http\Controllers;

use App\Jobs\WebhookProcess;
use App\WebhookProcessError;
use Illuminate\Http\Request;

class WebhookProcessErrorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $records = WebhookProcessError::orderBy('created_at')->paginate(20);

        return view("webhook-process.error", compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WebhookProcessError  $webhookProcessError
     * @return \Illuminate\Http\Response
     */
    public function show(WebhookProcessError $webhookProcessError)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WebhookProcessError  $webhookProcessError
     * @return \Illuminate\Http\Response
     */
    public function edit(WebhookProcessError $webhookProcessError)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WebhookProcessError  $webhookProcessError
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebhookProcessError $webhookProcessError)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WebhookProcessError  $webhookProcessError
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebhookProcessError $webhookProcessError)
    {
        try {
            $webhookProcessError->delete();
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());

            return response('OK');
        }

        session()->flash('success', 'Record has been successfully deleted.');

        return response('OK');
    }

    public function retryProcess(Request $request, $id)
    {
        $find = WebhookProcessError::with('process')->find($id);

        if ($find && $find->process) {
            $find->process->error = false;
            $find->process->save();

            dispatch(new WebhookProcess($find->process->scope, $find->process->type, $find->process->bigcommerce_id, $find->process));

            try {
                $find->delete();

                session()->flash('success', 'Process is in the queue right now!');
            } catch (\Exception $exception) {
                session()->flash('warning', 'Process is in the queue right now. But record delete process unsuccessful!');
            }
        }

        return response('OK');
    }
}
