<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cerca o crea l'usuari a la base de dades
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'operador',
                ]
            );

            // Autentica l'usuari
            Auth::login($user);

            // Generar token Sanctum
            // Si volem autenticar en l'API podriem generar un token
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            // Redirigir l'usuari amb el token 
            return view('auth.success', ['token' => $token]); // Asumint que tens una vista 'auth.success'

        } catch (\Exception $e) {
            // Maneig d'errors
            return view('auth.error', ['error' => $e->getMessage()]); // Asumint que tens una vista 'auth.error'
        }
    }
}
