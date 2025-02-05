<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use App\Mail\OtpMail;
use App\Helper\JwtToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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

    function profile(Request $request)
    {
        return Inertia::render('ProfilePage');
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
                'password' => Hash::make($request->input('password')),
            ]);

            return redirect()->route('user-login')->with('success', 'User Registered Successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
    function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            $request->session()->put('email', $user->email);
            $request->session()->put('user_id', $user->id);

            return redirect()->route('dashboardPage')->with('success', 'Login successful!');
        } else {
            return redirect()->route('LoginPage')->with('error', 'Invalid email or password.');
        }
    }

    function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('LoginPage');
    }
    function sendOtpCode(Request $request)
    {

        $email = $request->input('email');
        $otp = rand(100000, 999999);
        $user = User::where('email', $email)->first();
        if ($user == 1) {
            Mail::to($email)->send(new OtpMail($otp));
            User::where('email', '=', $email)->update(['otp' => $otp]);
            return response()->json([
                'status' => 'success',
                'message' => '6 Digit {$otp}Code has been send to your email !',
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Email not found',
            ], 404);
        }

    }

    function verifyOtpCode(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $user = User::where('email', '=', $email)
            ->where('otp', '=', $otp)->count();

        if ($user == 1) {
            User::where('email', '=', $email)->update(['otp' => '0']);
            $token = JwtToken::createPasswordResetToken($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Successful',
                'token' => $token
            ], 200)->cookie('token', $token, 60 * 24 * 30);

        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ], 200);
        }
    }


    function resetPassword(Request $request)
    {

        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', '=', $email)->update(['password' => $password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ], 200);
        }

    }


    function userProfile(Request $request)
    {
        $email = $request->header('email');
        $user = User::where('email', '=', $email)->first();
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
            User::where('email', '=', $email)->update([
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'password' => $password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ], 200);
        }
    }

}
