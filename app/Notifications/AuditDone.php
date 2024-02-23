<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Crypt;
class AuditDone extends Notification
{
    use Queueable;

    private $noti_data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Array $noti_data)
    {
        $this->noti_data = $noti_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            "url"=>"partner/single_audit_detail/".Crypt::encrypt($this->noti_data['audit_id']),
            "upper_text"=>$this->noti_data['upper_text'],
            "description"=>$this->noti_data['description'],
            "audit_id"=>$this->noti_data['audit_id'],
            "name"=>$this->noti_data['name'],
            "avatar"=>$this->noti_data['avatar'],
        ];
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
