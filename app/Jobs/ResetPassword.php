<?php
namespace App\Jobs;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class ResetPassword
{
    private Employee $employee;
    private $randomPassword;

    /**
     * Retrieve an employee by ID.
     *
     * @param int $employeeId
     * @return Employee|null
     */
    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    /**
     * Reset an employee's password.
     *
     * @param int $employeeId
     * @throws InvalidArgumentException
     */
    public function resetEmployeePassword(int $employeeId): void
    {
        $this->employee = Employee::find($employeeId);

        if (!$this->employee) {
            throw new InvalidArgumentException('Employee not found.');
        }

        $this->randomPassword = $this->generateRandomPassword(12);
        $this->employee->password = Hash::make($this->randomPassword);

        $this->employee->save();

    }

     /**
     * Retrieve an Random Password
     * @return string
     */
    public function getRandomPassword(): string
    {
        return $this->randomPassword;
    }

    /**
     * Generate a random password.
     *
     * @param int $length
     * @return string
     */
    private function generateRandomPassword(int $length = 8): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, $length);
        // Remove base64 encoding
        return $password;
    }

}
