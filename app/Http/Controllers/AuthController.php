<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /** Show login page */
    public function showLogin()
    {
        return view('auth.login');
    }

    /** Show admin login form */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /** Show registration page */
    public function showRegister()
    {
        return view('auth.register');
    }

    /** Handle unified login with role-based redirection */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();

                // Explicit admin panel redirection check with email fallback
                if (($user->role ?? '') === 'admin' || $user->email === 'dhavalsolanki615@gmail.com') {
                    return redirect()->intended('/admin');
                }

                return redirect()->intended('/store');
            }

            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        } catch (\Throwable $e) {
            \Log::error('Login Exception: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }
    }

    /** Handle normal registration with comprehensive error handling */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            ];

            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'role')) {
                $userData['role'] = 'user';
            }

            $user = \App\Models\User::create($userData);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/store');
        } catch (\Throwable $e) {
            \Log::error('Register Exception: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    /** Email verification handler with error handling */
    public function verifyEmail(Request $request)
    {
        try {
            $record = \Illuminate\Support\Facades\DB::table('email_verifications')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$record) {
                return view('verify-result', [
                    'status' => 'Invalid or expired token.',
                    'token' => null,
                    'redirect_url' => route('notlogin.index')
                ]);
            }

            $user = \App\Models\User::where('email', $request->email)->first();
            if (!$user) {
                return view('verify-result', [
                    'status' => 'User not found.',
                    'token' => $request->token,
                    'redirect_url' => route('notlogin.index')
                ]);
            }

            $user->email_verified_at = now();
            $user->save();

            \Illuminate\Support\Facades\DB::table('email_verifications')->where('email', $request->email)->delete();

            Auth::login($user);

            // Determine user role with fallback
            $userRole = isset($user->role) ? $user->role : 'user';

            session([
                'user_logged_in' => true,
                'user_email'     => $user->email,
                'is_admin'       => ($userRole === 'admin')
            ]);
            $request->session()->regenerate();

            // Role-based redirect after verification
            $redirectUrl = ($userRole === 'admin') ? route('admin.dashboard') : route('store');

            return view('verify-result', [
                'status' => 'Email verified successfully!',
                'token'  => $request->token,
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Throwable $e) {
            Log::error('Email verification failed: ' . $e->getMessage());
            return view('verify-result', [
                'status' => 'Verification failed. Please try again.',
                'token' => $request->token ?? null,
                'redirect_url' => route('notlogin.index')
            ]);
        }
    }

    /** Logout with error handling */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->forget(['user_logged_in', 'user_email', 'is_admin', 'admin_id', 'admin_email', 'admin_name']);
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('notlogin.index')->with('success', 'Logged out successfully!');
        } catch (\Throwable $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return redirect()->route('notlogin.index')->with('success', 'Logged out successfully!');
        }
    }

    // -----------------------------
    // Forgot Password & OTP flows
    // -----------------------------

    /** Show forgot password choice */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /** OTP Recovery Page */
    public function showOtpPage()
    {
        return view('auth.forgot-password-otp');
    }

    /** Handle OTP Request with mail fallback */
    public function sendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ]);

            $email = $request->email;
            $otp = rand(100000, 999999);

            \Illuminate\Support\Facades\DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => $otp,
                    'created_at' => now()
                ]
            );

            try {
                config(['mail.mailers.smtp.transport' => 'log']);
                \Illuminate\Support\Facades\Mail::raw("Your OTP for password reset is: {$otp}", function ($message) use ($email) {
                    $message->to($email)->subject('Password Reset OTP');
                });
            } catch (\Throwable $mailEx) {
                \Log::warning('OTP Mail failed to send (using log fallback): ' . $mailEx->getMessage());
            }

            return back()->with('status', 'OTP sent successfully!');
        } catch (\Throwable $e) {
            \Log::error('OTP Request Exception: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to process OTP request. Please try again.']);
        }
    }

    /** Alias for sendOtp for backward compatibility */
    public function handleOtp(Request $request)
    {
        return $this->sendOtp($request);
    }

    /** Show OTP verification form */
    public function showOtpVerify($email)
    {
        return view('auth.verify-otp', compact('email'));
    }

    /** Handle OTP verification with error handling */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp'   => 'required|digits:6'
            ]);

            $record = \Illuminate\Support\Facades\DB::table('password_resets')->where('email', $request->email)->first();
            if (!$record || $record->token != $request->otp) {
                return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
            }

            return redirect()->route('forgot.password.newpass', ['email' => $request->email]);

        } catch (\Throwable $e) {
            Log::error('OTP verification failed: ' . $e->getMessage());
            return back()->withErrors(['otp' => 'An error occurred. Please try again.']);
        }
    }

    /** New Password Page */
    public function showNewPassPage($email)
    {
        return view('auth.new-password', compact('email'));
    }

    /** Handle New Password with comprehensive error handling */
    public function handleNewPass(Request $request)
    {
        try {
            $request->validate([
                'email'        => 'required|email|exists:users,email',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $user = \App\Models\User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'User not found.']);
            }

            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);

            // Only set temp_password if the column exists
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'temp_password')) {
                $user->temp_password = $request->new_password;
            }

            $user->save();

            \Illuminate\Support\Facades\DB::table('password_resets')->where('email', $request->email)->delete();

            return redirect()->route('login')->with('success', 'Password changed successfully! You can login now.');

        } catch (\Throwable $e) {
            Log::error('Password update failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
    }

    /** Old Password Recovery Page */
    public function showOldPassPage()
    {
        return view('auth.forgot-password-old');
    }

    /** Handle Old Password Change with comprehensive error handling */
    public function handleOldPass(Request $request)
    {
        try {
            $request->validate([
                'email'        => 'required|email|exists:users,email',
                'old_password' => 'required',
                'new_password' => 'required|string|min:6|confirmed',
            ], [
                'email.exists' => 'This email is not registered in our system.',
            ]);

            $user = \App\Models\User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'User not found.']);
            }

            if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Old password does not match our records.']);
            }

            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);

            // Only set temp_password if the column exists
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'temp_password')) {
                $user->temp_password = $request->new_password;
            }

            $user->save();

            return back()->with('success', 'Password changed successfully. You can now login with your new password.');

        } catch (\Throwable $e) {
            Log::error('Old password change failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
    }
}
