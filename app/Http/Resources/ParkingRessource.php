<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkingRessource extends JsonResource
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
            'code' => $this->code,
            'nom' => $this->nom,
            'adresse' => $this->adresse,
            'quartier' => $this->quartier,
            'ville' => $this->ville,
            'status' => $this->status,
            'archive' => $this->archive,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'Entreprise' => new EntrepriseResource($this->entreprise),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
