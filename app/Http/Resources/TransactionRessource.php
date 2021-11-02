<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionRessource extends JsonResource
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
            'num_plaque' => $this->num_plaque,
            'date' => $this->date,
            'archive' => $this->archive,
            'client' => new ClientRessource($this->client),
            'agent' => new AgentRessource($this->agent),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

    }
}
