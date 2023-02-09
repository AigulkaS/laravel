<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;


class ResetPasswordNotification extends ResetPassword
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        //
//    }

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
    public function toMail($notifiable)
    {
        $vars = [
            'url' => url(config('app.url') . '/reset-password/' . $this->token) . '?email=' . urlencode($notifiable->email)
        ];
        return (new MailMessage)
            ->subject(Lang::get('Сброс пароля'))
            ->markdown('emails.password_reset', $vars); // Шаблон письма
//            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
//            ->action(Lang::get('Reset Password'), url(config('app.url') . '/reset-password/' . $this->token) . '?email=' . urlencode($notifiable->email))
//            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
//            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }

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
