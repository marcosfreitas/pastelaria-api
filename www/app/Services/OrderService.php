<?php

namespace App\Services;

use App\Models\Order;
use App\Services\PastelService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;

class OrderService
{
    private $repository;
    private $client_service;
    private $pastel_service;

    public function __construct(Order $order, ClientService $client_service, PastelService $pastel_service)
    {
        $this->repository = $order;
        $this->client_service = $client_service;
        $this->pastel_service = $pastel_service;
    }

    /**
     * List resources filtering by received parameters
     *
     * @param array $params
     * @return array
     */
    public function getByFilters(array $params = [], $with_trashed_resources = true)
	{
		if (!empty($params) && empty($params['uuid'])) {
			return [
				'error' => 1,
				'code' => 'invalid_request_params',
				'description' => 'Filtros recebidos não suportados'
			];
		}

		$filters = [];

        if (!empty($params['uuid'])) {
			$filters[] = ['uuid', '=', $params['uuid']];
        }

        # check for existent customer
        $found_order = $this->repository->where($filters);

        if ($with_trashed_resources) {
            $found_order = $found_order->withTrashed()->get();
        } else {
            $found_order = $found_order->get();
        }

		if ($found_order->isEmpty()) {
			return [
				'error' => 1,
				'code' => 'model_not_found',
				'description' => 'Cadastro não encontrado'
			];
		}

		return [
			'error' => 0,
			'code' => 'model_found',
			'description' => 'Cadastro encontrado.',
			'data' => OrderResource::collection($found_order)
		];
    }

    /**
	 * Save a new resource
	 *
	 * @param $request
	 * @return array
	 */
	public function store($request)
	{
		try {

            # check for existent customer
            $found_client = $this->client_service->getByFilters(['uuid' => $request->client]);

            if (!empty($found_client['error'])) {
                return [
                    'error' => 1,
                    'code' => 'model_not_found',
                    'description' => 'Cliente não encontrado para o identificador utilizado'
                ];
            }

            $found_client = $found_client['data']->first();

            DB::beginTransaction();

            $created_order = $found_client->order()->create();

            if(isset($created_order->error) || !$created_order) {

                DB::rollBack();

                return  [
                    'error' => 1,
                    'code' => 'failed_creating_model',
                    'description' => 'Não foi criar o pedido'
                ];
            }

            $return_in_loop = [];

			foreach($request->pastels as $key => $pastel) {

                $found_pastel = $this->pastel_service->getByFilters(['uuid' => $pastel['uuid']]);

                if (!empty($found_pastel['error'])) {
                    DB::rollBack();

                    $return_in_loop =  [
                        'error' => 1,
                        'code' => 'failed_creating_model',
                        'description' => 'Não foi possível inserir os pasteis ao pedido'
                    ];

                    break;
                }

                $found_pastel = $found_pastel['data']->first();

                $attached_pastel = $created_order->pastel()->attach([
                    'pastel_id' => $found_pastel->id
                ]);

                if(isset($attached_pastel->error)) {

                    DB::rollBack();

                    $return_in_loop =  [
                        'error' => 1,
                        'code' => 'failed_creating_model',
                        'description' => 'Não foi possível inserir os pasteis ao pedido'
                    ];

                    break;
                }
            }

            if (!empty($return_in_loop)) {
                return $return_in_loop;
            }

            DB::commit();

			return [
				'error' => 0,
				'code' => 'created_model',
				'description' => 'Pedido cadastrado com sucesso.',
				'data' => new OrderResource($created_order)
			];

		} catch (\Exception $e) {

            DB::rollback();

			Log::emergency($e->getMessage(), [$e->getFile(), $e->getLine()]);

            return  [
				'error' => 1,
				'code' => 'unexpected_app_exception',
				'description' => 'Não foi possível processar a requisição'
			];
		}
    }

    /**
     * Soft Delete resource
     *
     * @param Request $request
     * @param \App\Http\Requests\OrderDestroyRequest $request
     * @return array
     */
    public function destroy($request)
	{
		try {

            $found_order = $this->getByFilters(['uuid' => $request->order]);

            if (!empty($found_order['error'])) {
                return [
                    'error' => 1,
                    'code' => 'model_not_found',
                    'description' => 'Cadastro não encontrado para o identificador utilizado.',
                ];
            }

            $found_order = $found_order['data']->first();

            $deleted =  $this->repository->destroy($found_order->id);

            if ($deleted) {
                return [
                    'error' => 0,
                    'code' => 'deleted_model',
                    'description' => 'Cadastro arquivado com sucesso.'
                ];
			}

			return [
				'error' => 1,
				'code' => 'failed_deleting_model',
				'description' => 'Não foi possível excluir o cadastro, tente novamente.'
			];

		} catch (\Exception $e) {
			Log::emergency($e->getMessage(), [$e->getFile(), $e->getLine()]);
			return  [
				'error' => 1,
				'code' => 'unexpected_app_exception',
				'description' => 'Não foi possível processar a requisição'
			];
		}
	}
}
