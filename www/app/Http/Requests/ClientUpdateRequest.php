<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\ClientRequestBaseTrait;

class ClientUpdateRequest extends FormRequest
{
    use ClientRequestBaseTrait;

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
			'name' => 'present|string',
			'phone' => 'numeric',
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
