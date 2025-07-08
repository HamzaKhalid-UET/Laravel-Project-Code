<?php



namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\user;
use App\Models\Userrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserRoleController extends Controller
{
    // public function storeUserRole(Request $request)
    public function storeUserRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $userRole = Userrole::create($request->all());
        if ($userRole) {
            return response()->json(['message' => 'User role created successfully', 'userRole' => $userRole], 200);
        } else {
            return response()->json(['message' => 'User role not created'], 400);
        }
    }

    public function getUserRole($id)
    {


        $userRole = user::find($id)->roles->map(function ($role) {
            return [
                'name' => $role->name,
            ];
        });
        // foreach ($userRole as $role) {
        //     $roles[] = $role->name;
        // }

        if ($userRole) {
            return response()->json(['message' => 'User role get successfully', 'userRole' => $userRole], 200);
        } else {
            return response()->json(['message' => 'User role not found'], 400);
        }


        //   return response()->json($userRole);
    }


    public function getRoleOfUser($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $role = Role::find($id)->users;

        $users = $role->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
            ];
        });

        if ($role) {
            return response()->json(['message' => 'users found', 'users' => $users], 200);
        } else {
            return response()->json(['message' => 'users not found'], 404);
        }
    }
}
