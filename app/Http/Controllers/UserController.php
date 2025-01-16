<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use Exception;
use App\Models\User;
use App\Helper\JwtToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

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
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $email = $request->input('email');
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $password = Hash::make($request->input('password')); // Hash the password


            User::create([
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'password' => $password,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
        }
    }


    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            $token = JwtToken::createTokenForUser($request->input('email'), $user->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful',
                'token' => $token
            ], 200)->cookie('token', $token, time() + 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ], 401);
        }
    }

    function logout(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'User Logout Successfully',
        ], 200)->cookie('token', '', -1);
        // return redirect('/')->cookie('token','',-1);

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
