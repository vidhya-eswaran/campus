<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SetSchoolDatabase
{
    public function handle(Request $request, Closure $next)
{
    $school = $request->route('school');
    Log::info("🔍 [Middleware] Incoming school route param: {$school}");

    // Step 1: Switch to central DB
    try {
        DB::purge('mysql');
        Config::set('database.connections.mysql.database', 'central_db');
        DB::reconnect('mysql');
        Log::info("✅ [Middleware] Connected to central database.");
    } catch (\Exception $e) {
        Log::error("❌ [Middleware] Failed to connect to central DB", ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Could not connect to central DB', 'details' => $e->getMessage()], 500);
    }

    // Step 2: Find school
    $schoolRecord = DB::table('schools')->where('name', $school)->first();
    if (!$schoolRecord) {
        Log::warning("❌ [Middleware] School '{$school}' not found in central DB.");
        return response()->json(['error' => 'Invalid school identifier'], 404);
    }

    Log::info("✅ [Middleware] School found: {$schoolRecord->name} (ID: {$schoolRecord->id})");

    // Step 3: Set Razorpay and School Configuration
    Config::set('razorpay.key', $schoolRecord->razorpay_key ?? null);
    Config::set('razorpay.secret', $schoolRecord->razorpay_secret ?? null);
    Config::set('razorpay.webhook_secret', $schoolRecord->razorpay_webhook_secret ?? null);

    Config::set('school.id', $schoolRecord->id ?? null);
    Config::set('school.name', $schoolRecord->name ?? null);
    Config::set('school.school_logo', $schoolRecord->school_logo ?? null);
    Config::set('school.school_type', $schoolRecord->school_type ?? null);
    Config::set('school.school_category', $schoolRecord->school_category ?? null);
    Config::set('school.established_year', $schoolRecord->established_year ?? null);
    Config::set('school.website_url', $schoolRecord->website_url ?? null);
    Config::set('school.country', $schoolRecord->country ?? null);
    Config::set('school.state', $schoolRecord->state ?? null);
    Config::set('school.city', $schoolRecord->city ?? null);
    Config::set('school.postal_code', $schoolRecord->postal_code ?? null);
    Config::set('school.full_address', $schoolRecord->full_address ?? null);
    Config::set('school.phone_number', $schoolRecord->phone_number ?? null);
    Config::set('school.alternate_phone_number', $schoolRecord->alternate_phone_number ?? null);
    Config::set('school.email_address', $schoolRecord->email_address ?? null);
    Config::set('school.support_email', $schoolRecord->support_email ?? null);
    Config::set('school.admin_full_name', $schoolRecord->admin_full_name ?? null);
    Config::set('school.admin_email', $schoolRecord->admin_email ?? null);
    Config::set('school.admin_phone', $schoolRecord->admin_phone ?? null);
    Config::set('school.selected_plan', $schoolRecord->selected_plan ?? null);
    Config::set('school.subscription_start_date', $schoolRecord->subscription_start_date ?? null);
    Config::set('school.subscription_end_date', $schoolRecord->subscription_end_date ?? null);
    Config::set('school.payment_method', $schoolRecord->payment_method ?? null);

    // Step 4: Connect to the school's DB
    Config::set('database.connections.school', [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => $schoolRecord->db_name,
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ]);

    try {
        DB::purge('school');
        DB::reconnect('school');
        DB::setDefaultConnection('school');
        Log::info("✅ [Middleware] Connected to school DB: {$schoolRecord->db_name}");
    } catch (\Exception $e) {
        Log::error("❌ [Middleware] Failed to connect to school DB", ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Failed to connect to school DB', 'details' => $e->getMessage()], 500);
    }

    return $next($request);
}
    // public function handle(Request $request, Closure $next)
    // {
    //     $school = $request->route('school');
    //     DB::purge('mysql');
    //     Config::set('database.connections.mysql.database', 'central_db');
    //     DB::reconnect('mysql');

    //     $schoolRecord = DB::table('schools')->where('name', $school)->first();

    //     //dd($schoolRecord);

    //     if (!$schoolRecord) {
    //         return response()->json(['error' => 'Invalid school identifier'], 404);
    //     }

    //     Config::set('database.connections.school', [
    //         'driver' => 'mysql',
    //         'host' => env('DB_HOST', '127.0.0.1'),
    //         'port' => env('DB_PORT', '3306'),
    //         'database' => $schoolRecord->db_name,
    //         'username' => 'root', // use root for now
    //         'password' => '',     // blank password
    //         'charset' => 'utf8mb4',
    //         'collation' => 'utf8mb4_unicode_ci',
    //         'prefix' => '',
    //         'strict' => true,
    //         'engine' => null,
    //     ]);

    //     // Reconnect to the school DB
    //     try {
    //         DB::purge('school');
    //         DB::reconnect('school');
    //         DB::setDefaultConnection('school');
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Failed to connect to school DB', 'details' => $e->getMessage()], 500);
    //     }

    //     return $next($request);
    // }

}

