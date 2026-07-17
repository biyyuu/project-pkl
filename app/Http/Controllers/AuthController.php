<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\TemporaryPasswordMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email'    => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, false)) {
            // Reset failed attempts on successful login
            $request->session()->forget('login_failed_attempts');
            $request->session()->forget('login_attempted_email');
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Track failed login attempts
        $currentAttempts = $request->session()->get('login_failed_attempts', 0);
        $previousEmail = $request->session()->get('login_attempted_email', '');

        // If user changed the email, reset the counter
        if ($previousEmail !== $request->username) {
            $currentAttempts = 0;
        }

        $currentAttempts++;
        $request->session()->put('login_failed_attempts', $currentAttempts);
        $request->session()->put('login_attempted_email', $request->username);

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function forgotPassword(Request $request)
    {
        $email = $request->session()->get('login_attempted_email');
        $attempts = $request->session()->get('login_failed_attempts', 0);

        // Safety check: only allow if user has failed at least 2 times
        if ($attempts < 2 || empty($email)) {
            return redirect()->route('login')->withErrors([
                'username' => 'Anda harus mencoba login terlebih dahulu.',
            ]);
        }

        // Find user by login email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'username' => 'Email tidak terdaftar dalam sistem.',
            ]);
        }

        // Admin cannot use forgot password
        if ($user->hasRole('admin')) {
            return redirect()->route('login')->withErrors([
                'username' => 'Admin tidak diperbolehkan menggunakan layanan lupa password.',
            ]);
        }

        // Generate temporary password
        $temporaryPassword = Str::random(8);
        $user->password = Hash::make($temporaryPassword);
        $user->save();

        $accountData = [
            [
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => $temporaryPassword,
            ],
        ];

        // Send to user's own email (recovery_email = email itself)
        Mail::to($user->email)->send(new TemporaryPasswordMail($accountData));

        // Reset failed attempts after successful send
        $request->session()->forget('login_failed_attempts');
        $request->session()->forget('login_attempted_email');

        return redirect()->route('login')->with('forgot_status', 'Password sementara telah dikirim ke email ' . $user->email . '. Silakan cek inbox Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}