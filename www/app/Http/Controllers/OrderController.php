<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderDestroyRequest;

class OrderController extends Controller
{
    private $model_service;

    public function __construct(OrderService $order_service)
    {
        $this->model_service = $order_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $index = $this->model_service->getByFilters($request->all(), false);

        return parent::response($index);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\OrderStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStoreRequest $request)
    {
        $store = $this->model_service->store($request);

        return parent::response($store);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\OrderDestroyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderDestroyRequest $request)
    {
        $destroy = $this->model_service->destroy($request);

        return parent::response($destroy);
    }
}
