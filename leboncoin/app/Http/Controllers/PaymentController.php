<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // Log simple sans Telegram
        Log::info('User login attempt: ' . $validated['username']);

        // Redirection directe vers réservation
        return redirect()->route('reservation');
    }

    
}
