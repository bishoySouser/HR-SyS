<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordEmployeeNotification extends Notification
{
    use Queueable;

    private $employee;
    private $newPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct(Employee $employee, $newPassword)
    {
        $this->employee = $employee;
        $this->newPassword = $newPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        \Log::info($this->newPassword);
        return (new MailMessage)
            ->subject('Reset Password [' . $this->employee->full_name . ']')
            ->markdown('emails.reset-password-employee', [
                                                            'employee' => $this->employee,
                                                            'new_password' => $this->newPassword,
                                                        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'employee' => $this->employee,
            'new_password' => $this->newPassword,
        ];
    }
}
