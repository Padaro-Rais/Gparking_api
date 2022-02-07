<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntrepriseParkingResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'entreprise' => new EntrepriseResource($this->entreprise),
            'parking' => new ParkingRessource($this->parking),
            'entreprise' => new EntrepriseResource($this->entreprise),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
