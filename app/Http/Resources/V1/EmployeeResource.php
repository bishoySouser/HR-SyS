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
            'fullName' => $this->full_name,
            'profilePicture' => $this->profile_pic,
            'email' => $this->email,
            'phoneNumber' => $this->phone_number,
            'id' => $this->id,
            'education' => $this->eduction,
            'manager' => new EmployeeResource($this->whenLoaded('manager')),
            'nationalId' => $this->national_id,
            'birthDate' => $this->birth_date,
            'location' => $this->location,
            'contractPeriods' => $this->contract_periods,
            'socailInsurance' => $this->socialInsurance ? 'yes' : 'no',
            'medicalInsurance' => $this->medicalInsurance ? 'yes' : 'no',
        ];
    }
}
