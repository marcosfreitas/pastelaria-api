<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            //'archived' => $this->isTrashed(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'birth' => $this->birth,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'complement' => $this->complement,
            'district' => $this->district,
            'zip_code' => $this->zip_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //'deleted_at' => $this->deleted_at
        ];
    }
}
