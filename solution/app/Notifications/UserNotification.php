<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification {
    use Queueable;

    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $status;
    /**
     * @var bool
     */
    private $persist;
    /**
     * @var null
     */
    private $user_new_balance;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     * @param string $status
     * @param bool $persist
     * @param null $user_new_balance
     */
    public function __construct(string $message, string $status = 'processing', bool $persist = false, $user_new_balance = null)
    {
        $this->message = $message;
        $this->status = $status;
        $this->persist = $persist;
        $this->user_new_balance = $user_new_balance;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ['broadcast'];
        if ($this->persist) {
            $channels[] = 'database';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage($this->toArray($notifiable)))
            ->onConnection('sync') // using sync to make it easy to run on Heroku single dyno worker
            ->onQueue('broadcasts');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'status' => $this->status,
            'persisted' => $this->persist,
            'newbalance' => $this->user_new_balance
        ];
    }
}
