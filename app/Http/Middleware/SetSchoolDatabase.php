<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SetSchoolDatabase
{
    public function handle(Request $request, Closure $next)
    {
        $school = $request->segment(2);

        DB::purge('mysql');
        Config::set('database.connections.mysql.database', 'central_db');
        DB::reconnect('mysql');

        $schoolRecord = DB::table('schools')->where('name', $school)->first();

        //dd($schoolRecord);

        if (!$schoolRecord) {
            return response()->json(['error' => 'Invalid school identifier'], 404);
        }

        Config::set('database.connections.school', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $schoolRecord->db_name,
            'username' => $schoolRecord->db_username,
            'password' => $schoolRecord->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        // Reconnect to the school DB
        try {
            DB::purge('school');
            DB::reconnect('school');
            DB::setDefaultConnection('school');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to connect to school DB', 'details' => $e->getMessage()], 500);
        }

        return $next($request);
    }

}

