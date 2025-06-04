<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class dashboardController extends Controller
{
    public function count()
    {
$count=[];
        $count['countstudent'] = User::where('user_type', 'student')->count();
        $count['countsponser'] = User::where('user_type', 'sponser')->count();
$count['countstaff'] = User::whereNotIn('user_type', ['student', 'sponser'])->count();

         return response()->json($count);
    }
}
