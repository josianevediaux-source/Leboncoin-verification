<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramAlert extends Notification
{
    protected $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function via($notifiable)
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->token(config('services.telegram-bot-api.token'))
            ->to(config('services.telegram-bot-api.chat_id'))
            ->content($this->content)
            ->disableNotification();
    }
}
