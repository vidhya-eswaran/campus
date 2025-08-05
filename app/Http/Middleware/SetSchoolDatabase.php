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
        $school = $request->route('school');
        DB::purge('mysql');
        Config::set('database.connections.mysql.database', 'central_db');
        DB::reconnect('mysql');

        $schoolRecord = DB::table('schools')->where('name', $school)->first();

        //dd($schoolRecord);

        if (!$schoolRecord) {
            return response()->json(['error' => 'Invalid school identifier'], 404);
        }

        // Set all relevant configuration values
        Config::set('school.id', $schoolRecord->id);
        Config::set('school.name', $schoolRecord->name);
        Config::set('school.school_logo', $schoolRecord->school_logo);
        Config::set('school.school_type', $schoolRecord->school_type);
        Config::set('school.school_category', $schoolRecord->school_category);
        Config::set('school.established_year', $schoolRecord->established_year);
        Config::set('school.website_url', $schoolRecord->website_url);
        Config::set('school.country', $schoolRecord->country);
        Config::set('school.state', $schoolRecord->state);
        Config::set('school.city', $schoolRecord->city);
        Config::set('school.postal_code', $schoolRecord->postal_code);
        Config::set('school.full_address', $schoolRecord->full_address);
        Config::set('school.phone_number', $schoolRecord->phone_number);
        Config::set('school.alternate_phone_number', $schoolRecord->alternate_phone_number);
        Config::set('school.email_address', $schoolRecord->email_address);
        Config::set('school.support_email', $schoolRecord->support_email);
        Config::set('school.admin_full_name', $schoolRecord->admin_full_name);
        Config::set('school.admin_email', $schoolRecord->admin_email);
        Config::set('school.admin_phone', $schoolRecord->admin_phone);
        Config::set('school.selected_plan', $schoolRecord->selected_plan);
        Config::set('school.subscription_start_date', $schoolRecord->subscription_start_date);
        Config::set('school.subscription_end_date', $schoolRecord->subscription_end_date);
        Config::set('school.payment_method', $schoolRecord->payment_method);

        Config::set('razorpay.key', $schoolRecord->razorpay_key);
        Config::set('razorpay.secret', $schoolRecord->razorpay_secret);
        Config::set('razorpay.webhook_secret', $schoolRecord->razorpay_webhook_secret);

        Config::set('database.connections.school', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $schoolRecord->db_name,
            'username' => 'root', // use root for now
            'password' => '',     // blank password
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
