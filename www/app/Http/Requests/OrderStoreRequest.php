<?php

namespace App\Http\Requests;

use App\Http\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    use BaseRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $fillable = [
			'client' => 'request|string',
			'pastels' => 'required|array'
		];

		return $fillable;
    }

    public function attributes()
    {
        return [
            'client' => 'Client',
            'pastels' => 'Pastels'
        ];
    }

}
