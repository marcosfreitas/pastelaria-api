<?php

namespace App\Http\Controllers;

use App\Models\Pastel;
use Illuminate\Http\Request;
use App\Services\PastelService;
use App\Http\Requests\PastelDestroyRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class PastelController extends Controller
{
    private $model_service;

    public function __construct(PastelService $pastel_service)
    {
        $this->model_service = $pastel_service;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = $this->model_service->store($request);

        return parent::response($store);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($pastel, Request $request)
    {

        $show = $this->model_service->getPastelImage($pastel, $request);

        $response = parent::response($show);

        if (!empty($show['error']) || $show['code'] === 'data_not_found') {
            return $response;
        }

        return response($show['data']['image'], JsonResponse::HTTP_OK)->header('Content-Type', 'image/'. $show['data']['extension']);

    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\PastelDestroyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(PastelDestroyRequest $request)
    {
        $destroy = $this->model_service->destroy($request);

        return parent::response($destroy);
    }
}
