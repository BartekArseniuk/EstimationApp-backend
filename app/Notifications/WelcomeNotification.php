<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return (new MailMessage)
            ->subject('Witamy w EstimationApp!')
            ->greeting('Witaj ' . $notifiable->name . '!')
            ->line('Dziękujemy za rejestrację w EstimationApp - aplikacji do zarządzania wycenami projektów.')
            ->line('Cieszymy się, że jesteś z nami! Oto, co możesz zyskać:')
            ->line('• Szybkie tworzenie i zarządzanie wycenami.')
            ->line('• Łatwe przypisywanie wycen do projektów.')
            ->line('• Monitorowanie wycen oraz projektów.')
            ->salutation('Pozdrawiamy, Zespół EstimationApp');
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
