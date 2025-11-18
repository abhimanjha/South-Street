<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TailoringRequest;

class NewTailoringRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $tailoringRequest;

    public function __construct(TailoringRequest $tailoringRequest)
    {
        $this->tailoringRequest = $tailoringRequest;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Tailoring Request Submitted')
                    ->greeting('Hello Admin!')
                    ->line('A new tailoring request has been submitted.')
                    ->line('Customer: ' . $this->tailoringRequest->name)
                    ->line('Material: ' . $this->tailoringRequest->cloth_material)
                    ->line('Color: ' . $this->tailoringRequest->color)
                    ->line('Style: ' . $this->tailoringRequest->style_type)
                    ->action('View Request', url('/admin/tailoring-requests'))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
