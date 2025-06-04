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
            '*.academic_year' => 'required',
            '*.roll_no'       => 'required',
            '*.name'          => 'required',
            '*.standard'      => 'required',
            '*.section'       => 'nullable',
            '*.term'          => 'required',
            // '*.remarks'          => 'required',
            '*.group'         => 'nullable',
        ]);

        $records = [];

        // Iterate through each record in the payload
        foreach ($request->all() as $data) {
            // Extract core fields
       
            $coreData = [
                'academic_year' => $data['academic_year'],
                'roll_no'       => $data['roll_no'],
                'name'          => $data['name'],
                'standard'      => $data['standard'],
                'section'       => $data['section'] ?? null,
                'term'          => $data['term'],
                'group_no'      => $data['group'] ?? null,
                'total'         => $data['total'] ?? null,
                'percentage'    => $data['percentage'] ?? null,
                'remarks'    => $data['remarks'] ?? null,
              'created_at'    => Carbon::now('Asia/Kolkata'), // Custom timestamp
            'updated_at'    => Carbon::now('Asia/Kolkata'), // Custom timestamp
            ];

            // Extract dynamic subject fields
               $subjects = array_diff_key($data, array_flip([
            'academic_year',
            'roll_no',
            'name',
            'standard',
            'section',
            'term',
            'group',
            'total',
            'percentage',
            'created_at',
            'updated_at', // Exclude these fields
            'concordinate_string',
            'remarks',
            'id',
             'std',
        ]));
            // Create the record in the database
            $record = StudentMarkRecord::create(array_merge($coreData, [
                'subjects' => json_encode($subjects),
            ]));

            // Add record to response collection
            $records[] = $record;
            
     $userId = User::where('roll_no', $data['roll_no'])->latest('id')->value('id');

        // Log the mark entry
        LifecycleLogger::log(
            'Mark Record Created',
            $userId,
            'student_mark_record_created',
            [
                'student_name' => $data['name'] ?? null,
                'roll_no'      => $data['roll_no'] ?? null,
                'term'         => $data['term'] ?? null,
                'standard'     => $data['standard'] ?? null,
                 'total'        => $data['total'] ?? null,
                'percentage'   => $data['percentage'] ?? null,
            ]
        );
        }

        // Return success response with all stored records
        return response()->json([
            'message' => 'Student mark records saved successfully.',
            'data'    => $records,
        ], 201);
    }
  public function saveTemporary(Request $request)
{
    $validatedData = $request->validate([
        '*.academic_year' => 'nullable',
        '*.roll_no'      => 'nullable',
        '*.name'         => 'nullable',
        '*.standard'     => 'nullable',
        '*.section'      => 'nullable',
        '*.term'         => 'nullable',
        '*.group'        => 'nullable',
        '*.total'        => 'nullable',
        '*.percentage'   => 'nullable',
        '*.remarks'   => 'nullable',
    ]);

    $recordsToInsert = [];
    foreach ($request->all() as $data) {
        // Check if required fields exist for insertion
        // if (!empty($data['total']) && !empty($data['percentage']) && !empty($data['term']) && !empty($data['academicYear'])) {
            // Build core data
            $coreData = [
                'academic_year' => $data['academic_year'] ?? null,
                'roll_no'       => $data['roll_no'] ?? null,
                'name'          => $data['name'] ?? null,
                'standard'      => $data['standard'] ?? null,
                'section'       => $data['section'] ?? null,
                'term'          => $data['term'] ?? null,
                'group_no'      => $data['group'] ?? null,
                'total'      => $data['total'] ?? null,
                'percentage'      => $data['percentage'] ?? null,
                'remarks'      => $data['remarks'] ?? null,
               'subjects' => !empty(array_diff_key($data, array_flip([
    'academic_year', 'roll_no', 'name', 'standard', 'section', 'term', 'group', 'total', 'percentage','remarks'
]))) 
    ? json_encode(array_diff_key($data, array_flip([
        'academic_year', 'roll_no', 'name', 'standard', 'section', 'term', 'group', 'total', 'percentage','remarks'
    ]))) 
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
                    'academic_year' => $record['academic_year'],
                    'roll_no'       => $record['roll_no'],
                    'standard'      => $record['standard'],
                    'section'       => $record['section'],
                    'term'          => $record['term'],
                ],
                $record
            );
        }
    }

    return response()->json(['message' => 'Temporary marks saved successfully.'], 200);
}

    public function getTemporary(Request $request)
    {
        $query = \App\Models\TemporaryStudentMark::query();
    
        // Apply filters dynamically
        if ($request->has('term')) {
            $query->where('term', $request->query('term'));
        }
        if ($request->has('standard')) {
            $query->where('standard', $request->query('standard'));
        }
        if ($request->has('section')) {
            $query->where('section', $request->query('section'));
        }
        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->query('academic_year'));
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
            return response()->json(['message' => 'No data found for the given filters.'], 404);
        }
    
        // Return the response
        return response()->json(['data' => $datas], 200);
    }

  
    
    public function viewAll(Request $request)
    {
        // Build the query to fetch records
        $query = DB::table('student_mark_records');

        // Apply filters if present
        if ($request->has('term')) {
            $query->where('term', $request->query('term'));
        }

        if ($request->has('standard')) {
            $query->where('standard', $request->query('standard'));
        }

        if ($request->has('section')) {
            $query->where('section', $request->query('section'));
        }

        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->query('academic_year'));
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
        $query = DB::table('student_mark_records');

        if ($request->has('term')) {
            $query->where('term', $request->query('term'));
        }
        if ($request->has('standard')) {
            $query->where('standard', $request->query('standard'));
        }
        if ($request->has('section')) {
            $query->where('section', $request->query('section'));
        }
        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->query('academic_year'));
        }
        if ($request->has('roll_no')) {
            $query->where('roll_no', $request->query('roll_no'));
        }

        $student = $query->first();

        // Step 2: Check if student data exists
        if (!$student) {
            return response()->json(['message' => 'Student record not found'], 404);
        }
         $student_data = Student::where('roll_no',$request->query('roll_no'))->first();
         $fatherName = $student_data->FATHER;
        $motherName = $student_data->MOTHER;
        $dob = $student_data->DOB_DD_MM_YYYY;
        $admissionNo = $student_data->admission_no;
        $section = $student_data->sec;
        $termName = Term::where('id', $request->query('term'))->first()->name ?? null;;
        $template = TemplateMaster::where('template_name', 'Report Card')->first();
