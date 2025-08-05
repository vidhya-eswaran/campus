<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class SchoolController extends Controller
{
    public function createSchool(Request $request)
    {
        try {
        
            $request->validate([
                'name' => 'required|unique:schools,name',
                'admin_name' => 'required|string',
                'admin_email' => 'required|email|unique:users,email',
                'school' => 'nullable',
                'school_logo' => 'nullable',
                'school_type' => 'nullable|in:Public,Private,International',
                'school_category' => 'nullable|in:Primary,Secondary,Higher Secondary,University',
                'established_year' => 'nullable|date_format:Y',
                'website_url' => 'nullable|url',
                'country' => 'nullable|string',
                'state' => 'nullable|string',
                'city' => 'nullable|string',
                'postal_code' => 'nullable|string',
                'full_address' => 'nullable|string',
                'phone_number' => 'nullable|string',
                'alternate_phone_number' => 'nullable|string',
                'support_email' => 'nullable|email',
                'selected_plan' => 'nullable|in:Basic,Premium,Enterprise',
                'subscription_start_date' => 'nullable|date',
                'subscription_end_date' => 'nullable|date|after_or_equal:subscription_start_date',
                'payment_method' => 'nullable|in:Card,UPI,Bank Transfer',
                'razorpay_key' => 'nullable|string',
                'razorpay_secret' => 'nullable|string',
                'razorpay_webhook_secret' => 'nullable|string',
            ]);

            $schoolSlug = request()->route('school');
           
            if ($request->hasFile('school_logo')) {
                $file = $request->file('school_logo');
                $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

               $path = 'documents/' . $schoolSlug . '/school_logo/' . $filename;        

                // Upload to S3
                Storage::disk('s3')->put($path, file_get_contents($file));

                // Get public URL
                $school_logo = Storage::disk('s3')->url($path);

            }
       
        $schoolName = $request->name;
        $adminName = $request->admin_name;
        $adminEmail = $request->admin_email;

        $today = now()->format('Ymd');
        $schoolName = Str::slug($request->name, '_'); // e.g. santhosh_vidhyalaya
        $dbName = $schoolName . '_' . $today;
        $adminPasswordRaw = $schoolName . '_' . $today;
        $adminPassword = bcrypt($adminPasswordRaw);

        // Check if db_name already exists
        if (DB::table('schools')->where('db_name', $dbName)->exists()) {
            return response()->json([
                'status' => false,
                'message' => "Database name already exists for today."
            ]);
        }

        //Step 1: Create new database
        try {
            DB::statement("CREATE DATABASE `$dbName`");
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create database: ' . $e->getMessage()], 500);
        }

        // Step 2: Import schema.sql into the new database
        $schemaPath = base_path('schema.sql');
        
        if (!File::exists($schemaPath)) {
            return response()->json(['error' => 'schema.sql file not found.'], 500);
        }

        $mysqlPath = '/usr/bin/mysql';
        $dbUser = 'root';
        $dbPass = ''; // leave blank if no password
        $schemaPath = '/var/www/html/schema.sql';

        $command = "sudo $mysqlPath -u $dbUser " . ($dbPass ? "-p$dbPass " : "") . "$dbName < \"$schemaPath\"";

        // Capture stderr too
        $output = [];
        exec($command . ' 2>&1', $output, $resultCode);

        if ($resultCode !== 0) {
            return response()->json([
                'error' => 'Failed to import schema into the new database.',
                'command' => $command,
                'result_code' => $resultCode,
                'output' => $output,
            ]);
        }


        // Insert into schools and get the inserted ID
        $schoolId = DB::table('schools')->insertGetId([
            'name' => $schoolName,
            'school' => $request->name,
            'db_name' => $dbName,
            'db_username' => 'root',
            'db_password' => env('DB_PASSWORD', ''),
            'db_host' => '127.0.0.1',
            
            'school_logo' => $school_logo,
            'school_type' => $request->school_type,
            'school_category' => $request->school_category,
            'established_year' => $request->established_year,
            'website_url' => $request->website_url,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'full_address' => $request->full_address,
            'phone_number' => $request->phone_number,
            'alternate_phone_number' => $request->alternate_phone_number,
            'email_address' => $request->email_address,
            'support_email' => $request->support_email,
            'selected_plan' => $request->selected_plan,
            'subscription_start_date' => $request->subscription_start_date,
            'subscription_end_date' => $request->subscription_end_date,
            'payment_method' => $request->payment_method,
            'razorpay_key' => $request->razorpay_key,
            'razorpay_secret' => $request->razorpay_secret,
            'razorpay_webhook_secret' => $request->razorpay_webhook_secret,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert admin user with the retrieved school_id
        DB::table('users')->insert([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => $adminPassword,
            'school_id' => $schoolId,  // use the ID here
            'role' => 'school_admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);


        return response()->json(['message' => 'School created successfully and database initialized.']);
        } catch (ValidationException $e) {
            \Log::error('Validation failed', $e->errors());
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }


    public function updateSchool(Request $request, $id)
    {
        $school = DB::table('schools')->where('id', $id)->first();

        if (!$school) {
            return response()->json(['message' => 'School not found'], 404);
        }

        //dd($request->admin_name);

        $request->validate([
            //'admin_name' => 'nullable|string',
            'school_logo' => 'nullable',
            'school_type' => 'nullable|in:Public,Private,International',
            'school_category' => 'nullable|in:Primary,Secondary,Higher Secondary,University',
            'established_year' => 'nullable|digits:4',
            'website_url' => 'nullable|url',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'full_address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'alternate_phone_number' => 'nullable|string',
           // 'email' => 'nullable|email',
            'support_email' => 'nullable|email',
            'selected_plan' => 'nullable|in:Basic,Premium,Enterprise',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date|after_or_equal:subscription_start_date',
            'payment_method' => 'nullable|in:Card,UPI,Bank Transfer',
            'razorpay_key' => 'nullable|string',
            'razorpay_secret' => 'nullable|string',
            'razorpay_webhook_secret' => 'nullable|string',
        ]);

        $schoolSlug = request()->route('school');

        if ($request->hasFile('school_logo')) {
                $file = $request->file('school_logo');
                $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

               $path = 'documents/' . $schoolSlug . '/school_logo/' . $filename;         

                // Upload to S3
                Storage::disk('s3')->put($path, file_get_contents($file));

                // Get public URL
                $school_logo = Storage::disk('s3')->url($path);

            }
            //dd($school_logo);
        DB::table('schools')->where('id', $id)->update([
           // 'admin_name' => $request->admin_name ?? $school->admin_name,
            'school_logo' => $request->hasFile('school_logo') ? $school_logo : $school->school_logo,
            'school_type' => $request->school_type ?? $school->school_type,
            'school_category' => $request->school_category ?? $school->school_category,
            'established_year' => $request->established_year ?? $school->established_year,
            'website_url' => $request->website_url ?? $school->website_url,
            'country' => $request->country ?? $school->country,
            'state' => $request->state ?? $school->state,
            'city' => $request->city ?? $school->city,
            'postal_code' => $request->postal_code ?? $school->postal_code,
            'full_address' => $request->full_address ?? $school->full_address,
            'phone_number' => $request->filled('phone_number') ? $request->phone_number : $school->phone_number,
            'alternate_phone_number' => $request->filled('alternate_phone_number') ? $request->alternate_phone_number : $school->alternate_phone_number,
           // 'email' => $request->email ?? $school->email,
            'support_email' => $request->support_email ?? $school->support_email,
            'selected_plan' => $request->selected_plan ?? $school->selected_plan,
            'subscription_start_date' => $request->subscription_start_date ?? $school->subscription_start_date,
            'subscription_end_date' => $request->subscription_end_date ?? $school->subscription_end_date,
            'payment_method' => $request->payment_method ?? $school->payment_method,
              'razorpay_key' => $request->razorpay_key ?? $school->razorpay_key,
            'razorpay_secret' => $request->razorpay_secret ?? $school->razorpay_secret,
            'razorpay_webhook_secret' => $request->razorpay_webhook_secret ?? $school->razorpay_webhook_secret,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'School updated successfully']);
    }

    public function viewSchool(Request $request, $id)
    {
        $id = $request->id;
        $school = DB::table('schools')->where('id', $id)->first();

        if (!$school) {
            return response()->json(['message' => 'School not found'], 404);
        }

        $schoolData = (array) $school;

        // Fetch users related to the school
        $users = DB::table('users')->where('school_id', $id)->select('name', 'email')->get();

        // Add users as a nested property
        $schoolData['users'] = $users;

        return response()->json(['school' => $schoolData]);
    }

    public function getSchool()
    {
        $schools = DB::table('schools')->get();

        if ($schools->isEmpty()) {
            return response()->json(['message' => 'No schools found'], 404);
        }

        // Convert to array and map users to each school
        $schoolData = $schools->map(function ($school) {
            $users = DB::table('users')
                ->where('school_id', $school->id)
                ->select('name', 'email')
                ->get();

            $school->users = $users;
            return $school;
        });

        return response()->json(['schools' => $schoolData]);
    }



}
