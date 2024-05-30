<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->full_name,
            'email' => $this->email,
            'phoneNumber' => $this->phone_number,
            'education' => $this->eduction,
            'manager' => new EmployeeResource($this->whenLoaded('manager')),
            'profilePicture' => $this->profile_pic,
            'nationalId' => $this->national_id,
            'birthDate' => $this->birth_date,
            'location' => $this->location,
            'contractPeriods' => $this->contract_periods,
            'socailInsurance' => $this->socialInsurance ? 'yes' : 'no',
            'medicalInsurance' => $this->medicalInsurance ? 'yes' : 'no',
        ];
    }
}
