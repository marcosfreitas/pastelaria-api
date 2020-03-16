<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $client = Client::where('id', $this->client_id)->first();

        return [
            'uuid' => $this->uuid,
            'client' => new ClientResource($client),
            'pastels' => PastelResource::collection($this->pastel()->get()),
        ];
    }
}
