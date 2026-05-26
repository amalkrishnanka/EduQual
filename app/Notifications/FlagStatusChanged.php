<?php

namespace App\Notifications;

use App\Models\Flag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class FlagStatusChanged extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Flag $flag,
        protected string $oldStatus,
        protected string $newStatus,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Flag Status Updated: ' . $this->flag->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The status of a flag you raised has been updated.')
            ->line('**Flag:** ' . $this->flag->title)
            ->line('**Previous Status:** ' . ucfirst(str_replace('_', ' ', $this->oldStatus)))
            ->line('**New Status:** ' . ucfirst(str_replace('_', ' ', $this->newStatus)))
            ->action('View Flag', url('/flags/' . $this->flag->id))
            ->line('Thank you for using Bookly.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'flag_id' => $this->flag->id,
            'title' => $this->flag->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
