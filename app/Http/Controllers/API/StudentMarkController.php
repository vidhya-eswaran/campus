<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentMarkRecord;
use App\Models\TemporaryStudentMark;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TemplateMaster;
use App\Models\Student;
use App\Models\Term;
use Barryvdh\DomPDF\Facade as PDF;
use App\Helpers\LifecycleLogger;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class StudentMarkController extends Controller
{
    public function store(Request $request)
    {
        // Validate the payload to ensure it's an array and contains required fields
        $validatedData = $request->validate([
            "*.academic_year" => "required",
            "*.roll_no" => "required",
            "*.name" => "required",
            "*.standard" => "required",
            "*.section" => "nullable",
            "*.term" => "required",
            // '*.remarks'          => 'required',
            "*.group" => "nullable",
        ]);

        $records = [];

        // Iterate through each record in the payload
        foreach ($request->all() as $data) {
            // Extract core fields

            $coreData = [
                "academic_year" => $data["academic_year"],
                "roll_no" => $data["roll_no"],
                "name" => $data["name"],
                "standard" => $data["standard"],
                "section" => $data["section"] ?? null,
                "term" => $data["term"],
                "group_no" => $data["group"] ?? null,
                "total" => $data["total"] ?? null,
                "percentage" => $data["percentage"] ?? null,
                "remarks" => $data["remarks"] ?? null,
                "created_at" => Carbon::now("Asia/Kolkata"), // Custom timestamp
                "updated_at" => Carbon::now("Asia/Kolkata"), // Custom timestamp
            ];

            // Extract dynamic subject fields
            $subjects = array_diff_key(
                $data,
                array_flip([
                    "academic_year",
                    "roll_no",
                    "name",
                    "standard",
                    "section",
                    "term",
                    "group",
                    "total",
                    "percentage",
                    "created_at",
                    "updated_at", // Exclude these fields
                    "concordinate_string",
                    "remarks",
                    "id",
                    "std",
                ])
            );
            // Create the record in the database
            $record = StudentMarkRecord::create(
                array_merge($coreData, [
                    "subjects" => json_encode($subjects),
                ])
            );

            // Add record to response collection
            $records[] = $record;

            $userId = User::where("roll_no", $data["roll_no"])
                ->latest("id")
                ->value("id");

            // Log the mark entry
            LifecycleLogger::log(
                "Mark Record Created",
                $userId,
                "student_mark_record_created",
                [
                    "student_name" => $data["name"] ?? null,
                    "roll_no" => $data["roll_no"] ?? null,
                    "term" => $data["term"] ?? null,
                    "standard" => $data["standard"] ?? null,
                    "total" => $data["total"] ?? null,
                    "percentage" => $data["percentage"] ?? null,
                ]
            );
        }

        // Return success response with all stored records
        return response()->json(
            [
                "message" => "Student mark records saved successfully.",
                "data" => $records,
            ],
            201
        );
    }
    public function saveTemporary(Request $request)
    {
        $validatedData = $request->validate([
            "*.academic_year" => "nullable",
            "*.roll_no" => "nullable",
            "*.name" => "nullable",
            "*.standard" => "nullable",
            "*.section" => "nullable",
            "*.term" => "nullable",
            "*.group" => "nullable",
            "*.total" => "nullable",
            "*.percentage" => "nullable",
            "*.remarks" => "nullable",
        ]);

        $recordsToInsert = [];
        foreach ($request->all() as $data) {
            // Check if required fields exist for insertion
            // if (!empty($data['total']) && !empty($data['percentage']) && !empty($data['term']) && !empty($data['academicYear'])) {
            // Build core data
            $coreData = [
                "academic_year" => $data["academic_year"] ?? null,
                "roll_no" => $data["roll_no"] ?? null,
                "name" => $data["name"] ?? null,
                "standard" => $data["standard"] ?? null,
                "section" => $data["section"] ?? null,
                "term" => $data["term"] ?? null,
                "group_no" => $data["group"] ?? null,
                "total" => $data["total"] ?? null,
                "percentage" => $data["percentage"] ?? null,
                "remarks" => $data["remarks"] ?? null,
                "subjects" => !empty(
                    array_diff_key(
                        $data,
                        array_flip([
                            "academic_year",
                            "roll_no",
                            "name",
                            "standard",
                            "section",
                            "term",
                            "group",
                            "total",
                            "percentage",
                            "remarks",
                        ])
                    )
                )
                    ? json_encode(
                        array_diff_key(
                            $data,
                            array_flip([
                                "academic_year",
                                "roll_no",
                                "name",
                                "standard",
                                "section",
                                "term",
                                "group",
                                "total",
                                "percentage",
                                "remarks",
                            ])
                        )
                    )
                    : null,
            ];

            // Save record to array for batch insert
            $recordsToInsert[] = $coreData;
            // }
        }

        // Insert all valid records
        if (!empty($recordsToInsert)) {
            foreach ($recordsToInsert as $record) {
                \App\Models\TemporaryStudentMark::updateOrCreate(
                    [
                        "academic_year" => $record["academic_year"],
                        "roll_no" => $record["roll_no"],
                        "standard" => $record["standard"],
                        "section" => $record["section"],
                        "term" => $record["term"],
                    ],
                    $record
                );
            }
        }

        return response()->json(
            ["message" => "Temporary marks saved successfully."],
            200
        );
    }

    public function getTemporary(Request $request)
    {
        $query = \App\Models\TemporaryStudentMark::query();

        // Apply filters dynamically
        if ($request->has("term")) {
            $query->where("term", $request->query("term"));
        }
        if ($request->has("standard")) {
            $query->where("standard", $request->query("standard"));
        }
        if ($request->has("section")) {
            $query->where("section", $request->query("section"));
        }
        if ($request->has("academic_year")) {
            $query->where("academic_year", $request->query("academic_year"));
        }

        // Fetch the data
        $datas = $query->get();

        // Map the data for JSON response
        $datas = $datas->map(function ($record) {
            // Decode the subjects JSON field
            $subjects = json_decode($record->subjects, true);

            // Check if decoding was successful
            if (is_array($subjects)) {
                foreach ($subjects as $subject => $marks) {
                    $record->{$subject} = $marks; // Add each subject as a column
                }
            }

            // Remove the original subjects field
            unset($record->subjects);

            return $record;
        });

        // Check if data is empty
        if ($datas->isEmpty()) {
            return response()->json(
                ["message" => "No data found for the given filters."],
                404
            );
        }

        // Return the response
        return response()->json(["data" => $datas], 200);
    }

    public function viewAll(Request $request)
    {
        // Build the query to fetch records
        $query = DB::table("student_mark_records");

        // Apply filters if present
        if ($request->has("term")) {
            $query->where("term", $request->query("term"));
        }

        if ($request->has("standard")) {
            $query->where("standard", $request->query("standard"));
        }

        if ($request->has("section")) {
            $query->where("section", $request->query("section"));
        }

        if ($request->has("academic_year")) {
            $query->where("academic_year", $request->query("academic_year"));
        }

        // Get the filtered results
        $students = $query->get();
        // Process each student record to extract individual subjects
        $students = $students->map(function ($student) {
            // Decode the subjects JSON field to an associative array
            $subjects = json_decode($student->subjects, true);

            // Merge subjects as separate columns
            foreach ($subjects as $subject => $marks) {
                $student->{$subject} = $marks;
            }

            // Remove the subjects field as we don't need it anymore
            unset($student->subjects);

            return $student;
        });
        return response()->json($students);
    }

    public function viewReportCard(Request $request)
{
    // Step 1: Build the query to fetch student record
    $query = DB::table("student_mark_records");

    if ($request->has("term")) {
        $query->where("term", $request->query("term"));
    }
    if ($request->has("standard")) {
        $query->where("standard", $request->query("standard"));
    }
    if ($request->has("section")) {
        $query->where("section", $request->query("section"));
    }
    if ($request->has("academic_year")) {
        $query->where("academic_year", $request->query("academic_year"));
    }
    if ($request->has("roll_no")) {
        $query->where("roll_no", $request->query("roll_no"));
    }

    $student = $query->first();

    // Step 2: Check if student data exists
    if (!$student) {
        return response()->json(
            ["message" => "Student record not found"],
            404
        );
    }

    // Get additional student data
    $student_data = Student::where("roll_no", $request->query("roll_no"))->first();

    if (!$student_data) {
        return response()->json(
            ["message" => "Student details not found"],
            404
        );
    }

    $fatherName = $student_data->FATHER ?? 'N/A';
    $motherName = $student_data->MOTHER ?? 'N/A';
    $dob = $student_data->DOB_DD_MM_YYYY ?? 'N/A';
    $admissionNo = $student_data->admission_no ?? 'N/A';
    $section = $student_data->sec ?? $student->section;

    $termName = Term::where("name", $request->query("term"))->first()->name ?? $request->query("term");

    $template = TemplateMaster::where("template_name", "Report Card")->first();
    $logo = "https://santhoshavidhyalaya.com/svsportaladmintest/static/media/newlogo.f86bd51493e0e8166940.jpg";

    if (!$template) {
        return response()->json(["message" => "Template not found"], 404);
    }

    // Step 3: Parse the subjects JSON and prepare the marks table rows
    $subjects = json_decode($student->subjects, true);

    if (!$subjects || !is_array($subjects)) {
        return response()->json(["message" => "Invalid subjects data"], 400);
    }

    $remarks = $student->remarks ?? 'Good performance';
    $subjectNames = array_keys($subjects);
    $subjectMarks = array_map(function ($mark) {
        return is_numeric($mark) ? (int) $mark : 0;
    }, array_values($subjects));

    // Assign unique colors for each bar
    $barColors = [
        "FF6384", "36A2EB", "FFCE56", "4BC0C0", "9966FF", "FF9F40",
    ];

    // Ensure we have enough colors
    while (count($barColors) < count($subjectNames)) {
        $barColors = array_merge($barColors, $barColors);
    }

    $barColors = array_slice($barColors, 0, count($subjectNames));

    // Generate the Image-Charts URL
    $chartUrl = "https://image-charts.com/chart?" . http_build_query([
        "cht" => "bvg", // Vertical bar graph
        "chs" => "700x400", // Chart size
        "chd" => "t:" . implode(",", $subjectMarks), // Chart data
        "chxl" => "0:|" . implode("|", $subjectNames), // X-axis labels
        "chxt" => "x,y", // Display X and Y axis
        "chco" => implode(",", $barColors), // Bar colors
        "chbh" => "30,5,15", // Bar width and spacing
        "chtt" => "Student+Performance+Graph", // Title
        "chts" => "000000,20", // Title style
        "chds" => "0,100", // Y-axis range
    ]);

    // Build marks table rows
    $marksRows = "";
    foreach ($subjects as $subject => $marks) {
        $marksRows .= "
            <tr>
                <td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($subject) . "</td>
                <td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>" . htmlspecialchars($marks) . "</td>
            </tr>
        ";
    }

    // Step 4: Replace placeholders in the template
    $replacements = [
        '${name}' => htmlspecialchars($student->name ?? 'N/A'),
        '${roll_no}' => htmlspecialchars($student->roll_no ?? 'N/A'),
        '${academic_year}' => htmlspecialchars($student->academic_year ?? 'N/A'),
        '${term}' => htmlspecialchars($student->term ?? 'N/A'),
        '${standard}' => htmlspecialchars($student->standard ?? 'N/A'),
        '${section}' => htmlspecialchars($section),
        '${marksRows}' => $marksRows,
        '${totalMarks}' => htmlspecialchars($student->total ?? '0'),
        '${percentage}' => htmlspecialchars(($student->percentage ?? '0') . "%"),
        '${chartUrl}' => htmlspecialchars($chartUrl),
        '${father}' => htmlspecialchars($fatherName),
        '${mother}' => htmlspecialchars($motherName),
        '${dob}' => htmlspecialchars($dob),
        '${admission_no}' => htmlspecialchars($admissionNo),
        '${term_name}' => htmlspecialchars($termName),
        '${logo}' => htmlspecialchars($logo),
        '${remarks}' => htmlspecialchars($remarks),
    ];

    $populatedTemplate = str_replace(
        array_keys($replacements),
        array_values($replacements),
        $template->template
    );

    // Step 5: Create fallback template if original template is empty or malformed
    if (empty(trim($populatedTemplate)) || strlen($populatedTemplate) < 100) {
        $populatedTemplate = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Report Card</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 20px; }
                .logo { max-width: 100px; height: auto; }
                .student-info { margin-bottom: 20px; }
                .marks-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .marks-table th, .marks-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                .marks-table th { background-color: #f2f2f2; }
                .summary { margin: 20px 0; }
                .chart { text-align: center; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="header">
                <img src="' . $logo . '" alt="School Logo" class="logo">
                <h1>Student Report Card</h1>
            </div>

            <div class="student-info">
                <h3>Student Information</h3>
                <p><strong>Name:</strong> ' . ($student->name ?? 'N/A') . '</p>
                <p><strong>Roll No:</strong> ' . ($student->roll_no ?? 'N/A') . '</p>
                <p><strong>Class:</strong> ' . ($student->standard ?? 'N/A') . '</p>
                <p><strong>Section:</strong> ' . $section . '</p>
                <p><strong>Academic Year:</strong> ' . ($student->academic_year ?? 'N/A') . '</p>
                <p><strong>Term:</strong> ' . $termName . '</p>
                <p><strong>Father Name:</strong> ' . $fatherName . '</p>
                <p><strong>Mother Name:</strong> ' . $motherName . '</p>
                <p><strong>Date of Birth:</strong> ' . $dob . '</p>
                <p><strong>Admission No:</strong> ' . $admissionNo . '</p>
            </div>

            <h3>Subject-wise Marks</h3>
            <table class="marks-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $marksRows . '
                </tbody>
            </table>

            <div class="summary">
                <h3>Summary</h3>
                <p><strong>Total Marks:</strong> ' . ($student->total ?? '0') . '</p>
                <p><strong>Percentage:</strong> ' . (($student->percentage ?? '0') . '%') . '</p>
                <p><strong>Remarks:</strong> ' . $remarks . '</p>
            </div>

            <div class="chart">
                <h3>Performance Graph</h3>
                <img src="' . $chartUrl . '" alt="Performance Chart" style="max-width: 100%; height: auto;">
            </div>
        </body>
        </html>';
    }

    // Save debug template for inspection
    file_put_contents(public_path('debug_template.html'), $populatedTemplate);

   // In your viewReportCard function, replace the PDF generation part:
try {
    // Disable remote images temporarily
    $populatedTemplate = str_replace($chartUrl, '', $populatedTemplate);
    $populatedTemplate = str_replace($logo, '', $populatedTemplate);

    $pdf = PDF::loadHTML($populatedTemplate)
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => false,  // Disable remote content
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
        ]);

    $pdfPath = public_path("reports/report_card_{$student->roll_no}.pdf");
    $pdf->save($pdfPath);

      // Step 7: Return the PDF link as response
        return response()->json([
            "message" => "Report card generated successfully",
            "pdf_link" => url(
                "public/reports/report_card_{$student->roll_no}.pdf"
            ),
            "remarks" => $remarks,
            //'load'=>$populatedTemplate
            "name" => $termName,
        ]);

} catch (Exception $e) {
    return response()->json([
        "message" => "Error generating PDF: " . $e->getMessage(),
        "suggestion" => "Please enable GD extension in PHP"
    ], 500);
}
}

    public function update(Request $request)
    {
        // Validate the payload to ensure it's an array and contains required fields
        $validatedData = $request->validate([
            "*.id" => "required|integer", // Ensure the 'id' is present and is an integer
            "*.academic_year" => "required|string",
            "*.roll_no" => "required|string",
            "*.name" => "required|string",
            "*.standard" => "required|string",
            "*.section" => "nullable|string",
            "*.term" => "required|string",
            "*.group" => "nullable|string",
            "*.remarks" => "nullable|string",
        ]);

        $records = [];

        // Iterate through each record in the payload
        foreach ($request->all() as $data) {
            // Extract core fields
            $coreData = [
                "academic_year" => $data["academic_year"],
                "roll_no" => $data["roll_no"],
                "name" => $data["name"],
                "standard" => $data["standard"],
                "section" => $data["section"] ?? null,
                "term" => $data["term"],
                "group_no" => $data["group"] ?? null,
                "total" => $data["total"] ?? null,
                "percentage" => $data["percentage"] ?? null,
                "remarks" => $data["remarks"] ?? null,
                "updated_at" => Carbon::now("Asia/Kolkata"),
            ];

            // Extract dynamic subject fields
            $subjects = array_diff_key(
                $data,
                array_flip([
                    "id",
                    "academic_year",
                    "roll_no",
                    "name",
                    "standard",
                    "section",
                    "term",
                    "group",
                    "total",
                    "percentage",
                    "concordinate_string",
                    "remarks",
                ])
            );

            // Find the record by ID and update it, or create a new one if it doesn't exist
            $record = StudentMarkRecord::find($data["id"]); // Find the record by ID

            if ($record) {
                // If record exists, update the record
                $record->update(
                    array_merge($coreData, [
                        "subjects" => json_encode($subjects),
                    ])
                );
            } else {
                // If the record doesn't exist, create a new one
                $record = StudentMarkRecord::create(
                    array_merge($coreData, [
                        "subjects" => json_encode($subjects),
                    ])
                );
            }

            // Add the updated or created record to the response collection
            $records[] = $record;
        }

        // Return success response with all updated or created records
        return response()->json(
            [
                "message" => "Student mark records updated successfully.",
                "data" => $records,
            ],
            200
        );
    }
}
