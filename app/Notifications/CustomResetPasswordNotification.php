<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;
    public $userName;
    public $token;
    public $email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userName, $token, $email)
    {
        $this->userName = $userName;
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link = url("/password/reset/" . $this->token . '?email=' . $this->email);
        return (new MailMessage)
            ->subject('Password Reset Link for Your ' . config('app.name') .  ' Account')
            ->greeting('Dear ' . $this->userName . ',')
            ->line('You have requested a password reset for your ' . config('app.name') . ' account. Please click the link below to reset your password. This link is valid for 24 hours:')
            ->action('Reset Password', $link)
            ->line('If you have any concerns or did not request a password reset, please contact our customer support team.')
            ->line('Thank you for choosing ' . config('app.name') . '  as your payment processing partner.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
