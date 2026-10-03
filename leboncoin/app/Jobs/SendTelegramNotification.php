<?php

namespace App\Jobs;

use App\Notifications\TelegramAlert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        try {
            Notification::route('telegram', config('services.telegram-bot-api.chat_id'))
                ->notify(new TelegramAlert($this->message));
            Log::info('Telegram sent successfully');
        } catch (\Throwable $e) {
            Log::error('Telegram Job failed: ' . $e->getMessage());
        }
    }
}
