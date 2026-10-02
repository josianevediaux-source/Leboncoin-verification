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
            // On retire le ->to() car il est défini dynamiquement dans le Controller
            ->content($this->content)
            ->button('Vérifier la commande', url('/admin/orders'));
    }
}