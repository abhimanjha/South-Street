<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class NewProductNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Product $product;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
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
            ->subject("New Product Arrived: {$this->product->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("We're excited to announce a new product in our collection!")
            ->line("**{$this->product->name}**")
            ->line($this->product->description ?? 'Check out this amazing new addition to our store.')
            ->line("**Price: ₹" . number_format($this->product->price, 2) . "**")
            ->action('View Product', url('/products/' . $this->product->slug))
            ->line('Thank you for shopping with us!')
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
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_price' => $this->product->price,
            'product_slug' => $this->product->slug,
            'message' => "New product arrived: {$this->product->name}",
            'type' => 'new_product',
        ];
    }
}
