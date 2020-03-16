<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Resources\Json\JsonResource;

class PastelResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $photo_url = '';

        if (!empty($this->photo)) {
           $photo_url = URL::signedRoute('pastels.show', ['pastel' => $this->uuid]);
        }

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'price' => $this->price,
            'photo' => $photo_url
        ];
    }
}
