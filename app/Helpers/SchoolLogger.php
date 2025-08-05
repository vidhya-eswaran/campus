<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class SchoolLogger
{
    public static function log($message, $context = [])
    {
        $schoolName = Config::get('school.name', 'unknown_school');
        $schoolName = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($schoolName));
        $logFile = storage_path("logs/log_{$schoolName}.log");

        if (!File::exists($logFile)) {
            File::put($logFile, '');
        }

        Log::build([
            'driver' => 'single',
            'path' => $logFile,
        ])->info($message, $context);
    }

    public static function error($message, $context = [])
    {
        $schoolName = Config::get('school.name', 'unknown_school');
        $schoolName = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($schoolName));
        $logFile = storage_path("logs/log_{$schoolName}.log");

        if (!File::exists($logFile)) {
            File::put($logFile, '');
        }

        Log::build([
            'driver' => 'single',
            'path' => $logFile,
        ])->error($message, $context);
    }
}
