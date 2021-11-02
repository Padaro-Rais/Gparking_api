<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentRessource extends JsonResource
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
            'prenoms' => $this->prenoms,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'matricule_ent' => $this->matricule_ent,
            'status' => $this->status,
            'archive' => $this->archive,
            'parking' => new ParkingRessource($this->parking),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

    }
}
