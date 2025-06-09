<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\TemplateEditor;
use App\Models\Student;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \DateTime;
use Illuminate\Support\Str;
class TemplateEditorController extends Controller
{
    // Fetch all templates
    public function index()
    {
        return response()->json(TemplateEditor::all());
    }

    public function viewById($id)
    {
        // Find the template by ID
        $template = TemplateEditor::find($id);

        // Check if template exists
        if ($template) {
            // Prepare raw HTML + CSS content
            $htmlContent = $template->template;

            // Return as HTML with proper content type
            return response($htmlContent, 200)->header(
                "Content-Type",
                "text/html; charset=UTF-8"
            );
        }

        // Return 404 if template is not found
        return response()->json(["message" => "Template not found"], 404);
    }

    public function bonafideView(Request $request)
    {
        $template = TemplateEditor::where(
            "template_name",
            $request->template_name
        )->first();
        if (!$template) {
            return response()->json(["message" => "Template not found"], 404);
        }

        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json(["message" => "Student not found"], 404);
        }
        $data = [
            "student_name" => $student->STUDENT_NAME,
            "father_or_mother_name" => $student->FATHER,
            "class" => $student->SOUGHT_STD,
            "academic_year" => $student->academic_year,
            "date_of_birth_numeric" => $student->DOB_DD_MM_YYYY,
            "date_of_birth_words" => $this->convertDateToWords(
                $student->DOB_DD_MM_YYYY
            ),
            "admission_date" => $student->date_form,
            "st" => now()->format("d-m-Y"),
        ];

        $url = $this->generateCertificate(
            $student,
            $template,
            $data,
            "Bonafide Certificate",
            "bonafide_certificate"
        );

