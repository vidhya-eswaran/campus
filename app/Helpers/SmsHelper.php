<?php
namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsHelper
{
    /**
     * Send SMS by template name and single mobile number
     *
     * @param string $templateName  Template name in sms_templates.json
     * @param string $mobileNumber  Mobile number to send SMS
     * @param array $variables      Optional variables to replace in content
     */
    public static function sendTemplateSms($templateName, $mobileNumber, $variables = [])
    {
        try {
            if (!$mobileNumber) return;

            // Load SMS templates
            $filePath = resource_path('json/sms_templates.json');
            if (!file_exists($filePath)) {
                Log::channel('sms')->error("SMS template file missing.");
                return;
            }

            $templates = json_decode(File::get($filePath), true);

            // Find matching template
            $template = collect($templates)->first(function ($tpl) use ($templateName) {
                return isset($tpl['template_name']) && $tpl['template_name'] === $templateName;
            });

            if (!$template || !isset($template['content'])) {
                Log::channel('sms')->error("SMS template not found: {$templateName}");
                return;
            }

            // Replace variables in content
            $message = $template['content'];
            foreach ($variables as $key => $value) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }

            // Send SMS via API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic YOUR_AUTH_STRING' // change this
            ])->post('https://restapi.smscountry.com/v0.1/Accounts/1zgB8GtvL6nCGfZxJJgQ/SMSes/', [
                "Text" => $message,
                "Number" => $mobileNumber,
                "SenderId" => "SVHSTL",
                "DRNotifyUrl" => "https://yourdomain.com/sms-callback",
                "DRNotifyHttpMethod" => "POST",
                "Tool" => "API",
            ]);

            if ($response->failed()) {
                Log::channel('sms')->error("Failed to send SMS to {$mobileNumber} | Status: {$response->status()} | Body: " . $response->body());
            } else {
                Log::channel('sms')->info("SMS sent to {$mobileNumber} using template {$templateName}");
            }

        } catch (\Exception $e) {
            Log::channel('sms')->error("SMS Error: {$e->getMessage()} | Template: {$templateName}");
        }
    }
}

