<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifyEmail;

class AuthController extends Controller
{
    /** Show login page */
    public function showLogin()
    {
        return view('auth.login');
    }

    /** Show registration page */
    public function showRegister()
    {
        return view('auth.register');
    }

    /** Handle normal login */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'    => 'required|email|min:5|max:50',
                'password' => 'required|min:3|max:20',
            ]);

            $email = $credentials['email'];
            $password = $credentials['password'];

            // ---------------------
            // Admin authentication check
            // ---------------------
            $admin = Admin::where('email', $email)->first();
            if ($admin && Hash::check($password, $admin->password) && $admin->isActive()) {
                // Update last login
                $admin->updateLastLogin();
                
                // Create a session for admin
                session([
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email,
                    'admin_name' => $admin->name,
                    'is_admin' => true
                ]);
                
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')->with('success', 'Admin logged in successfully!');
            }

            // ---------------------
            // Normal user login
            // ---------------------
            $user = User::where('email', $email)->first();

            // Check email verification
            if ($user && !$user->email_verified_at && Hash::check($password, $user->password)) {
                $token = Str::random(32);
                DB::table('email_verifications')->updateOrInsert(
                    ['email' => $email],
                    ['token' => $token, 'created_at' => now(), 'updated_at' => now()]
                );

                Mail::to($email)->send(new VerifyEmail($email, $token));

                return redirect()->route('verification.wait', ['email' => $email])
                    ->with('error', 'Email not verified. Verification email sent.');
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                session(['is_admin' => false]);
                return redirect()->route('notlogin.index')->with('success', 'Logged in successfully!');
            }

            return back()->with('error', 'Invalid email or password.');
        } catch (\Throwable $e) {
            \Log::error('Login Exception: ' . $e->getMessage());
            return back()->with('error', 'Invalid email or password.');
        }
    }

    /** Handle normal registration */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = Str::random(32);
            DB::table('email_verifications')->updateOrInsert(
                ['email' => $user->email],
                ['token' => $token, 'created_at' => now(), 'updated_at' => now()]
            );

            Mail::to($user->email)->send(new VerifyEmail($user->email, $token));

            return redirect()->route('verification.wait', ['email' => $user->email])
                ->with('success', 'Account created! Verification email sent.');
        } catch (\Throwable $e) {
            \Log::error('Register Exception: ' . $e->getMessage());
            return back()->with('error', 'Registration failed. Please try again.');
        }
    }

    /** Email verification handler */
    public function verifyEmail(Request $request)
    {
        try {
            $record = DB::table('email_verifications')
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

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return view('verify-result', [
                    'status' => 'User not found.',
                    'token' => $request->token,
                    'redirect_url' => route('notlogin.index')
                ]);
            }

            $user->email_verified_at = now();
            $user->save();

            DB::table('email_verifications')->where('email', $request->email)->delete();

            Auth::login($user);
            session([
                'user_logged_in' => true,
                'user_email'     => $user->email,
                'is_admin'       => false
            ]);
            $request->session()->regenerate();

            return view('verify-result', [
                'status' => 'Email verified successfully!',
                'token'  => $request->token,
                'redirect_url' => route('notlogin.index')
            ]);
        } catch (\Throwable $e) {
            \Log::error('Email verification failed: ' . $e->getMessage());
            return view('verify-result', [
                'status' => 'Verification failed. Please try again.',
                'token' => $request->token ?? null,
                'redirect_url' => route('notlogin.index')
            ]);
        }
    }

    /** Logout */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->forget(['user_logged_in', 'user_email', 'is_admin', 'admin_id', 'admin_email', 'admin_name']);
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('notlogin.index')->with('success', 'Logged out successfully!');
        } catch (\Throwable $e) {
            \Log::error('Logout failed: ' . $e->getMessage());
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

    /** Handle OTP Request */
    public function handleOtp(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email|exists:users,email']);

            $email = $request->email;
            $otp = rand(100000, 999999);

            if (\Illuminate\Support\Facades\Schema::hasTable('password_resets')) {
                DB::table('password_resets')->updateOrInsert(
                    ['email' => $email],
                    ['token' => $otp, 'created_at' => now()]
                );
            }

            try {
                config(['mail.mailers.smtp.transport' => 'log']);
                \Illuminate\Support\Facades\Mail::raw("Your OTP is: {$otp}", function ($message) use ($email) {
                    $message->to($email)->subject('Password Reset OTP');
                });
            } catch (\Throwable $mailEx) {
                \Log::warning('OTP Mail fallback warning: ' . $mailEx->getMessage());
            }

            return redirect()->route('forgot.password.otp.verify', ['email' => $email])
                ->with('success', 'OTP has been sent to your email.');
        } catch (\Throwable $e) {
            \Log::error('OTP Request Exception: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to process OTP request. Please try again.']);
        }
    }

    /** Show OTP verification form */
    public function showOtpVerify($email)
    {
        return view('auth.verify-otp', compact('email'));
    }

    /** Handle OTP verification */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp'   => 'required|digits:6'
            ]);

            $record = DB::table('password_resets')->where('email', $request->email)->first();
            if (!$record || $record->token != $request->otp) {
                return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
            }

            return redirect()->route('forgot.password.newpass', ['email' => $request->email]);
        } catch (\Throwable $e) {
            \Log::error('OTP verification failed: ' . $e->getMessage());
            return back()->withErrors(['otp' => 'An error occurred. Please try again.']);
        }
    }

    /** New Password Page */
    public function showNewPassPage($email)
    {
        return view('auth.new-password', compact('email'));
    }

    /** Handle New Password */
    public function handleNewPass(Request $request)
    {
        try {
            $request->validate([
                'email'        => 'required|email|exists:users,email',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'User not found.']);
            }

            $user->password = Hash::make($request->new_password);

            // Only set temp_password if the column exists
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'temp_password')) {
                $user->temp_password = $request->new_password;
            }

            $user->save();

            if (\Illuminate\Support\Facades\Schema::hasTable('password_resets')) {
                DB::table('password_resets')->where('email', $request->email)->delete();
            }

            return redirect()->route('login')->with('success', 'Password changed successfully! You can login now.');
        } catch (\Throwable $e) {
            \Log::error('Password update failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
    }

    /** Old Password Recovery Page */
    public function showOldPassPage()
    {
        return view('auth.forgot-password-old');
    }

    /** Handle Old Password Change */
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

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'User not found.']);
            }

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Old password does not match our records.']);
            }

            $user->password = Hash::make($request->new_password);

            // Only set temp_password if the column exists
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'temp_password')) {
                $user->temp_password = $request->new_password;
            }

            $user->save();

            return back()->with('success', 'Password changed successfully. You can now login with your new password.');
        } catch (\Throwable $e) {
            \Log::error('Old password change failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
    }

    /** Alias for sendOtp for backward compatibility */
    public function sendOtp(Request $request)
    {
        return $this->handleOtp($request);
    }
}
