<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntrepriseResource extends JsonResource
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
            'matricule' => $this->matricule,
            'name' => $this->name,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'status' => $this->status,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