$logo = "https://santhoshavidhyalaya.com/svsportaladmintest/static/media/newlogo.f86bd51493e0e8166940.jpg";

        if (!$template) {
            return response()->json(['message' => 'Template not found'], 404);
        }

                            // Step 4: Parse the subjects JSON and prepare the marks table rows
                            $subjects = json_decode($student->subjects, true);
                            $remarks = $student->remarks ?? null;
                     $subjectNames = array_keys($subjects);
                    $subjectMarks = array_map(function ($mark) {
                        return is_numeric($mark) ? (int) $mark : 0; // Convert marks to integers or set to 0 for "absent"
                    }, array_values($subjects));
                    
                    // Assign unique colors for each bar
                    $barColors = ['FF6384', '36A2EB', 'FFCE56', '4BC0C0', '9966FF', 'FF9F40'];
                    $barColors = array_slice(array_merge($barColors, array_fill(0, max(0, count($subjectNames) - count($barColors)), '000000')), 0, count($subjectNames));
                    
                    // Generate the Image-Charts URL
                    $chartUrl = 'https://image-charts.com/chart?' . http_build_query([
                        'cht' => 'bvg', // Vertical bar graph
                        'chs' => '700x400', // Chart size
                        'chd' => 't:' . implode(',', $subjectMarks), // Chart data
                        'chxl' => '0:|' . implode('|', $subjectNames), // X-axis labels
                        'chxt' => 'x,y', // Display X and Y axis
                        'chco' => implode(',', $barColors), // Bar colors
                        'chbh' => '30,5,15', // Bar width and spacing
                        'chtt' => 'Student+Performance+Graph', // Title
                        'chts' => '000000,20', // Title style
                        'chds' => '0,100', // Y-axis range
                    ]);

        $marksRows = '';
        foreach ($subjects as $subject => $marks) {
            $marksRows .= "
                <tr>
                    <td>{$subject}</td>
                    <td>{$marks}</td>
                </tr>
            ";
        }

        // Step 5: Replace placeholders in the template
        $populatedTemplate = str_replace(
            ['${name}', '${roll_no}', '${academic_year}', '${term}', '${standard}', '${section}', '${marksRows}', '${totalMarks}', '${percentage}','${chartUrl}','${father}','${mother}','${dob}','${admission_no}','${term_name}','${logo}','${remarks}'],
            [
                $student->name,
                $student->roll_no,
                $student->academic_year,
                $student->term,
                $student->standard,
                $student->section,
                $marksRows,
                $student->total,
                $student->percentage . '%',
               $chartUrl,
               $fatherName,
               $motherName,
               $dob,
               $admissionNo,
               $termName,
               $logo,
               $remarks
            ],
            $template->template
        );

        // Step 6: Convert the populated HTML to PDF
