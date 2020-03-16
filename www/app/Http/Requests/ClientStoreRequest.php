<?php

namespace App\Http\Requests;

use App\Http\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
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
			'name' => 'request|string',
			'email' => 'required|email|unique:clients,email',
			'phone' => 'numeric|unique:clients,phone',
			'birth' => 'date|present|nullable',
			'address' => 'required|string',
            'complement' => 'required|string',
            'district' => 'required|string',
			'zip_code' => 'required|string'
		];

		return $fillable;
    }

    public function attributes()
    {
        return [
            'name' => 'Nome',
            'email' => 'E-mail',
            'phone' => 'Telefone',
            'birth' => 'Data de Nascimento',
            'address' => 'Endereço',
            'complement' => 'Complemento',
            'district' => 'Bairro',
            'zip_code' => 'CEP'
        ];
    }

}
