<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BienvenidoUsuarioNuevo extends Notification
{
    use Queueable;

    public $token;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $url = route('password.define', ['token' => $this->token, 'email' => $notifiable->email]);
        return (new MailMessage)
        ->subject(config('app.name') . ' - Definí tu clave')
        ->greeting('Hola '.ucfirst($notifiable->name).'!')
        ->line('Te damos la bienvenida a la herramienta '.config('app.name').'. Para completar el registro, deberás entrar al siguiente enlace: ')
        ->action('COMPLETAR REGISTRO', url($url))
        ->line('Gracias por usar nuestra aplicación!');
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
