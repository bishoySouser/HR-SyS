<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EmployeeOfTheMonthResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->getRawOriginal('month'));

        return [
            'employee' => $this->whenLoaded('employee', new EmployeeResource( $this->employee )),
            'month' => $date->format('Y F'),
        ];
    }
}
