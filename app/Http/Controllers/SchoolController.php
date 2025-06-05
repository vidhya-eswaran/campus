<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class SchoolController extends Controller
{
    public function createSchool(Request $request)
    {
        try {
        $request->validate([
            'name' => 'required|unique:schools,name',
            'db_name' => 'required|unique:schools,db_name',
            'admin_name' => 'required|string',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6',
        ]);
        
        $schoolName = $request->name;
        $dbName = $request->db_name;
        $adminName = $request->admin_name;
        $adminEmail = $request->admin_email;
        $adminPassword = bcrypt($request->admin_password);

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

        
        $mysqlPath = '/usr/bin/mysql'; // full path
        $dbUser = 'root';
        $dbPass = ''; // leave blank if no password

        $command = "\"$mysqlPath\" -u $dbUser " . ($dbPass ? "-p$dbPass " : "") . "$dbName < \"$schemaPath\"";

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = "cmd /c \"$command\"";
        }

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return response()->json([
                'error' => 'Failed to import schema into the new database.',
                'command' => $command,
                'result_code' => $resultCode,
                'output' => $output,
            ], 500);
        }

        // Step 3: Save to central_db
        $schoolId = DB::table('schools')->insert([
            'name' => $schoolName,
            'db_name' => $dbName,
            'db_username' => 'root',
            'db_password' => env('DB_PASSWORD', ''),
            'db_host' => '127.0.0.1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

         // Step 4: Insert admin user into central users table
        DB::table('users')->insert([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => $adminPassword,
            'school_id' => $schoolId,
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
}
