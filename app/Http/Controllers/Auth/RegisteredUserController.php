<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // LOGS DE DEBUG PARA CSRF/SESSÃO
        Log::info('[REGISTER] SESSION ID: ' . session()->getId());
        Log::info('[REGISTER] SESSION TOKEN: ' . session()->token());
        Log::info('[REGISTER] _token recebido: ' . $request->input('_token'));
        Log::info('[REGISTER] Cookies recebidos:', $_COOKIE);
        $sessionExists = DB::table('sessions')->where('id', session()->getId())->exists();
        Log::info('[REGISTER] Sessão existe no banco? ' . ($sessionExists ? 'SIM' : 'NÃO'));

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'lgpd_consent' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'lgpd_consent' => true,
            'lgpd_consent_at' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
