<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Profiles;
use App\Models\user;
use Illuminate\Http\Request;
// use App\Models\Profiles;
// use App\Models\Profiles;
// use Profile as GlobalProfile;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{


    public function storeProfile(Request $request)
    {

        $existingProfile = Profiles::where('user_id', $request->user_id)->first();
        // dd($existingProfile);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'image' => 'required|file|image|max:2048',
            'address' => 'required|unique:profiles,address',
            'phone' => 'required|unique:profiles,phone',
            'gender' => 'required',
            'dob' => 'required|date|unique:profiles,dob',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }


        $uploadDir = public_path('uploads/');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move($uploadDir, $imageName);
        $imagePath = 'uploads/' . $imageName;

        // dd($imagePath);
        if ($existingProfile) {
            $existingProfile->update($request->all([
                'user_id' => $request->user_id,
                'image' => $imagePath,
                'address' => $request->address,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'dob' => $request->dob,
            ]));
            return response()->json(['message' => 'Profile updated successfully', 'profile' => $existingProfile], 200);
        } else {

            $profile = Profiles::create([
                'user_id' => $request->user_id,
                'image' => $imagePath,
                'address' => $request->address,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'dob' => $request->dob,
            ]);
            // dd($profile);

            if ($profile) {
                return response()->json(['message' => 'Profile created successfully', 'profile' => $profile], 200);
            } else {
                return response()->json(['message' => 'Profile not created'], 400);
            }
        }
    }

    public function getProfile()
    {
        $profile = Profiles::all();
        if ($profile) {
            return response()->json(['profile' => $profile], 200);
        } else {
            return response()->json(['message' => 'Profile not found'], 404);
        }
    }

    public function getProfileByUserId($id)
    {

        $profile = Profiles::where('id', $id)->first();
        // dd($profile);
        if ($profile) {
            return response()->json(['profile' => $profile], 200);
        } else {
            return response()->json(['message' => 'Profile not found'], 404);
        }
    }

    public function deleteProfile($id)
    {
        $profile = Profiles::where('id', $id)->first();
        // dd($profile);
        if (!$profile) {
            dd('not found');
            return response()->json(['message' => 'Profile not found'], 404);
        } else {
            $profile = Profiles::where('id', $id)->delete();
            return response()->json(['message' => 'Profile deleted successfully'], 200);
        }
    }

    public function userProfile($id)
    {
        $user = User::find($id)->profiles;
        // dd($user);
        if (!$user) {
            return response()->json(['message' => 'Profile of this user not found '.$id], 200);
        }
        return response()->json(['user' => $user], 200);
    }
}
