<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ClientResource;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Resources\ClientResourceCollection;

class ClientService
{
    private $repository;

    public function __construct(Client $client)
    {
        $this->repository = $client;
    }

    /**
     * List resources filtering by received parameters
     *
     * @param array $params
     * @return array
     */
    public function getByFilters(array $params = [], $with_trashed_resources = true)
	{
		if (!empty($params) && empty($params['phone']) && empty($params['email']) && empty($params['uuid'])) {
			return [
				'error' => 1,
				'code' => 'invalid_request_params',
				'description' => 'Filtros recebidos não suportados'
			];
		}

		$filters = [];

		if (!empty($params['email'])) {
			$filters[] = ['email', '=', $params['email']];
        }

        if (!empty($params['uuid'])) {
			$filters[] = ['uuid', '=', $params['uuid']];
        }

        if (!empty($params['phone'])) {
			$filters[] = ['phone', '=', $params['phone']];
		}

        # check for existent customer
        $found_client = $this->repository->where($filters);

        if ($with_trashed_resources) {
            $found_client = $found_client->withTrashed()->get();
        } else {
            $found_client = $found_client->get();
        }

		if ($found_client->isEmpty()) {
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
			'data' => ClientResource::collection($found_client)
		];
    }

    /**
	 * Save a new resource
	 *
	 * @param \App\Http\Requests\ClientStoreRequest $request
	 * @return array
	 */
	public function store($request)
	{
		try {

            DB::beginTransaction();

			# check for existent customer
            $found_client = $this->getByFilters(['email' => $request->email]);

            if (empty($found_client['error'])) {
                return [
                    'error' => 1,
                    'code' => 'email_in_use',
                    'description' => 'O e-mail utilizado já está em uso.'
                ];
            }

            $created_client = $this->repository->create([
                'name' => $request->name ?? null,
                'email' => $request->email ?? null,
                'phone' => $request->phone ?? null,
                'birth' => $request->birth ?? null,
                'address' => $request->address ?? null,
                'complement' => $request->complement ?? null,
                'district' => $request->district ?? null,
                'zip_code' => $request->zip_code ?? null,
            ]);

			if(isset($created_client->error) || !$created_client) {

                DB::rollBack();

                return  [
					'error' => 1,
					'code' => 'failed_creating_model',
					'description' => 'Não foi possível processar a requisição'
				];
			}

            DB::commit();

			return [
				'error' => 0,
				'code' => 'created_model',
				'description' => 'Cliente cadastrado com sucesso.',
				'data' => new ClientResource($created_client)
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
     * Update client resource
     *
     * @param Request $request
     * @param \App\Http\Requests\ClientUpdateRequest $request
     * @return array
     */
    public function update($request)
	{
		try {
            # check for existent customer
            # @info $request->client is passed into URL as a convention of Laravel Controller Resources
            $found_client = $this->getByFilters(['uuid' => $request->client]);

            if (!empty($found_client['error'])) {
                return [
                    'error' => 1,
                    'code' => 'model_not_found',
                    'description' => 'Cadastro não encontrado para o identificador utilizado.',
                ];
            }

            $found_client = $found_client['data']->first();
            $data = $request->validated();

            if (isset($request->email)) {
                unset($data['email']);
            }

            if ($request->phone === $found_client->phone) {
                unset($data['phone']);
            } else {

                $another_client_by_phone = $this->getByFilters(['phone' => $request->phone]);

                if (empty($another_client_by_phone['error'])) {
                    return [
                        'error' => 1,
                        'code' => 'phone_already_in_use',
                        'description' => 'Este telefone não pode ser utilizado.',
                    ];
                }

            }

			$updated = $found_client->update($data);

			if ($updated) {
				return [
					'error' => 0,
					'code' => 'updated_model',
					'description' => 'Cadastro atualizado com sucesso.',
					'data' => new ClientResource($found_client)
				];
			}

			return [
				'error' => 1,
				'code' => 'failed_updating_model',
				'description' => 'Não foi possível atualizar o cadastro, tente novamente.'
			];

		} catch (\Exception $e) {
			Log::emergency($e->getMessage(), [$e , $e->getFile(), $e->getLine()]);
			return  [
				'error' => 1,
				'code' => 'unexpected_app_exception',
				'description' => 'Não foi possível processar a requisição'
			];
		}
    }

     /**
     * Soft Delete client resource
     *
     * @param Request $request
     * @param \App\Http\Requests\ClientDestroyRequest $request
     * @return array
     */
    public function destroy($request)
	{
		try {

            $found_client = $this->getByFilters(['uuid' => $request->client]);

            if (!empty($found_client['error'])) {
                return [
                    'error' => 1,
                    'code' => 'model_not_found',
                    'description' => 'Cadastro não encontrado para o identificador utilizado.',
                ];
            }

            $found_client = $found_client['data']->first();

            $deleted =  $this->repository->destroy($found_client->id);

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
