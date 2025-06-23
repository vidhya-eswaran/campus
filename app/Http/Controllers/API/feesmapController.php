<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FeesMap;
use App\Models\StudentFeesMap;
use Illuminate\Support\Facades\Validator;
use App\Models\GenerateInvoiceView;
use App\Models\StudentFeeMapArray;
class feesmapController extends Controller
{

    public function insert(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'standard' => 'required',
                'group' => 'nullable',
                'amount' => 'required',
                'fees_heading' => 'required',
                'fees_sub_heading' => 'required',
                'due_date' => 'required',
                'acad_year' => 'required',
                'created_by' => 'required'
            ]
        );
        $std = $request->input('standard');
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }
        $data = $request->all();
        $user = FeesMap::create($data);
        $fee_id = $user->id;
        $userDatas = User::where('status', '=', 1)->where('standard', '=',  $request->input('standard'))->where('user_type', '=', 'student')->get();
        if ((int)count($userDatas) != 0) {
            if (isset($std)) {
                foreach ($userDatas as $userData) {
                    $dataFeeMap[] = [
                        'student_id' => $userData->id,
                        'roll_no' => $userData->roll_no,
                        'name' => $userData->name,
                        'standard' => $request->input('standard'),
                        'twe_group' => $request->input('group') ?? '',
                        'sec' => $userData->sec ?? '',
                        'hostelOrDay' => $userData->hostelOrDay,
                        'sponser_id' => $userData->sponser_id ?? '',
                        'email' => $userData->email,
                        'fee_id' => $fee_id,
                        'amount' => $request->input('amount'),
                        'fee_heading' => $request->input('fees_heading'),
                        'fee_sub_heading' => $request->input('fees_sub_heading'),
                        'date' => $request->input('due_date'),
                        'acad_year' => $request->input('acad_year'),
                        'created_by' => $request->input('created_by'),
                    ];
                }
            }
             $chunkSize = 50; // Adjust the chunk size as per your needs

            // Chunk the data into smaller batches for faster inserts
            $chunks = array_chunk($dataFeeMap, $chunkSize);

            // Perform bulk insert in batches
            foreach ($chunks as $chunk) {
                $insertedCount =  StudentFeesMap::insert($chunk);
                //  $successfullyInserted += $insertedCount;
            }


            $responseData = [
                //  'count' => $successfullyInserted,
                'message' => 'Data successfully inserted.',
            ];
        } else {
            $responseData = [
                //  'count' => $successfullyInserted,
                'message' => 'User not found for this standard.',
            ];
        }
        return response()->json($responseData, 200);
    }

    public function insertArray(Request $request)
    {

               $std = $request->input('std');
                $fees_heading = $request->input('Fee_Category_head');
                $fees_sub_heading = $request->input('Fee_Category');
                $acad_year = $request->input('newAcadYear');
                $date = $request->input('Cdate');


                $StudentFeeMapArray = new StudentFeeMapArray();
                $StudentFeeMapArray->std = $std;
                $StudentFeeMapArray->fees_heading = $fees_heading ;
                $StudentFeeMapArray->fees_sub_heading = json_encode($fees_sub_heading) ;
                $StudentFeeMapArray->acad_year = $acad_year ;
                $StudentFeeMapArray->date = $date ;

                $StudentFeeMapArray->save();
             //   return response()->json($fees_sub_heading, 200);
             $fee_id = $StudentFeeMapArray->id;

                //$fees_sub_heading= json_encode($fees_sub_heading);
                $dataFeeMap = []; // Initialize $dataFeeMap before the foreach loop

                foreach ($fees_sub_heading as $feeData) {
                    $fee = new FeesMap();
                    $fee->standard = $std;
                    $fee->group = '';
                    $fee->amount = $feeData['amount'];
                    $fee->fees_heading = $fees_heading;
                    $fee->fees_sub_heading = $feeData['sub_heading'];
                    $fee->due_date = $date;
                    $fee->Priority = $feeData['Priority'];
                    $fee->acad_year = $acad_year;
                    $fee->created_by = '';
                    $fee->save();

                 //   $fee_id =  $fee->id;

                    // Append data to $dataFeeMap inside the loop
                    // Adjusted the code to use $feeData instead of $fee
                    $userDatas = User::where('status', '=', 1)
                                    ->where('standard', '=',  $std)
                                    ->where('user_type', '=', 'student')
                                    ->get();

                    if ($userDatas->count() != 0) {
                        foreach ($userDatas as $userData) {
                            $dataFeeMap[] = [
                                'student_id' => $userData->id,
                                'roll_no' => $userData->roll_no,
                                'name' => $userData->name,
                                'standard' =>$std,
                                'twe_group' =>  '',
                                'sec' => $userData->sec ?? '',
                                'hostelOrDay' => $userData->hostelOrDay,
                                'sponser_id' => $userData->sponser_id ?? '',
                                'email' => $userData->email,
                                'fee_id' => $fee_id,
                                'amount' =>  $feeData['amount'], // Use $feeData instead of $fee
                                'fee_heading' =>$fees_heading,
                                'fee_sub_heading' => $feeData['sub_heading'], // Use $feeData instead of $fee
                                'Priority' =>$feeData['Priority'],
                                'date' => $date,
                                'acad_year' => $acad_year,
                                'created_by' =>'',
                            ];
                        }
                    }
                }

                $chunkSize = 50; // Adjust the chunk size as per your needs

                // Chunk the data into smaller batches for faster inserts
                $chunks = array_chunk($dataFeeMap, $chunkSize);

                // Perform bulk insert in batches
                foreach ($chunks as $chunk) {
                    StudentFeesMap::insert($chunk);
                }

                $responseData = ['message' => 'Data successfully inserted.'];

                //////////////////////////////////////////////////////////////////////////////

        return response()->json($responseData, 200);
    }



    public function insertbyIDArray(Request $request)
    {
        $std = $request->input('std');
        $fees_heading = $request->input('Fee_Category_head');
        $fees_sub_heading = $request->input('Fee_Category');
        $acad_year = $request->input('newAcadYear');
        $date = $request->input('Cdate');
        $studentIds = $request->input('student_id');

        $userDatas = User::whereIn('id', $studentIds)
            ->where('status', 1)
            ->where('user_type', 'student')
            ->get();

        if ($userDatas->count() > 0) {
            foreach ($userDatas as $userData) {
                $StudentFeeMapArray = new StudentFeeMapArray();
                $StudentFeeMapArray->student_id = $userData->id;
                $StudentFeeMapArray->roll_no = $userData->roll_no;
                $StudentFeeMapArray->name = $userData->name;
                $StudentFeeMapArray->std = $userData->standard;
                $StudentFeeMapArray->fees_heading = $fees_heading;
                $StudentFeeMapArray->fees_sub_heading = json_encode($fees_sub_heading);
                $StudentFeeMapArray->acad_year = $acad_year;
                $StudentFeeMapArray->date = $date;
                $StudentFeeMapArray->save();

                // Save related data using the ID of the main data
                $fee_id = $StudentFeeMapArray->id;

                $dataFeeMap = [];
                foreach ($fees_sub_heading as $feeData) {
                    $dataFeeMap[] = [
                        'student_id' => $userData->id,
                        'roll_no' => $userData->roll_no,
                        'name' => $userData->name,
                        'standard' => $std,
                        'twe_group' => '',
                        'sec' => $userData->sec ?? '',
                        'hostelOrDay' => $userData->hostelOrDay,
                        'sponser_id' => $userData->sponser_id ?? '',
                        'email' => $userData->email,
                        'fee_id' => $fee_id,
                        'amount' => $feeData['amount'], // Use $feeData->amount instead of $feeData['amount']
                        'fee_heading' => $fees_heading,
                        'fee_sub_heading' =>$feeData['sub_heading'], // Use $feeData->sub_heading instead of $feeData['sub_heading']
                        'Priority' =>$feeData['Priority'],
                        'date' => $date,
                        'acad_year' => $acad_year,
                        'created_by' => '',
                    ];
                }

                $chunkSize = 50; // Adjust the chunk size as per your needs

                // Chunk the data into smaller batches for faster inserts
                $chunks = array_chunk($dataFeeMap, $chunkSize);

                // Perform bulk insert in batches
                foreach ($chunks as $chunk) {
                    StudentFeesMap::insert($chunk);
                }
            }

            $responseData = ['message' => 'Data successfully inserted.'];
        } else {
            $responseData = ['message' => 'User not found.'];
        }

        return response()->json($responseData, 200);
    }