$pdf = PDF::loadHTML($populatedTemplate);

        // Define a path to save the PDF
        $pdfPath = public_path("reports/report_card_{$student->roll_no}.pdf");

        // Save the PDF to the defined path
        $pdf->save($pdfPath);

        // Step 7: Return the PDF link as response
        return response()->json([
            'message' => 'Report card generated successfully',
            'pdf_link' => url("public/reports/report_card_{$student->roll_no}.pdf"),
            'remarks'=>$remarks,
            //'load'=>$populatedTemplate
            'name'=>$termName
        ]);
    
  }
    public function update(Request $request)
{
    // Validate the payload to ensure it's an array and contains required fields
    $validatedData = $request->validate([
        '*.id'            => 'required|integer',    // Ensure the 'id' is present and is an integer
        '*.academic_year'  => 'required|string',
        '*.roll_no'       => 'required|string',
        '*.name'          => 'required|string',
        '*.standard'      => 'required|string',
        '*.section'       => 'nullable|string',
        '*.term'          => 'required|string',
        '*.group'         => 'nullable|string',
        '*.remarks'         => 'nullable|string',
    ]);

    $records = [];

    // Iterate through each record in the payload
    foreach ($request->all() as $data) {
        // Extract core fields
        $coreData = [
            'academic_year' => $data['academic_year'],
            'roll_no'       => $data['roll_no'],
            'name'          => $data['name'],
            'standard'      => $data['standard'],
            'section'       => $data['section'] ?? null,
            'term'          => $data['term'],
            'group_no'      => $data['group'] ?? null,
            'total'         => $data['total'] ?? null,
            'percentage'    => $data['percentage'] ?? null,
            'remarks'    => $data['remarks'] ?? null,
            'updated_at'    => Carbon::now('Asia/Kolkata'),
        ];

        // Extract dynamic subject fields
        $subjects = array_diff_key($data, array_flip([
            'id',
            'academic_year',
            'roll_no',
            'name',
            'standard',
            'section',
            'term',
            'group',
            'total',
            'percentage',
            'concordinate_string',
            'remarks',
        ]));

        // Find the record by ID and update it, or create a new one if it doesn't exist
        $record = StudentMarkRecord::find($data['id']);  // Find the record by ID

        if ($record) {
            // If record exists, update the record
            $record->update(array_merge($coreData, [
                'subjects' => json_encode($subjects),
            ]));
        } else {
            // If the record doesn't exist, create a new one
            $record = StudentMarkRecord::create(array_merge($coreData, [
                'subjects' => json_encode($subjects),
            ]));
        }

        // Add the updated or created record to the response collection
        $records[] = $record;
    }

    // Return success response with all updated or created records
    return response()->json([
        'message' => 'Student mark records updated successfully.',
        'data'    => $records,
    ], 200);
}
}
