<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\Services\ClientService;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Requests\ClientDestroyRequest;

class ClientController extends Controller
{
    private $model_service;

    public function __construct(ClientService $client_service)
    {
        $this->model_service = $client_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $index = $this->model_service->getByFilters($request->all());

        return parent::response($index);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ClientStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientStoreRequest $request)
    {
        $store = $this->model_service->store($request);

        return parent::response($store);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ClientUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUpdateRequest $request)
    {
        $store = $this->model_service->update($request);

        return parent::response($store);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ClientUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientDestroyRequest $request)
    {
        $destroy = $this->model_service->destroy($request);

        return parent::response($destroy);
    }
}
