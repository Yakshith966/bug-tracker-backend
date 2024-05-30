<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsersByRole(Request $request)
    {
        $roleName = $request->input('role');
        $users = User::join('roles', 'users.role_id', '=', 'roles.id')
                     ->where('roles.name', $roleName)
                     ->select('users.*')
                     ->get();
                    //  dd($users);

        return response()->json($users);
    }
}
