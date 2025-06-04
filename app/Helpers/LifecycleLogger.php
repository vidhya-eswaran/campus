<?php
namespace App\Helpers;

use App\Models\LifecycleLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
class LifecycleLogger
{
public static function log($heading, $student_id = null, $event_type = null, $extra = null)
{
    try {
        // Attempt to create the lifecycle log entry
        LifecycleLog::create([
            'heading'     => $heading,
            'student_id'  => $student_id,
            'event_type'  => $event_type,
            'extra'       => is_array($extra) ? json_encode($extra) : $extra,
            'logged_at'   => Carbon::now('Asia/Kolkata'), 
        ]);

        // Log success
        Log::info('Lifecycle log created successfully.', [
            'heading' => $heading,
            'student_id' => $student_id,
            'event_type' => $event_type,
        ]);
    } catch (Exception $e) {
        // Catch any errors and log them
        Log::error('Error creating lifecycle log:', [
            'error_message' => $e->getMessage(),
            'heading' => $heading,
            'student_id' => $student_id,
            'event_type' => $event_type,
        ]);
    }
}

}

