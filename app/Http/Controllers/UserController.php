<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\JwtToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            ], 200)->cookie('token', $token, 60 * 24 * 30, null, null, true, true);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ], 401);
        }
    }
    
    function logout(Request $request){
        return redirect('/')->cookie('token','',-1);
    }

}
