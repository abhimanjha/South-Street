<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\CustomTailoringRequest;

class NewCustomTailoringRequest extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Custom Tailoring Request Submitted')
            ->greeting('Hello Admin!')
            ->line('A new custom tailoring request has been submitted.')
            ->line('**Customer Details:**')
            ->line('Name: ' . $this->request->name)
            ->line('Email: ' . $this->request->email)
            ->line('Phone: ' . $this->request->phone)
            ->line('Cloth Material: ' . $this->request->cloth_material)
            ->line('Color: ' . $this->request->color)
            ->line('Style: ' . $this->request->style)
            ->action('View Request', url('/admin/custom-tailoring/' . $this->request->id))
            ->line('Please review and update the status accordingly.');
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
            'message' => 'New custom tailoring request submitted',
        ];
    }
}