// public function insertByID(Request $request)
//     {
//         // if (!Auth::guard('api')->check()) {
//         //     return response()->json(['error' => 'Unauthorized'], 401);
//         // }        'standard', 'group', 'amount', 'fees_heading', 'fees_sub_heading', 'date', 'status', 'created_by', 'created_at'

//         $validator = Validator::make(
//             $request->all(),
//             [
//                 'student_id' => 'required',
//                 'standard' => 'nullable',
//                 'amount' => 'required',
//                 'fees_heading' => 'required',
//                 'fees_sub_heading' => 'required',
//                 'due_date' => 'required',
//                 'acad_year' => 'required',
//                 'created_by' => 'required'
//             ]
//         );
//         $std = $request->input('standard');



//         if ($validator->fails()) {
//             return response()->json(['message' => 'validator error'], 401);
//         }
//         $data = $request->all();
//         // $user = FeesMap::create($data);
//         // $fee_id = $user->id;
//         $userDatas = User::where('id', '=', $request->input('student_id'))->where('status', '=', 1)->where('standard', '=',  $request->input('standard'))->where('user_type', '=', 'student')->get();
//         if ((int)count($userDatas) != 0) {
//             if (isset($std)) {
//                 foreach ($userDatas as $userData) {
//                     $dataFeeMap[] = [
//                         'student_id' => $userData->id,
//                         'roll_no' => $userData->roll_no,
//                         'name' => $userData->name,
//                         'standard' => $request->input('standard'),
//                         'twe_group' => $request->input('group'),
//                         'sec' => $userData->sec,
//                         'hostelOrDay' => $userData->hostelOrDay,
//                         'sponser_id' => $userData->sponser_id,
//                         'email' => $userData->email,
//                         'amount' => $request->input('amount'),
//                         'fee_heading' => $request->input('fees_heading'),
//                         'fee_sub_heading' => $request->input('fees_sub_heading'),
//                         'date' => $request->input('due_date'),
//                         'acad_year' => $request->input('acad_year'),
//                         'created_by' => $request->input('created_by'),
//                     ];
//                 }
//             }

