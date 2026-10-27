<?php

namespace Acsi\AuthCode\Http\Controllers\Auth;

use Acsi\AuthCode\Mail\RegistrationVerifyMail;
use Acsi\AuthCode\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function showRegister()
    {
        return view('auth-code::auth.register');
    }

    public function register(Request $request)
    {
        $userModel = config('auth-code.user_model', \App\Models\User::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:registrations,email',
            'domain_name' => 'nullable|string|max:255',
        ]);

        $token = Str::random(64);

        $registration = Registration::create([
            'name' => $request->name,
            'email' => $request->email,
            'domain_name' => $request->domain_name,
            'token' => $token,
            'expires_at' => now()->addHours(config('auth-code.registration_expiry_hours', 24)),
        ]);

        Mail::to($request->email)->send(new RegistrationVerifyMail($registration));

        return redirect()->route('auth.register.verify')
            ->with('email', $request->email)
            ->with('success', 'Verification link sent to your email.');
    }

    public function showVerify()
    {
        $email = session('email');
        if (! $email) {
            return redirect()->route('auth.register');
        }

        return view('auth-code::auth.register-verify', ['email' => $email]);
    }

    public function verify(Request $request)
    {
        $user = $this->activateRegistration($request->email, $request->token);

        if (! $user) {
            return back()->withErrors(['token' => 'Invalid or expired verification token.']);
        }

        return redirect()->intended(config('auth-code.redirect_after_login', '/dashboard'))
            ->with('success', 'Account created! Welcome to '.config('app.name').'.');
    }

    public function verifyByLink(Request $request)
    {
        $user = $this->activateRegistration(
            (string) $request->query('email'),
            (string) $request->query('token')
        );

        if (! $user) {
            return redirect()->route('auth.register.verify')
                ->with('email', (string) $request->query('email'))
                ->withErrors(['token' => 'Invalid or expired verification link. Please paste your token manually.']);
        }

        return redirect()->intended(config('auth-code.redirect_after_login', '/dashboard'))
            ->with('success', 'Account created! Welcome to '.config('app.name').'.');
    }

    private function activateRegistration(?string $email, ?string $token): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        if (! $email || ! $token) {
            return null;
        }

        $registration = Registration::where('email', $email)
            ->where('token', $token)
            ->whereNull('activated_at')
            ->first();

        if (! $registration || $registration->isExpired()) {
            return null;
        }

        $userModel = config('auth-code.user_model', \App\Models\User::class);
        $adminRole = config('auth-code.admin_role', 'admin');
        $clientRole = config('auth-code.client_role', 'client');

        $role = $userModel::count() === 0 && config('auth-code.first_user_is_admin', true)
            ? $adminRole
            : $clientRole;

        $user = $userModel::create([
            'name' => $registration->name,
            'email' => $registration->email,
            'password' => Hash::make(Str::random(16)),
            'role' => $role,
            'is_active' => true,
        ]);

        $registration->update([
            'verified_at' => now(),
            'activated_at' => now(),
        ]);

        auth()->login($user, remember: true);

        return $user;
    }
}