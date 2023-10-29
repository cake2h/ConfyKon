<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            $this->id,
            $this->name,
            $this->country,
            $this->city,
            $this->date_start,
            $this->date_end,
            $this->deadline,
            $this->description
        ];
    }
}
