<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Trait to implement common override methods into Client Custom Form Requests
 */
trait BaseRequestTrait
{
/**
	 * Get rewritten params passed into route (url.com/{param_1}/{param_2}) to be validated by this request, otherwise It will be invalid.
	 *
	 * @return array
	 */
	public function validationData()
	{
		if (method_exists($this->route(), 'parameters')) {
			$this->request->add($this->route()->parameters());
			$this->query->add($this->route()->parameters());

			return array_merge($this->route()->parameters(), $this->all());
        }

		return $this->all();
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     *
     * @todo should throws a \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors =  (new ValidationException($validator))->errors();
        $errors = implode(PHP_EOL , array_map(function ($arr) {
			return implode(PHP_EOL , $arr);
        }, $errors));

        $response = response()->json(
            [
                'error' => 1,
                'code' => 'invalid_request_params',
                'descriptions' => $errors
            ],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
         );

        throw (new HttpResponseException($response));

    }
}
