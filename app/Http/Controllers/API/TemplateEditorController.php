<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

    public function viewById(Request $request, $id)
    {
        $id = $request->id;
        // Find the template by ID
        $template = TemplateEditor::find($id);

        $schoolSlug = request()->route('school');

        $school = DB::connection('central')->table('schools')->where('name', $schoolSlug)->first();


        // Check if template exists
        if ($template) {
            $placeholders = [
                '{{ $school_name }}' => $school->school,
                '{{ $school_address }}' => $school->full_address,
                '{{ $school_logo }}' => $school->school_logo,
                '{{ $school_phone_1 }}' => $school->phone_number,
                '{{ $school_phone_2 }}' => $school->alternate_phone_number,
                '{{ $school_website }}' => $school->website_url,
                '{{ $school_address_line1 }}' => $school->full_address,
                '{{ $school_address_line2 }}' => trim(($school->city ?? '') . ', ' . ($school->state ?? '')),
                '{{ $school_email }}' => $school->email_address,
            ];
            $htmlContent = $template->template;

            $htmlContent = str_replace(array_keys($placeholders), array_values($placeholders), $htmlContent);

            return response($htmlContent, 200)->header("Content-Type", "text/html; charset=UTF-8");

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
            "student_name" => $student->student_name,
            "father_or_mother_name" => $student->father_name,
            "class" => $student->std_sought,
            "academic_year" => $student->academic_year,
            "date_of_birth_numeric" => $student->dob,
            "date_of_birth_words" => $this->convertDateToWords(
                $student->dob
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
            "student_name" => $student->student_name,
            "parent_name" => $student->father_name,
            "start_date" => $student->date_form,
            "end_date" => \Carbon\Carbon::today()->toDateString(),
            "class_name" => $student->std_sought,
            "completed_class" => $student->std_sought,
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
            $profilePhoto = $student->profile_image; // e.g. "profile740.png"
            $photoUrl = $profilePhoto;

            $schoolSlug = request()->route('school');

            $school = DB::connection('central')->table('schools')->where('name', $schoolSlug)->first();

            // Prepare data for the template
            $data = [
                "student_name" => $student->student_name,
                "student_photo" => $photoUrl,
                "DOB_DD_MM_YYYY" => $student->dob,
                "SOUGHT_STD" => $student->std_sought,
                "MOBILE_NUMBER" => $student->father_mobile_no,
                "academic_year" => $student->academic_year,

                // Permanent address
                "PERMANENT_HOUSENUMBER" => $student->permanent_house_no,
                "P_STREETNAME" => $student->permanent_street_name,
                "P_VILLAGE_TOWN_NAME" => $student->permanent_city_town_village,
                "P_DISTRICT" => $student->permanent_district,
                "P_STATE" => $student->permanent_state,
                "P_PINCODE" => $student->permanent_pincode,

                'school_name' => $school->school,
                'school_address' => $school->full_address,
                'school_logo' => $school->school_logo,
                'school_phone_1' => $school->phone_number,
                'school_phone_2' => $school->alternate_phone_number,
                'school_website' => $school->website_url,
                'school_address_line1' => $school->full_address,
                'school_address_line2' => trim(($school->city ?? '') . ', ' . ($school->state ?? '')),
                'school_email' => $school->email_address,
            ];

            $placeholders = [
                '{{ $school_name }}' => $school->school,
                '{{ $school_address }}' => $school->full_address,
                '{{ $school_logo }}' => $school->school_logo,
                '{{ $school_phone_1 }}' => $school->phone_number,
                '{{ $school_phone_2 }}' => $school->alternate_phone_number,
                '{{ $school_website }}' => $school->website_url,
                '{{ $school_address_line1 }}' => $school->full_address,
                '{{ $school_address_line2 }}' => trim(($school->city ?? '') . ', ' . ($school->state ?? '')),
                '{{ $school_email }}' => $school->email_address,
            ];

            $htmlContent = $template->template;

            $template = str_replace(array_keys($placeholders), array_values($placeholders), $htmlContent);

            $filename =
                "idcard_" .
                Str::slug($student->student_name) .
                "_" .
                $studentId;
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
                "student_name" => $student->student_name,
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

        dd($html);

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
        //dd($html);

        $pdfContent = $pdf->output();
//dd("CCc");
        $fileName = $filenamePrefix . "_" . $student->id . ".pdf";

        $schoolSlug = request()->route('school');  

        $s3Path = 'documents/' . $schoolSlug ."/idcard/{$fileName}";
        Storage::disk('s3')->put($s3Path, $pdfContent);

        // Return the full URL to access the file
        $url1 = Storage::disk('s3')->url($s3Path);

        $s3Key = ltrim(parse_url($url1, PHP_URL_PATH), '/');

                    // Generate temporary signed download link
        $url = Storage::disk('s3')->temporaryUrl(
                        $s3Key,
                        now()->addMinutes(5),
                        ['ResponseContentDisposition' => 'attachment']
        );

        // 7. Return the URL (using root path)
        return $url;
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
            "student_name" => $student->student_name,
            "admission_no" => $student->admission_no,
            "SOUGHT_STD" => $student->std_sought,
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
            "student_name" => $student->student_name,
            "class_name" => $student->std_sought,
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
            "student_name" => $student->student_name,
            "parent_name" => $student->father_name,
            "start_date" => $student->date_form,
            "end_date" => \Carbon\Carbon::today()->toDateString(),
            "class_name" => $student->std_sought,
            "completed_class" => $student->std_sought,
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
        $pdfContent = $pdf->output();

        $fileName = $filePrefix . "_" . $student->id . ".pdf";

        $schoolSlug = request()->route('school');  

        $s3Path = 'documents/' . $schoolSlug ."/certificates/{$fileName}";
        Storage::disk('s3')->put($s3Path, $pdfContent);

        // Return the full URL to access the file
        $url1 = Storage::disk('s3')->url($s3Path);

        $s3Key = ltrim(parse_url($url1, PHP_URL_PATH), '/');

                    // Generate temporary signed download link
        $url = Storage::disk('s3')->temporaryUrl(
                        $s3Key,
                        now()->addMinutes(5),
                        ['ResponseContentDisposition' => 'attachment']
        );

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

    public function studentCertificatelist(Request $request)
    {
        // Start query builder
        $query = Student::query();

        // Apply filters
        if ($request->has("standard")) {
            $query->where("std_sought", $request->query("standard"));
        }
        if ($request->has("section")) {
            $query->where("sec", $request->query("section"));
        }
        if ($request->has("academic_year")) {
            $query->where("academic_year", $request->query("academic_year"));
        }

        // Get the filtered student list
        $students = $query->get();

        if ($students->isEmpty()) {
            return response()->json(["message" => "No students found"], 404);
        }

        // Fetch template
        $template = TemplateEditor::where("template_name", $request->query("template_name"))->first();
        if (!$template) {
            return response()->json(["message" => "Certificate template not found"], 404);
        }

        $data = [];

        foreach ($students as $student) {
            $template_data = [
                "student_name" => $student->student_name,
                "parent_name" => $student->father_name,
                "start_date" => $student->date_form,
                "end_date" => \Carbon\Carbon::today()->toDateString(),
                "class_name" => $student->std_sought,
                "completed_class" => $student->std_sought,
                "st" => now()->format("d-m-Y"),
            ];

            $url = $this->generateCertificate(
                $student,
                $template,
                $template_data,
                $template->template_name,
                "certificate"
            );

            $data[] = [
                "student_id" => $student->id,
                "student_name" => $student->student_name,
                "roll_no" => $student->roll_no,
                "class_name" => $student->std_sought,
                "section" => $student->sec,
                "academic_year" => $student->academic_year,
                "url" => $url
            ];
        }

        return response()->json($data, 200);
    }

}
