<?php

namespace App\Helpers;

class HelperEmail
{
    public static function getEmailTemplate($module, $purpose = null)
    {
        $jsonPath = resource_path('json/email_templates.json');

        if (!file_exists($jsonPath)) {
            return null;
        }

        $jsonData = json_decode(file_get_contents($jsonPath), true);

        foreach ($jsonData as $template) {
            if ($template['module'] === $module) {
                if ($purpose === null || $template['purpose'] === $purpose) {
                    return $template;
                }
            }
        }

        return null;
    }

    // Optional: replace placeholders with actual values
    public static function parseTemplate($templateBody, $variables = [])
    {
        foreach ($variables as $key => $value) {
            $templateBody = str_replace('{{' . $key . '}}', $value, $templateBody);
        }
        return $templateBody;
    }
}
