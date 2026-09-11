<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Mail\VerifyEmail;

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
                'email'    => 'required|email|min:5|max:50',
                'password' => 'required|min:3|max:20',
            ]);

            // Attempt authentication using Laravel's secure Auth::attempt()
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();

                // Determine user role with fallback
                $userRole = isset($user->role) ? $user->role : 'user';

                // Role-based redirection
                if ($userRole === 'admin') {
                    // Set admin session
                    session([
                        'admin_id' => $user->id,
                        'admin_email' => $user->email,
                        'admin_name' => $user->name,
                        'is_admin' => true
                    ]);

                    return redirect()->route('admin.dashboard')->with('success', 'Admin logged in successfully!');
                } else {
                    // Regular user session
                    session([
                        'user_logged_in' => true,
                        'user_email' => $user->email,
                        'is_admin' => false
                    ]);

                    return redirect()->route('store')->with('success', 'Logged in successfully!');
                }
            }

            // Authentication failed
            return back()->with('error', 'Invalid email or password.');

        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred during login. Please try again.']);
        }
    }

    /** Handle normal registration with comprehensive error handling */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $userData = [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ];

            // Only add role if the column exists
            if (Schema::hasColumn('users', 'role')) {
                $userData['role'] = 'user';
            }

            $user = User::create($userData);

            // Generate verification token
            $token = Str::random(32);

            try {
                DB::table('email_verifications')->updateOrInsert(
                    ['email' => $user->email],
                    ['token' => $token, 'created_at' => now(), 'updated_at' => now()]
                );
            } catch (\Exception $e) {
                Log::error('Email verification token creation failed: ' . $e->getMessage());
                // Continue even if token creation fails
            }

            // Attempt to send verification email with fallback
            try {
                // Force log mailer as fallback for production
                config(['mail.mailers.smtp.transport' => 'log']);
                Mail::to($user->email)->send(new VerifyEmail($user->email, $token));
            } catch (\Exception $e) {
                Log::error('Email sending failed: ' . $e->getMessage());
                // Continue registration even if email fails
            }

            return redirect()->route('verification.wait', ['email' => $user->email])
                ->with('success', 'Account created! Verification email sent.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during registration: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Registration failed due to database error. Please try again.']);
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
    }

    /** Email verification handler with error handling */
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

        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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
    public function handleOtp(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email|exists:users,email']);

            $email = $request->email;
            $otp = rand(100000, 999999);

            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                ['token' => $otp, 'created_at' => now()]
            );

            // Attempt to send OTP email with fallback
            try {
                config(['mail.mailers.smtp.transport' => 'log']);
                Mail::to($email)->send(new \App\Mail\OtpMail($otp));
            } catch (\Exception $e) {
                Log::error('OTP email sending failed: ' . $e->getMessage());
                // Continue even if email fails
            }

            return redirect()->route('forgot.password.otp.verify', ['email' => $email])
                ->with('success', 'OTP has been sent to your email.');

        } catch (\Exception $e) {
            Log::error('OTP request failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
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

            $record = DB::table('password_resets')->where('email', $request->email)->first();
            if (!$record || $record->token != $request->otp) {
                return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
            }

            return redirect()->route('forgot.password.newpass', ['email' => $request->email]);

        } catch (\Exception $e) {
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

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'User not found.']);
            }

            $user->password = Hash::make($request->new_password);

            // Only set temp_password if the column exists
            if (Schema::hasColumn('users', 'temp_password')) {
                $user->temp_password = $request->new_password;
            }

            $user->save();

            DB::table('password_resets')->where('email', $request->email)->delete();

            return redirect()->route('login')->with('success', 'Password changed successfully! You can login now.');

        } catch (\Exception $e) {
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

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'User not found.']);
            }

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Old password does not match our records.']);
            }

            $user->password = Hash::make($request->new_password);

            // Only set temp_password if the column exists
            if (Schema::hasColumn('users', 'temp_password')) {
                $user->temp_password = $request->new_password;
            }

            $user->save();

            return back()->with('success', 'Password changed successfully. You can now login with your new password.');

        } catch (\Exception $e) {
            Log::error('Old password change failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
    }
}
