<?php

namespace App\Notifications;

use App\Models\Hospital;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
//
//    public function __construct()
//    {

//    }

    protected function verificationUrl($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return str_replace('/api', '', $url);
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        $hospital = Hospital::find($notifiable->hospital_id);
        $role = Role::find($notifiable->role_id);

        $vars = [
            'url' => $verificationUrl,
            'user' => $notifiable,
            'hospital_full_name' => $hospital->full_name,
            'role' => $role->label
        ];
        return (new MailMessage)
            ->subject('Подтверждение почты') // Тема письма
            ->markdown('emails.verify', $vars); // Шаблон письма
//        return $this->buildMailMessage($verificationUrl);
    }

    protected function buildMailMessage($url)
    {
        $vars = ['url' => $url,];

        return (new MailMessage)
            ->subject('Подтверждение почты') // Тема письма
            ->markdown('emails.verify', $vars); // Шаблон письма
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
//    public function via($notifiable)
//    {
//        return ['mail'];
//    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
//    public function toArray($notifiable)
//    {
//        return [
//            //
//        ];
//    }
}
