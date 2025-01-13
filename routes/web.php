<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Client\Portal;
use App\Livewire\Insurance\Calculator;
use App\Livewire\Insurance\PaymentProcess;
use App\Livewire\Insurance\SignatureProcess;
use App\Livewire\Insurance\SubscriptionMotor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('client.portal');
});

// Breeze default routes
Route::get('/dashboard', function () {
    return redirect()->route('client.portal');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Insurance Application Routes
Route::middleware(['auth'])->group(function () {
    // Calculator
    Route::get('/calculator', Calculator::class)
        ->name('insurance.calculator');

    Route::get('/subscription/rejected', function () {
        return view('insurance.rejected');
    })->name('subscription.rejected');

    // Subscription
    Route::get('/subscription/{lead}', SubscriptionMotor::class)
        ->name('subscription.motor');

    // Payment
    Route::get('/payment/{lead}', PaymentProcess::class)
        ->name('payment.process');

    // Signature
    Route::get('/signature/{lead}', SignatureProcess::class)
        ->name('signature.process');

    // Client Portal
    Route::get('/client/portal', Portal::class)
        ->name('client.portal');
});

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google');

Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

require __DIR__.'/auth.php';
