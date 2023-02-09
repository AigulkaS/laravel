<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

//use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Notifications\Notification;
//use Illuminate\Support\Carbon;
//use NotificationChannels\WebPush\WebPushChannel;
//use NotificationChannels\WebPush\WebPushMessage;
//use App\Models\User;

class PushDemo extends Notification
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
        return [WebPushChannel::class];
//        return ['database', 'broadcast', WebPushChannel::class];
    }

//    public function toArray($notifiable)
//    {
//        return [
//            'title' => 'Hello from Laravel!',
//            'body' => 'Thank you for using our application.',
//            'action_url' => 'https://laravel.com',
//            'created' => Carbon::now()->toIso8601String(),
//        ];
//    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage())
            ->title('Welcome to the application')
            ->body('This is notification body content. You are successfully subscribed!');
    }


//    public function toWebPush($notifiable, $notification)
//    {
//        return (new WebPushMessage)
//            ->title('I\'m Notification Title')
//            ->body('Great, Push Notifications work!');
////            ->action('View App', 'notification_action');
//    }

}
