<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid password'], 401);
        }

       
        $token = Helper::createToken($user);
        $data = [
            "name" => $user->name,
            "email" => $user->email,
            "token" => $token,
        ];
        return response()->json(['message' => 'Login successful', 'data' => $data], 200);
    }
    public function upoladImages(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $image = $request->file('image');
        $imageName = "user_" . time() . '.' . $image->getClientOriginalExtension();
        $imagePath = config('BASE_APP_URL') . 'public/uploads/' . $imageName;
        $image->move($imagePath);
        return response()->json(['message' => 'Image uploaded successfully', 'image' => $imageName, 'imagePath' => $imagePath], 200);
    }
}
