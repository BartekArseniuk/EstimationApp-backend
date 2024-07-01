<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/api/reset-password/' . $this->token);

        return (new MailMessage)
            ->subject('Żądanie resetowania hasła')
            ->greeting('Witaj!')
            ->line('Otrzymujesz ten e-mail, ponieważ otrzymaliśmy żądanie zresetowania hasła dla Twojego konta.')
            ->action('Zresetuj hasło', $url)
            ->line('Jeśli nie żądałeś resetowania hasła, nie musisz podejmować żadnych dodatkowych działań.')
            ->salutation('Pozdrawiamy, Zespół EstimationApp');
            
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
