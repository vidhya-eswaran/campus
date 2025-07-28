<?php 

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class RazorpayService
{
    public function configureRazorpayForSchool($schoolSlug)
    {
        $school = DB::connection('central')->table('schools')->where('name', $schoolSlug)->first();

        if (!$school) {
            throw new \Exception("Invalid school");
        }

        

        // Set Razorpay credentials dynamically
        config([
            'razorpay.key' => $school->razorpay_key,
            'razorpay.secret' => $school->razorpay_secret,
        ]);

        \Log::info('Razorpay Key: ' . config('razorpay.key'));
        \Log::info('Razorpay Secret: ' . config('razorpay.secret'));

        //dd($school);

        // Switch to the tenant DB (if you're using per-school databases)
        DB::purge('tenant');
        config(['database.connections.tenant.database' => $school->db_name]);
        DB::reconnect('tenant');
        DB::setDefaultConnection('tenant');
    }
}
