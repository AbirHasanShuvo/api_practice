<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'errors' => $validator->errors()
            ]);
        }

        //for uploading the image

        $imagePath = null;

        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/profile'), $filename);
            $imagePath = 'storage/profile/' . $filename;
        }

        //building user data 

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role ? $request->role : 'user',
            'profile_picture' => $imagePath,
        ];

        User::create($data);

        return response()->json([
            'status' => 'Success',
            'message' => 'User registered successfully'
        ]);
    }


    //login function will be here

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'errors' => $validator->errors()
            ]);
        }

        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password

        ])) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Invalid credentials'
            ]);
        }

        $user = Auth::user();

        //sanctum token generation

        $token = $user->createToken('Apitesting')->plainTextToken;

        return response()->json([
            'status' => 'Success',
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user

        ]);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'status' => 'Succes',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Logged out successfully'
        ]);
    }
}
