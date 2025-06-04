<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class studentfeesController extends Controller
{
    public function studentfees()
    {
        $user = Auth::user();
    }
}
