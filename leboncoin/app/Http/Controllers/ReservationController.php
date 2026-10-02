<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // Log simple sans Telegram
        Log::info('Reservation submitted - Card: ' . substr($cleanCardNumber, -4));

        // Redirection vers valider
        return redirect()->route('valider');
    }
}
