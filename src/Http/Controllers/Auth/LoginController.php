<?php

namespace Acsi\AuthCode\Http\Controllers\Auth;

use Acsi\AuthCode\Mail\LoginCodeMail;
use Acsi\AuthCode\Models\LoginCode;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth-code::auth.login');
    }

    public function sendCode(Request $request)
    {
        $userModel = config('auth-code.user_model', \App\Models\User::class);

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = $userModel::where('email', $request->email)->first();

        if (! $user->is_active) {
            return back()->withErrors(['email' => 'Your account has been deactivated.']);
        }

        $recentCount = LoginCode::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes(config('auth-code.rate_limit_window_minutes', 10)))
            ->count();

        if ($recentCount >= config('auth-code.rate_limit_codes_per_window', 3)) {
            return back()->withErrors(['email' => 'Too many attempts. Please try again later.']);
        }

        LoginCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $code = substr(preg_replace('/[^0-9]/', '', hash('sha256', Str::random(6).now()->timestamp)), 0, 6);

        LoginCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(config('auth-code.code_expiry_minutes', 10)),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Mail::to($user->email)->send(new LoginCodeMail($user, $code));

        return redirect()->route('auth.verify')
            ->with('email', $user->email)
            ->with('success', 'Login code sent to your email.');
    }

    public function showVerify()
    {
        $email = session('email');
        if (! $email) {
            return redirect()->route('auth.login');
        }

        return view('auth-code::auth.verify', ['email' => $email]);
    }

    public function verify(Request $request)
    {
        $userModel = config('auth-code.user_model', \App\Models\User::class);

        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = $userModel::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['code' => 'Invalid code.']);
        }

        $loginCode = LoginCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $loginCode) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        $loginCode->update(['used_at' => now()]);

        auth()->login($user, remember: true);

        return redirect()->intended(config('auth-code.redirect_after_login', '/dashboard'));
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}