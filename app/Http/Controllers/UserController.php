<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function storeUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $password = Hash::make($request->password);
        $request->merge(['password' => $password]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create($request->all());
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function getUsers()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }
        return response()->json(['message' => 'Users fetched successfully', 'users' => $users], 200);
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => "User not found against this $id"], 404);
        }
        return response()->json(['message' => 'User fetched successfully', 'user' => $user], 200);
    }

    // public function updateUser(Request $request, $id)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     if ($validator->fails()) { 
    //     return response()->json(['error' => $validator->errors()], 422);

    //     $user = User::find($id);
    //     if (!$user) {
    //         return response()->json(['message' => "User not found against this $id"], 404);
    //     }
    //     $user->update($request->all());
    //     return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    // }
    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => "User not found against this $id"], 404);
        }

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User updated successfully',
            'user'    => $user,
        ], 200);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => "User not found against this $id"], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
