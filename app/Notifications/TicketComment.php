<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Ticket;
use App\TicketReply;

class TicketComment extends Notification
{
    use Queueable;
    public $ticket,$ticket_comment,$user_type,$url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket,TicketReply $ticket_comment,$user_type)
    {
        $this->ticket = $ticket;
        $this->ticket_comment = $ticket_comment;
        $this->user_type = $user_type;
        if($user_type == 'user'){
            $this->url = '/ticket/'.$this->ticket->id;
        }elseif($user_type == 'admin' || $user_type == 'operator' || $user_type == 'bankUser'){
            $this->url = '/'.$this->user_type.'/ticket/'.$this->ticket->id;
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ($notifiable instanceof AnonymousNotifiable)?['mail']:['database','broadcast','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Comment on ticket.')
                    ->line('Ticket Title:'.$this->ticket->title)
                    ->line('Comment:'.$this->ticket_comment->body)
                    ->action('View', url($this->url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'ticket'=>$this->ticket,
                'url'=> $this->url,
                'icon'=> 'mdi-ticket',
                'icon_bg'=>'bg-warning'
            ]
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket'=>$this->ticket,
            'url'=> $this->url,
            'icon'=> 'mdi-ticket',
            'icon_bg'=>'bg-warning'
        ];
    }
}
