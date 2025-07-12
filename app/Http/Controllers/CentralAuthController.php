<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CentralAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('SchoolAdminToken')->accessToken;

        if ($user->role !== 'super_admin') {

            $school = DB::table('schools')->where('id', $user->school_id)->first();

            if (!$school) {
                return response()->json(['error' => 'School not found'], 404);
            }

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'school_db' => [
                    'name' => $school->name,
                    'school' => $school->school,
                ]
            ]);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'school_db' => null
        ]);
    }

}
