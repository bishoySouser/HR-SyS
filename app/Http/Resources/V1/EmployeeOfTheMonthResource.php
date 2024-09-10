<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeOfTheMonthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'employee_id' => $this->employee_id,
            'employee' => $this->when(
                $this->relationLoaded('employee') && $this->employee_id,
                function () {
                    return new EmployeeResource($this->employee_id);
                },
                null
            ),
            'month' => $this->month,
        ];
    }
}
