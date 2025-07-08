<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\JsonResponse;
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
        // $uploadDir = 'C:\xampp\htdocs\image-folder\public\uploads\\';
        $uploadDir = public_path('uploads\\');

        // dd($uploadDir);

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $image = $request->image;
        $imageName = time() . '_' . $image->getClientOriginalName();

        $imagePath = $uploadDir  . $imageName;
        $image->move($uploadDir, $imageName);

        $request->merge(['image' => $imagePath]);

        //$user = User::create($request->all());
        $user = User::create(
            array_merge(
                $request->except('image'),
                ['image' => $imagePath]
            )
        );
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    // public function storeUsers(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|string|min:8',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     // Handle image upload
    //     $imagePath = null;
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $imageName = time() . '_' . $image->getClientOriginalName();

    //         // Your desired directory path (file system path, not URL)
    //         $uploadDir = 'C:\xampp\htdocs\aire-skinstore\public\uploads';

    //         // Create directory if it doesn't exist
    //         if (!file_exists($uploadDir)) {
    //             mkdir($uploadDir, 0777, true);
    //         }

    //         // Move uploaded file to directory
    //         $image->move($uploadDir, $imageName);

    //         // Create the full URL path for database storage
    //         $imagePath = "http://localhost/aire-skinstore/public/uploads/" . $imageName;
    //     }

    //     // Create user
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'image' => $imagePath, // This will store the full URL
    //     ]);

    //     return response()->json([
    //         'message' => 'User created successfully',
    //         'user' => $user,
    //         'image_url' => $imagePath
    //     ], 201);
    // }

    public function getUsers()
    {
        // $users = User::orderBy('id', 'desc')->limit(10)->get(['id', 'name', 'email', 'image']);
        // $users = User::with('profiles')->get();
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
