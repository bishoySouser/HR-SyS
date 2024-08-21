<?php

namespace App\Services\Interfaces;

interface LeaveRequestInterface
{
    public function canRequest($requestData): array;
    public function hasPending($employeeId): bool;
}
