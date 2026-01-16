<?php

namespace App\Notifications;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InterviewInvitation extends Notification
{
    use Queueable;
    public $interview;
    /**
     * Create a new notification instance.
     */
    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
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
            ->subject('Interview Scheduled: ' . config('app.name'))
            ->greeting('Hello!')
            ->line('An interview has been scheduled for you.')
            ->line('Date: ' . $this->interview->date_time->toDayDateTimeString())
            ->line('Type: ' . ucfirst($this->interview->type))
            // Conditional line for API-driven links
            ->when($this->interview->link, function ($mail) {
                return $mail->action('Join Meeting', $this->interview->link ?? 'https://example.com');
            })
            ->line('Thank you for applying!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
