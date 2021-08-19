<?php

namespace App\Http\Controllers;

use App\WebhookProcess;
use Illuminate\Http\Request;

class WebhookProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $records = WebhookProcess::orderBy('created_at')->paginate(20);

        return view("webhook-process.list", compact('records'));
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
     * @param  \App\WebhookProcess  $webhookProcess
     * @return \Illuminate\Http\Response
     */
    public function show(WebhookProcess $webhookProcess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WebhookProcess  $webhookProcess
     * @return \Illuminate\Http\Response
     */
    public function edit(WebhookProcess $webhookProcess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WebhookProcess  $webhookProcess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebhookProcess $webhookProcess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WebhookProcess  $webhookProcess
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy(WebhookProcess $webhookProcess)
    {
        try {
            $webhookProcess->delete();
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());

            return response('OK');
        }

        session()->flash('success', 'Record has been successfully deleted.');

        return response('OK');
    }
}
