<?php

namespace App\Services;

use App\Models\Pastel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PastelResource;
use Illuminate\Support\Facades\Storage;

class PastelService
{
    private $repository;

    public function __construct(Pastel $pastel)
    {
        $this->repository = $pastel;
    }

    /**
     * List resources filtering by received parameters
     *
     * @param array $params
     * @return array
     */
    public function getByFilters(array $params = [])
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
		$found_model = $this->repository->where($filters)->withTrashed()->get();

		if ($found_model->isEmpty()) {
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
			'data' => PastelResource::collection($found_model)
		];
    }

    /**
	 * Save a new resource
	 *
	 * @param \App\Http\Requests\PastelStoreRequest $request
	 * @return array
	 */
	public function store($request)
	{
		try {

            DB::beginTransaction();

            $created_model = $this->repository->create([
                'name' => $request->name ?? null,
                'price' => $request->price ?? null,
                'photo' => '',
            ]);

			if(isset($created_model->error) || !$created_model) {

                DB::rollBack();

                return  [
					'error' => 1,
					'code' => 'failed_creating_model',
					'description' => 'Não foi possível processar a requisição'
				];
			}

            DB::commit();

            if (!$this->savePastelImage($created_model, $request)) {
                return [
                    'error' => 1,
                    'code' => 'failed_storing_picture',
                    'description' => 'Não foi possível salvar a imagem do pastel'
                ];
            }

			return [
				'error' => 0,
				'code' => 'created_model',
				'description' => 'Pastel cadastrado com sucesso.',
				'data' => new PastelResource($created_model)
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
     * Retrieve a public URL to the resource image
     *
     * @param string $uuid
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function getPastelImage($uuid, Request $request)
    {
        try {

            if (!$request->hasValidSignature()) {
				return [
					'error' => 1,
					'code' => 'failed_request_validation',
					'description' => 'Invalid Signature'
				];
			}

            # check for existent customer
            $found_model = $this->getByFilters(['uuid' => $uuid]);

            if (!empty($found_model['error'])) {
                return [
                    'error' => 1,
                    'code' => 'model_not_found',
                    'description' => 'Cadastro do pastel não encontrado'
                ];

            }

            $found_model = $found_model['data']->first();
            $image = $found_model->photo;

            if (!empty($image)) {
                $image_binary = Storage::disk('local')->get($image['image_path']);

                return [
                    'error' => 0,
                    'code' => 'data_found',
                    'description' => 'Imagem encontrada',
                    'data' => [
                        'image' => $image_binary,
                        'extension' => $image['extension']
                    ]
                ];
            }

            return [
                'error' => 0,
                'code' => 'data_not_found',
                'description' => 'O Pastel não possui uma imagem definida',
                'data' => [
                    'image' => '',
                    'extension' => ''
                ]
            ];

        } catch (\Exception $e) {
			Log::emergency($e->getMessage(), [$e->getFile(), $e->getLine()]);
			return [
				'error' => 1,
				'code' => 'unexpected_app_exception',
				'description' => 'Não foi possível processar a requisição'
			];
		}

    }

    /**
	 * Save pastel' image by passed $request data
	 *
     * @param \App\Models\Pastel $model
     * @param \App\Http\Requests\PastelStoreRequest $request
	 * @return array
	 */
	public function savePastelImage($model, $request)
	{
		try {

			# check for existent customer
			$found_model = $this->getByFilters(['uuid' => $model->uuid]);

			if (!empty($found_model['error'])) {
				return [
					'error' => 1,
					'code' => 'model_not_found',
					'description' => 'Cadastro do pastel não encontrado'
				];

            }

            $this->deletePastelImages($model->uuid);

            /**
             * Parsing Base64 image and storing into this resource folder
             */
            $image_arr = explode(';', $request->photo);
            $extension = str_replace('data:image/', '', $image_arr[0]);
            $image_arr = explode(',', end($image_arr));
            $image = base64_decode(end($image_arr));

            if (!Storage::disk('local')->put('pastels/' . $model->uuid . '/photo.' . $extension, $image)) {
                return true;
            };

			return true;

		} catch (\Exception $e) {
			Log::emergency($e->getMessage(), [$e->getFile(), $e->getLine()]);
			return [
				'error' => 1,
				'code' => 'unexpected_app_exception',
				'description' => 'Não foi possível processar a requisição'
			];
        }

    }

    private function deletePastelImages($uuid)
    {
        /**
         * Removing existent images for this resource before saves
         */
        if (Storage::disk('local')->exists('pastels/' . $uuid)) {

            $images = Storage::disk('local')->files('pastels/' . $uuid);

            foreach ($images as $image_path) {
                Storage::disk('local')->delete($image_path);
            }
        }
    }
}
