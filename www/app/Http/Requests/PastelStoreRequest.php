<?php

namespace App\Http\Requests;

use App\Rules\EncodedImage;
use App\Http\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class PastelStoreRequest extends FormRequest
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
			'price' => 'required|decimal',
			'photo' => [
                'required',
                new EncodedImage(['png', 'jpeg', 'jpg'])
            ]
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
