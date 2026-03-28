<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        // VALIDASI CAPTCHA WAJIB
        $request->validate([
            'g-recaptcha-response' => 'required'
        ], [
            'g-recaptcha-response.required' => 'Silakan centang captcha terlebih dahulu.'
        ]);


        // VERIFIKASI CAPTCHA KE GOOGLE
        $response = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret="
            . config('services.recaptcha.secret_key')
            . "&response="
            . $request->input('g-recaptcha-response')
        );

        $response = json_decode($response);


        if (!$response->success) {
            return back()->withErrors([
                'captcha' => 'Verifikasi captcha gagal.'
            ]);
        }


        // LOGIN NORMAL LARAVEL
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}