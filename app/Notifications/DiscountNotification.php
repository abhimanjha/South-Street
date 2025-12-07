<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DiscountNotification extends Notification
{
    use Queueable;

    public string $title;
    public string $message;
    public string $discountCode;
    public float $discountPercentage;
    public string $expiryDate;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $message, string $discountCode, float $discountPercentage, string $expiryDate)
    {
        $this->title = $title;
        $this->message = $message;
        $this->discountCode = $discountCode;
        $this->discountPercentage = $discountPercentage;
        $this->expiryDate = $expiryDate;
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
        return (new MailMessage)
            ->subject($this->title)
            ->greeting("Hello {$notifiable->name}!")
            ->line($this->message)
            ->line("**Discount Code: {$this->discountCode}**")
            ->line("**{$this->discountPercentage}% OFF**")
            ->line("Valid until: {$this->expiryDate}")
            ->action('Shop Now', url('/products'))
            ->line('Don\'t miss out on this amazing offer!')
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
            'title' => $this->title,
            'message' => $this->message,
            'discount_code' => $this->discountCode,
            'discount_percentage' => $this->discountPercentage,
            'expiry_date' => $this->expiryDate,
            'type' => 'discount',
        ];
    }
}
