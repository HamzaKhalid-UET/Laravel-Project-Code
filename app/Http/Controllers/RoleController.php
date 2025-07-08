<?php



namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $role = Role::create($request->all());
        return response()->json($role);
    }
}