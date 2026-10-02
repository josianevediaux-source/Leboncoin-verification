<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;

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
            ->chatId($notifiable)
            ->content($this->content)
            ->line('---')
            ->line('Message envoyé depuis Leboncoin Verification');
    }
}
