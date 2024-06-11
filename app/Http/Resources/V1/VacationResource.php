<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class VacationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'from' => Carbon::parse($this->start_date)->format('d F Y'),
            'to' => Carbon::parse($this->end_date)->format('d F Y'),
            'status' => $this->status,
        ];
    }
}
