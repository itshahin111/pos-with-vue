<?php
namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use App\Mail\OtpMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use RateLimiter;

class UserController extends Controller
{
    function register()
    {
        return Inertia::render('RegistrationPage');
    }

    function userLogin()
    {
        return Inertia::render('LoginPage');
    }

    function forgotPassword()
    {
        return Inertia::render('SendOtpPage');
    }

    function verifyOtp()
    {
        return Inertia::render('VerifyOtpPage');
    }

    function profile()
    {
        return Inertia::render('ProfilePage');
    }

    function resetPasswordPage()
    {
        return Inertia::render('ResetPasswordPage');
    }

    function registration(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'mobile' => 'required|string|max:20',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => Hash::make($request->input('password')), // Hashing password properly
            ]);

            return redirect()->route('LoginPage')->with('success', 'User Registered Successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    function login(Request $request)
    {
        $email = (string) $request->input('email');
        $throttleKey = Str::lower($email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect()->back()->withErrors(['email' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.'])->withInput();
        }
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            RateLimiter::hit($throttleKey);
            return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }
        RateLimiter::clear($throttleKey);
        $request->session()->put('email', $user->email);
        $request->session()->put('user_id', $user->id);

        return redirect()->route('dashboardPage')->with('status', true)->with('message', 'Login Successfully');

    }
    function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('LoginPage');
    }

    public function sendOtpCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->input('email');
        $otp = rand(100000, 999999);
        $user = User::where('email', $email)->first();

        if ($user) {
            // Send OTP email
            Mail::to($email)->send(new OTPMail($otp));

            // Update user's OTP
            $user->update(['otp' => $otp]);

            // Store email in session
            $request->session()->put('email', $email);

            return redirect()->route('VerifyOtpPage')->with('status', true)->with('message', 'OTP Sent Successfully');
        }

        return redirect()->route('SendOtpPage')->with('status', false)->with('message', 'Email Not Found');
    }

    function verifyOtpCode(Request $request)
    {
        $email = $request->session()->get('email');

        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $email)->where('otp', $request->input('otp'))->first();

        if ($user) {
            $user->update(['otp' => '']);
            $request->session()->put('otp_verify', 'yes');

            return redirect()->route('ResetPasswordPage')->with('status', true)->with('message', 'OTP Verified Successfully');
        } else {
            return redirect()->route('VerifyOtpPage')->with('status', false)->with('message', 'Invalid OTP');
        }
    }

    function resetPassword(Request $request)
    {
        try {
            $email = $request->session()->get('email');
            $otp_verify = $request->session()->get('otp_verify');

            if ($otp_verify !== "yes") {
                return redirect()->route('ResetPasswordPage')->with('error', 'Invalid OTP verification');
            }

            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            User::where('email', $email)->update([
                'password' => Hash::make($request->input('password'))
            ]);

            $request->session()->flush();
            return redirect()->route('LoginPage')->with('status', true)->with('message', 'Password reset successfully!');
        } catch (Exception $e) {
            return redirect()->route('ResetPasswordPage')->with('status', false)->with('message', 'Something went wrong');
        }
    }

    function userProfile(Request $request)
    {
        $email = $request->header('email');
        $user = User::where('email', $email)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data' => $user
        ], 200);
    }

    function updateProfile(Request $request)
    {
        try {
            $email = $request->header('email');
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $password = $request->input('password');

            $updateData = [
                'name' => $name,
                'mobile' => $mobile,
            ];

            if ($password) {
                $updateData['password'] = Hash::make($password);
            }

            User::where('email', $email)->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