//             foreach ($dataFeeMap as $chunk) {
//                 $insertedCount =  StudentFeesMap::insert($chunk);
//              }


//             $responseData = [
//                  'message' => 'Data successfully inserted.',
//             ];
//         } else {
//             $responseData = [
//                  'message' => 'User not found for this standard.',
//             ];
//         }
//         return response()->json($responseData, 200);
//     }
public function insertByID(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'student_id' => 'required|array',
            'standard' => 'nullable',
            'amount' => 'required',
            'fees_heading' => 'required',
            'fees_sub_heading' => 'required',
            'due_date' => 'required',
            'acad_year' => 'required',
            'created_by' => 'required'
        ]
    );

    if ($validator->fails()) {
        return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 400);
    }

    $studentIds = $request->input('student_id');
    $dataFeeMap = [];

    $userDatas = User::whereIn('id', $studentIds)
        ->where('status', 1)
        ->where('user_type', 'student')
        ->get();

    if ($userDatas->count() > 0) {
        foreach ($userDatas as $userData) {
            $dataFeeMap[] = [
                'student_id' => $userData->id,
                'roll_no' => $userData->roll_no,
                'name' => $userData->name,
                'standard' => $userData->standard ,
                'twe_group' => $userData->twe_group ,
                'sec' => $userData->sec,
                'hostelOrDay' => $userData->hostelOrDay,
                'sponser_id' => $userData->sponser_id,
                'email' => $userData->email,
                'amount' => $request->input('amount'),
                'fee_heading' => $request->input('fees_heading'),
                'fee_sub_heading' => $request->input('fees_sub_heading'),
                'date' => $request->input('due_date'),
                'acad_year' => $request->input('acad_year'),
                'created_by' => $request->input('created_by'),
            ];
        }

        // Bulk insert into StudentFeesMap
        $insertedCount = StudentFeesMap::insert($dataFeeMap);

        $responseData = [
            'message' => 'Data successfully inserted.',
            'inserted_count' => $insertedCount,
        ];
    } else {
        $responseData = [
            'message' => 'User not found.',
        ];
    }

    return response()->json($responseData, 200);
}

    public function read(Request $request)
    {

        $fees = FeesMap::all(); // retrieve all fees from the 'fees' table

        $data = []; // create an empty array to hold the data
        //  'standard', 'group', 'amount', 'fees_heading', 'fees_sub_heading', 'date', 'status', 'created_by', 'created_at'

        foreach ($fees as $fee) {
            $data[] = [
                'id' => $fee->id,
                'standard' => $fee->standard,
                'group' => $fee->group,
                'amount' => $fee->amount,
                'fees_heading' => $fee->fees_heading,
                'fees_sub_heading' => $fee->fees_sub_heading,
                'status' => $fee->status,
                'due_date' => $fee->due_date,
                'acad_year' => $fee->acad_year,
                'created_by' => $fee->created_by,
                'created_at' => $fee->created_at,
            ];
        }
        return response()->json(['data' => $data]);
    }

    public function update(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                 'amount' => 'required',
                'fee_heading' => 'required',
                'fee_sub_heading' => 'nullable',
                 'acad_year' => 'required',

                            ]
        );
        $data = $request->all();

        $fee = StudentFeesMap::find($data['id']); // retrieve the fee by ID

        if ($fee) {
             $fee->amount = $data['amount'];
            // $fee->fee_heading = $data['fee_heading'];
            // $fee->fee_sub_heading = $data['fee_sub_heading'];
             $fee->acad_year = $data['acad_year'];
              $fee->save(); // save the changes to the database
        }
        return response()->json(['data' => $fee, 'message' => 'updated  successfully']);
    }
    public function delete(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
            ]
        );
        $data = $request->all();

        $id =  $data['id']; // replace with the ID of the data that you want to delete

        FeesMap::destroy($id); // delete
    }
    public function deleteforStudent(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
            ]
        );
        $data = $request->all();

        $id =  $data['id']; // replace with the ID of the data that you want to delete

        GenerateInvoiceView::destroy($id); // delete
    }

    public function fetchByStandard(Request $request)
    {
        $standard = $request->standard;
        $skip = $request->start ?? 0;
        $take = $request->length ?? 5;
        $searchValue = $request->search['value'] ?? ''; // Get the search query

        $query = StudentFeesMap::where('standard', $standard);

        // Apply search filter if a search query is provided
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                      ->orWhere('roll_no', 'like', '%' . $searchValue . '%')
                      ->orWhere('acad_year', 'like', '%' . $searchValue . '%')
                      ->orWhere('fee_heading', 'like', '%' . $searchValue . '%')
                      ->orWhere('fee_sub_heading', 'like', '%' . $searchValue . '%')
                      ->orWhere('amount', 'like', '%' . $searchValue . '%')
                      ->orWhere('date', 'like', '%' . $searchValue . '%');
            });
        }

        $count = $query->count();

        $students = $query
            ->orderBy('slno', 'desc')
            ->skip($skip)
            ->take($take)
            ->get();

        foreach ($students as $key => $student) {
            $user = User::find($student->created_by);
            $students[$key]['created_by'] = $user->name ?? '';
            $textColor = $student['invoice_generated'] == 0 ? 'red' : 'green';
            $students[$key]['name'] = "<span style='color: {$textColor}'>{$student['name']}</span>";
        }

        return response()->json(['data' => $students, 'recordsFiltered' => $count, 'recordsTotal' => $count]);
    }

    public function fetchByhostel(Request $request)
    {
        // $standard = $request->standard;
        $skip = request('start') != null ? request('start') : 0;
        $take = request('length') != null ? request('length') : 5;

        //$students = StudentFeesMap::where('standard', $standard)->skip($skip)->take($take)->get();
        $students = StudentFeesMap::where(function ($query) {
            $query->where('fee_heading', 'Other hostel and Educational Expenditure')
                ->orWhere('fee_heading', 'Hostel Bill');
        })
        ->orderBy('slno', 'desc')
        ->skip($skip)
        ->take($take)
        ->get();


        $count = StudentFeesMap::where(function ($query) {
            $query->where('fee_heading', 'Other hostel and Educational Expenditure')
                ->orWhere('fee_heading', 'Hostel Bill');
        })
        ->orderBy('slno', 'desc')
        ->count();


        foreach ($students as $key => $student) {
            $user = User::find($student->created_by);
            $students[$key]['created_by'] = $user->name ?? '';
            $textColor = $student['invoice_generated'] == 0 ? 'red' : 'green';
            $students[$key]['name'] = "<span style='color: {$textColor}'>{$student['name']}</span>";

        }

        return response()->json(['data' => $students, 'recordsFiltered' => $count, 'recordsTotal' => $count]);
    }
    public function fetchByid(Request $request, $id)
    {
        $id = $request->id;
        $students = StudentFeesMap::where('slno', $id)->get();
        return response()->json($students);
    }
    public function delByid(Request $request, $id)
    {
        $id = $request->id;
        StudentFeesMap::destroy($id); // delete
        return response()->json('delete done');
    }
}
