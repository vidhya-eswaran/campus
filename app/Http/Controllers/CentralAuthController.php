<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CentralAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $school = DB::table('schools')->where('id', $user->school_id)->first();

        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'school_db' => [
                'db_name' => $school->db_name,
                'db_username' => $school->db_username,
                'db_password' => $school->db_password,
                'db_host' => $school->db_host
            ]
        ]);
    }

}
