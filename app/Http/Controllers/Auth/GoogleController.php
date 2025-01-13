<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]
            );

            Auth::login($user);

            if ($user->hasActiveLead()) {
                return redirect()->route('insurance.calculator');
            }

            if ($user->hasActivePolicy()) {
                return redirect()->route('client.portal');
            }

            return redirect()->route('insurance.calculator');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Error al autenticar con Google');
        }
    }
}
