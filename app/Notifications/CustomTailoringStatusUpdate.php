<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\CustomTailoringRequest;

class CustomTailoringStatusUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    public CustomTailoringRequest $request;

    /**
     * Create a new notification instance.
     */
    public function __construct(CustomTailoringRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = ucfirst($this->request->status);

        return (new MailMessage)
            ->subject("Custom Tailoring Request Status Update - {$statusText}")
            ->greeting("Hello {$this->request->name}!")
            ->line("Your custom tailoring request status has been updated to: **{$statusText}**")
            ->line('**Request Details:**')
            ->line('Material: ' . $this->request->cloth_material)
            ->line('Color: ' . $this->request->color)
            ->line('Style: ' . $this->request->style)
            ->action('View Request Details', url('/account/orders')) // Link to user's account/orders page
            ->line('Thank you for choosing our custom tailoring service!')
            ->salutation('Best regards, Garmeva Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'customer_name' => $this->request->name,
            'status' => $this->request->status,
            'message' => 'Your custom tailoring request status has been updated to ' . ucfirst($this->request->status),
        ];
    }
}