        return response()->json(["url" => $url]);
    }
    public function courseCompletion(Request $request)
    {
        $template = TemplateEditor::where(
            "template_name",
            $request->template_name
        )->first();
        if (!$template) {
            return response()->json(["message" => "Template not found"], 404);
        }

        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json(["message" => "Student not found"], 404);
        }

        $data = [
            "student_name" => $student->STUDENT_NAME,
            "parent_name" => $student->FATHER,
            "start_date" => $student->date_form,
            "end_date" => \Carbon\Carbon::today()->toDateString(),
            "class_name" => $student->SOUGHT_STD,
            "completed_class" => $student->SOUGHT_STD,
        ];

        // Replace placeholders in the HTML content

        // Generate the certificate (assuming this function handles PDF creation and returns URL)
        $url = $this->generateCertificate(
            $student,
            $template,
            $data,
            "Course Completion Certificate",
            "certificate"
        );

        return response()->json(["url" => $url], 200);
    }
    // public function idcard(Request $request)
    // {
    //     $template = TemplateEditor::where('template_name', $request->template_name)->first();
    //     if (!$template) {
    //         return response()->json(['message' => 'Template not found'], 404);
    //     }

    //     $student = Student::find($request->student_id);
    //     if (!$student) {
    //         return response()->json(['message' => 'Student not found'], 404);
    //     }
    //     // In your Controller or Blade

    // $profilePhoto = $student->profile_photo; // e.g. "profile740.png"
    // $photoUrl = env('APP_URL') . '/storage/app/profile_photos/' . $profilePhoto;

    //     $data = [
    //         'student_name'            => $student->STUDENT_NAME,
    //         'student_photo'            =>$photoUrl,
    //         'DOB_DD_MM_YYYY'          => $student->DOB_DD_MM_YYYY,
    //         'SOUGHT_STD'              => $student->SOUGHT_STD,
    //         'MOBILE_NUMBER'           => $student->MOBILE_NUMBER,
    //         'academic_year'           => $student->academic_year,

    //         // Permanent address
    //         'PERMANENT_HOUSENUMBER'   => $student->PERMANENT_HOUSENUMBER,
    //         'P_STREETNAME'            => $student->P_STREETNAME,
    //         'P_VILLAGE_TOWN_NAME'     => $student->P_VILLAGE_TOWN_NAME,
    //         'P_DISTRICT'              => $student->P_DISTRICT,
    //         'P_STATE'                 => $student->P_STATE,
    //         'P_PINCODE'               => $student->P_PINCODE,

    //         // Communication address
    //         'COMMUNICATION_HOUSE_NO'  => $student->COMMUNICATION_HOUSE_NO,
    //         'C_STREET_NAME'           => $student->C_STREET_NAME,
    //         'C_VILLAGE_TOWN_NAME'     => $student->C_VILLAGE_TOWN_NAME,
    //         'C_DISTRICT'              => $student->C_DISTRICT,
    //         'C_STATE'                 => $student->C_STATE,
    //         'C_PINCODE'               => $student->C_PINCODE,

    //         // Add any other fields you want here
    //     ];

    //     // Generate the certificate (assuming this function handles PDF creation and returns URL)
    //      $url = $this->generateCertificate($student, $template, $data, 'Identity Card', 'certificate');

    //     return response()->json(['url' => $url], 200);
    // }

    public function idcard(Request $request)
    {
        $template = TemplateEditor::where(
            "template_name",
            $request->template_name
        )->first();
        if (!$template) {
            return response()->json(["message" => "Template not found"], 404);
        }

        // Check if student_ids is provided as an array
        if (!$request->has("student_ids") || !is_array($request->student_ids)) {
            return response()->json(
                ["message" => "Please provide an array of student IDs"],
                400
            );
        }

        $results = [];
        $errors = [];

        foreach ($request->student_ids as $studentId) {
            $student = Student::find($studentId);

            if (!$student) {
                $errors[] = "Student with ID {$studentId} not found";
                continue;
            }

            // Get profile photo URL
            $profilePhoto = $student->profile_photo; // e.g. "profile740.png"
            $photoUrl =
                env("APP_URL") . "/storage/app/profile_photos/" . $profilePhoto;

            // Prepare data for the template
            $data = [
                "student_name" => $student->STUDENT_NAME,
                "student_photo" => $photoUrl,
                "DOB_DD_MM_YYYY" => $student->DOB_DD_MM_YYYY,
                "SOUGHT_STD" => $student->SOUGHT_STD,
                "MOBILE_NUMBER" => $student->MOBILE_NUMBER,
                "academic_year" => $student->academic_year,

                // Permanent address
                "PERMANENT_HOUSENUMBER" => $student->PERMANENT_HOUSENUMBER,
                "P_STREETNAME" => $student->P_STREETNAME,
                "P_VILLAGE_TOWN_NAME" => $student->P_VILLAGE_TOWN_NAME,
                "P_DISTRICT" => $student->P_DISTRICT,
                "P_STATE" => $student->P_STATE,
                "P_PINCODE" => $student->P_PINCODE,

                // // Communication address
                // 'COMMUNICATION_HOUSE_NO'  => $student->COMMUNICATION_HOUSE_NO,
                // 'C_STREET_NAME'           => $student->C_STREET_NAME,
                // 'C_VILLAGE_TOWN_NAME'     => $student->C_VILLAGE_TOWN_NAME,
                // 'C_DISTRICT'              => $student->C_DISTRICT,
                // 'C_STATE'                 => $student->C_STATE,
                // 'C_PINCODE'               => $student->C_PINCODE,
            ];

            // Generate a filename that includes the student's name
            $filename =
                "idcard_" .
                Str::slug($student->STUDENT_NAME) .
                "_" .
                $studentId;
            // Generate the certificate with custom filename
            $url = $this->generateCertificateid(
                $student,
                $template,
                $data,
                "Identity Card",
                $filename
            );

            // Add to results array
            $results[] = [
                "student_id" => $studentId,
                "student_name" => $student->STUDENT_NAME,
                "url" => $url,
            ];
        }

        return response()->json(
            [
                "success" => count($results) > 0,
                "total_processed" => count($request->student_ids),
                "successful" => count($results),
                "failed" => count($errors),
                "results" => $results,
                "errors" => $errors,
                // 'template'=>$template
            ],
            200
        );
    }

    private function generateCertificateid(
        $student,
        $template,
        $data,
        $title,
        $filenamePrefix
    ) {
        // 1. Replace placeholders in the template
        $html = $template->template;

        foreach ($data as $key => $value) {
            $html = str_replace("{{" . $key . "}}", $value, $html);
        }

        // 2. Remove "display: none" styles
        // $html = preg_replace('/display\s*:\s*none\s*;?/i', '', $html);

        // 3. Remove "hidden-text" class
        $html = preg_replace_callback(
            '/class="([^"]*)"/',
            function ($matches) {
                $classes = preg_split("/\s+/", trim($matches[1]));
                $classes = array_filter(
                    $classes,
                    fn($cls) => $cls !== "hidden-text"
                );
                return 'class="' . implode(" ", $classes) . '"';
            },
            $html
        );
        \Log::info("Generated HTML: " . $html);

        // 4. Generate a unique filename
        $filename = $filenamePrefix . "_" . time() . ".pdf";

        // 5. Generate PDF
        $pdf = PDF::loadHTML($html);

        // 6. Save to root directory
        $folderPath = base_path("certificates");
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $fullPath = $folderPath . "/" . $filename;
        $pdf->save($fullPath);

        // 7. Return the URL (using root path)
        return env("APP_URL") . "/certificates/" . $filename;
    }

    public function noDue(Request $request)
    {
        $template = TemplateEditor::where(
            "template_name",
            $request->template_name
        )->first();
        if (!$template) {
            return response()->json(["message" => "Template not found"], 404);
        }

        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json(["message" => "Student not found"], 404);
        }

        $data = [
            "student_name" => $student->STUDENT_NAME,
            "admission_no" => $student->admission_no,
            "SOUGHT_STD" => $student->SOUGHT_STD,
            "end_date" => \Carbon\Carbon::today()->toDateString(),
        ];

        // Replace placeholders in the HTML content

        // Generate the certificate (assuming this function handles PDF creation and returns URL)
        $url = $this->generateCertificate(
            $student,
            $template,
            $data,
            "Nodue",
            "certificate"
        );

        return response()->json(["url" => $url], 200);
    }

    // public function studentAttendanceView(Request $request)
    // {
    //     $template = TemplateEditor::where('template_name', $request->template_name)->first();
    //     if (!$template) {
    //         return response()->json(['message' => 'Template not found'], 404);
    //     }

    //     $student = Student::find($request->student_id);
    //     if (!$student) {
    //         return response()->json(['message' => 'Student not found'], 404);
    //     }

    //     $data = [
    //         'student_name' => $student->STUDENT_NAME,
    //         'class_name' => $student->SOUGHT_STD,
    //         'academic_year' => $student->academic_year,
    //         'working_days' => '',
    //         'days_attended' => '',
    //         'attendance_percent' => '',
    //         'st' => Carbon::now()->format('d-m-Y'),
    //     ];

    //     $htmlContent = $this->replaceTemplateVariables($template->template, $data);

    //     return response($htmlContent, 200)
    //         ->header('Content-Type', 'text/html; charset=UTF-8');
    // }
    public function studentAttendanceView(Request $request)
    {
        $template = TemplateEditor::where(
            "template_name",
            $request->template_name
        )->first();
        if (!$template) {
            return response()->json(["message" => "Template not found"], 404);
        }

        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json(["message" => "Student not found"], 404);
        }

        $data = [
            "student_name" => $student->STUDENT_NAME,
            "class_name" => $student->SOUGHT_STD,
            "academic_year" => $student->academic_year,
            "working_days" => "",
            "days_attended" => "",
            "attendance_percent" => "",
            "st" => now()->format("d-m-Y"),
        ];

        $url = $this->generateCertificate(
            $student,
            $template,
            $data,
            "Attendance Certificate",
            "attendance_certificate"
        );

        return response()->json(["url" => $url]);
    }

    public function studentContactView(Request $request)
    {
        $template = TemplateEditor::where(
            "template_name",
            $request->template_name
        )->first();
        if (!$template) {
            return response()->json(["message" => "Template not found"], 404);
        }

        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json(["message" => "Student not found"], 404);
        }

        $template_data = [
            "student_name" => $student->STUDENT_NAME,
            "parent_name" => $student->FATHER,
            "start_date" => $student->date_form,
            "end_date" => \Carbon\Carbon::today()->toDateString(),
            "class_name" => $student->SOUGHT_STD,
            "completed_class" => $student->SOUGHT_STD,
            "st" => now()->format("d-m-Y"),
        ];

        $url = $this->generateCertificate(
            $student,
            $template,
            $template_data,
            "Student Certificate",
            "certificate"
        );

        return response()->json(["url" => $url], 200);
    }

    private function generateCertificate(
        $student,
        $template,
        $data,
        $title,
        $filePrefix
    ) {
        $body = $this->replaceTemplateVariables($template->template, $data);
        // Remove all "display: none" styles from inline styles and <style> blocks
        $body = preg_replace("/display\s*:\s*none\s*;?/i", "", $body);
        // Remove just the "hidden-text" class but keep the element and content
        // $body = preg_replace('/\bhidden-text\b/', '', $body);
        $body = preg_replace_callback(
            '/class="([^"]*)"/',
            function ($matches) {
                $classes = preg_split("/\s+/", trim($matches[1]));
                $classes = array_filter(
                    $classes,
                    fn($cls) => $cls !== "hidden-text"
                );
                return 'class="' . implode(" ", $classes) . '"';
            },
            $body
        );

        $body = ltrim($body);
        Log::info("$body {$body} ");

        $htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$title</title>
</head>
<body>
$body
</body>
</html>
HTML;

        $pdf = \PDF::loadHTML($htmlContent)->setPaper("a4", "portrait");

        $fileName = $filePrefix . "_" . $student->id . ".pdf";

        // Get path to save certificate
        $folderName = env("APP_FOLDER", "SVSTEST"); // default to SVSTEST if not set
        $uploadPath =
            $_SERVER["DOCUMENT_ROOT"] .
            "/" .
            $folderName .
            "/public/certificates";

        // Create directory if it doesn't exist
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $filePath = $uploadPath . "/" . $fileName;

        // ✅ Delete existing file if present
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Save new PDF
        $pdf->save($filePath);

        // Return public URL
        $url = "/" . $folderName . "/public/certificates/" . $fileName;

        return $url;
    }

    private function convertDateToWords($date)
    {
        // Convert the date string into a DateTime object
        $dateObj = new DateTime($date);

        // Format the day, month, and year as required
        $day = $dateObj->format("j"); // Day of the month (1-31)
        $month = $dateObj->format("F"); // Full textual representation of a month
        $year = $dateObj->format("Y"); // Four-digit year

        // Convert the day into words
        $dayWords = $this->convertNumberToWords($day);

        // Convert the year into words
        $yearWords = $this->convertNumberToWords($year);

        return "{$dayWords} {$month} {$yearWords}";
    }

    private function convertNumberToWords($number)
    {
        $words = [
            0 => "zero",
            1 => "one",
            2 => "two",
            3 => "three",
            4 => "four",
            5 => "five",
            6 => "six",
            7 => "seven",
            8 => "eight",
            9 => "nine",
            10 => "ten",
            11 => "eleven",
            12 => "twelve",
            13 => "thirteen",
            14 => "fourteen",
            15 => "fifteen",
            16 => "sixteen",
            17 => "seventeen",
            18 => "eighteen",
            19 => "nineteen",
            20 => "twenty",
            30 => "thirty",
            40 => "forty",
            50 => "fifty",
            60 => "sixty",
            70 => "seventy",
            80 => "eighty",
            90 => "ninety",
            100 => "hundred",
            1000 => "thousand",
            100000 => "lakh",
            10000000 => "crore",
        ];

        // Handle special case for numbers above 20 and under 100
        if ($number < 20) {
            return $words[$number];
        } elseif ($number < 100) {
            $tens = floor($number / 10) * 10;
            $ones = $number % 10;
            return $ones > 0
                ? $words[$tens] . "-" . $words[$ones]
                : $words[$tens];
        } elseif ($number < 1000) {
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            return $words[$hundreds] .
                " hundred" .
                ($remainder > 0
                    ? " and " . $this->convertNumberToWords($remainder)
                    : "");
        } elseif ($number < 100000) {
            $thousands = floor($number / 1000);
            $remainder = $number % 1000;
            return $this->convertNumberToWords($thousands) .
                " thousand" .
                ($remainder > 0
                    ? " " . $this->convertNumberToWords($remainder)
                    : "");
        } elseif ($number < 10000000) {
            $lakhs = floor($number / 100000);
            $remainder = $number % 100000;
            return $this->convertNumberToWords($lakhs) .
                " lakh" .
                ($remainder > 0
                    ? " " . $this->convertNumberToWords($remainder)
                    : "");
        } else {
            $crores = floor($number / 10000000);
            $remainder = $number % 10000000;
            return $this->convertNumberToWords($crores) .
                " crore" .
                ($remainder > 0
                    ? " " . $this->convertNumberToWords($remainder)
                    : "");
        }
    }

    private function replaceTemplateVariables($templateString, $data)
    {
        // First, check if the template contains any variables
        if (strpos($templateString, "{{") === false) {
            Log::warning("No template variables found in template");
        }

        // Log all variables we're trying to replace
        Log::info("Attempting to replace variables", array_keys($data));

        foreach ($data as $key => $value) {
            // Make sure value is a string
            $value = (string) $value;

            // Pattern matches {{ key }} with optional spaces inside
            $pattern = "/{{\s*" . preg_quote($key, "/") . "\s*}}/";

            // Count replacements for debugging
            $count = 0;
            $templateString = preg_replace(
                $pattern,
                $value,
                $templateString,
                -1,
                $count
            );

            if ($count === 0) {
                Log::warning("No replacements made for variable: {$key}");
            } else {
                Log::info("Replaced {$count} occurrences of {$key}");
            }
        }

        // Check if any {{ variables }} remain in the template
        if (preg_match_all("/{{(.*?)}}/", $templateString, $matches)) {
            Log::warning("Unreplaced variables found in template", $matches[1]);
        }

        return $templateString;
    }

    // public function viewById($id)
    // {
    //     // Find template by ID
    //     $template = TemplateEditor::find($id);

    //     // Check if template exists
    //     if (!$template) {
    //         // Return 404 if template not found
    //         return response()->json(['message' => 'Template not found'], 404);
    //     }

    //     // Return clean HTML content as response
    //     return response()->make($template->template, 200, [
    //         'Content-Type' => 'text/html',
    //     ]);
    // }

    // Create a new template
    public function store(Request $request)
    {
        $data = $request->validate([
            "template" => "required", // HTML content
        ]);

        $template = TemplateEditor::create($data);
        return response()->json($template, 201);
    }

    // Update an existing template
    public function update(Request $request, $id)
    {
        $template = TemplateEditor::find($id);
        if ($template) {
            $data = $request->validate([
                "template" => "required", // HTML content
            ]);

            $template->update($data);
            return response()->json($template);
        }
        return response()->json(["message" => "Template not found"], 404);
    }

    // Delete a template
    public function destroy($id)
    {
        $template = TemplateEditor::find($id);
        if ($template) {
            $template->delete();
            return response()->json([
                "message" => "Template deleted successfully",
            ]);
        }
        return response()->json(["message" => "Template not found"], 404);
    }

    // Generate and download PDF from template
    public function downloadPdf($id)
    {
        $template = TemplateEditor::find($id);
        if (!$template) {
            return response()->json(["message" => "Template not found"], 404);
        }

        // Pass data to the view and load the HTML content
        $data = [
            "template_name" => $template->template_name,
            "extra" => $template->extra,
            "comment" => $template->comment,
            "content" => $template->template, // HTML content
        ];

        // Load view and generate PDF
        $pdf = Pdf::loadView("pdf.template", $data);

        // Return PDF download
        return $pdf->download("template_" . $template->id . ".pdf");
    }

    // Preview the PDF in browser
    public function previewPdf($id)
    {
        // Get the template from the database
        $template = TemplateEditor::where(
            "template_name",
            "Attendance Certificate"
        )->first();
        $existingUser = User::find($id);
        $existingStudent = Student::where(
            "admission_no",
            $existingUser->admission_no
        )->first();

        if (!$template) {
            return response()->json(["message" => "Template not found"], 404);
        }

        // Corrected HTML Template for Perfect Alignment

        // Generate PDF with correct format
        $pdf = \PDF::loadHTML($template->template);
        return $pdf->stream("certificate_preview.pdf");
        // Define path to save PDF
        $pdfPath = public_path("templates/templates_12345.pdf");

        // Save the generated PDF to the path
        $pdf->save($pdfPath);

        // Return the correct PDF link
        return response()->json([
            "message" => "Certificate generated successfully",
            "pdf_link" => url("templates/templates_12345.pdf"),
        ]);
    }
}
