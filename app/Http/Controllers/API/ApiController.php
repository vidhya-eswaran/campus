<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\OtpDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Models\Student;
use App\Models\Staff;
use App\Models\SponserMaster;
use Carbon\Carbon;
use App\Helpers\ResponseHelper;

class ApiController extends Controller{
    public function lifecycle(Request $request)
    {
        $studentId =$request->student_id;
         $query = DB::table('lifecycle_logs')->orderBy('logged_at', 'desc');
    
        if (!empty($studentId)) {
            $query->where('student_id', $studentId);
        }
    
                return response()->json($query->get());
     
    }
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'gender' => 'nullable',
            'email' => 'required|email',
            'user_type' => 'required',
            'standard' => 'nullable',
            'sec' => 'nullable',
            'twe_group' => 'nullable',
            'hostelOrDay' => 'nullable',
            'roll_no' => 'nullable',
            'id' => 'nullable',
            'created_by' => 'nullable'


        ];
        if (!$request->has('password')) {
            // $name = $request->input('name');
            $name = 'svs@123'; // Appending "svs" and two special characters to the beginning of the name
            $request->merge(['password' => $name]);
        }
        if (!$request->has('roll_no') || empty($request->input('roll_no'))) {
            $lastRollNumber = User::latest('roll_no')->value('roll_no');
            $lastNumber = (int)substr($lastRollNumber, 2);
            $newNumber = $lastNumber + 1;
            $rollNumber = 'SV' . $newNumber;

            $data['roll_no'] = $rollNumber;
            $lastid = User::latest('id')->value('id');
            $lastid =  $lastid + 1;


            $request->merge(['id' => $lastid]);


            $request->merge(['roll_no' => $rollNumber]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }

        $data = $request->all();
        unset($data['c_password']);
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $reponse['token'] = $user->createToken('Myapp');
        $reponse['name'] = $user->name;
        $reponse['data'] = $data;

        return response()->json($reponse, 200);
    }


    public function admissionSearch(Request $request)
    {
         $admission_no = $request->input('admission_no');
         $query = User::query();
        if ($admission_no) {
            $query->where('admission_no', $admission_no);
        }
         $results = $query->orderBy('slno', 'desc')->get(['id', 'admission_no' , 'name']);
         return response()->json($results);
    }


    // public function login(Request $request)
    // {
    //     $credentials = [
    //         'password' => $request->input('password'),
    //     ];

    //     $input = $request->input('email');
    //     // Attempt authentication with email
    //     if (Auth::attempt(['email' => $input, 'password' => $credentials['password']])) {
    //         $user = Auth::user();


    //         $reponse['token'] = $user->createToken('Myapp');
    //         $reponse['user_type'] = $user->user_type ?? '';
    //         $reponse['name'] = $user->name ?? '';
    //         $reponse['email'] = $user->email ?? '';
    //         $reponse['id'] = $user->id ?? '';
    //         $reponse['gender'] = $user->gender ?? '';
    //         $reponse['standard'] = $user->standard ?? '';
    //         $reponse['sec'] = $user->sec ?? '';
    //         $reponse['fee_by'] = $user->fee_by ?? '';
    //         $reponse['sponser_id'] = $user->sponser_id ?? '';
    //         $reponse['roll_no'] =   '';
    //          $reponse['admission_no'] =   '';
    //          $reponse['presence'] =   '';
    //          $reponse['no_of_leaves'] =   '';
    //         $reponse['status'] = $user->status ?? '';
    //         if ($user->user_type === 'student') {
    //              $reponse['roll_no'] = $user->roll_no ?? '';
    //          $reponse['admission_no'] = $user->admission_no ?? '';
    //           $reponse['presence'] =   '97';
    //          $reponse['no_of_leaves'] =   '6';
    //             $existingStudent = Student::where('admission_no', 'like', $user->admission_no)->first();

    //             if ($existingStudent) {
    //                 $currentAddress = $existingStudent->c_HouseNumber . ', ' .
    //                     $existingStudent->c_StreetName . ', ' .
    //                     $existingStudent->c_VillageTownName . ', ' .
    //                     $existingStudent->c_Postoffice . ', ' .
    //                     $existingStudent->c_Taluk . ', ' .
    //                     $existingStudent->c_District . ', ' .
    //                     $existingStudent->c_State . ', ' .
    //                     $existingStudent->c_Pincode;

    //                 $mobile = $existingStudent->Mobilenumber;
    //                 $WhatsAppNo = $existingStudent->WhatsAppNo;
    //                 $permanentAddress = $existingStudent->p_housenumber . ', ' .
    //                     $existingStudent->p_Streetname . ', ' .
    //                     $existingStudent->p_VillagetownName . ', ' .
    //                     $existingStudent->p_Postoffice . ', ' .  $existingStudent->p_Taluk . ', ' .
    //                     $existingStudent->p_District . ', ' .
    //                     $existingStudent->p_State . ', ' .
    //                     $existingStudent->p_Pincode;

    //                 $combinedAddress = [
    //                     'current_address' => $currentAddress,
    //                     'permanent_address' => $permanentAddress,
    //                     'mobile' => $mobile,
    //                     'WhatsAppNo' => $WhatsAppNo,
    //                 ];

    //                 $reponse['student_info'] = $combinedAddress;
    //             } else {
    //                 $reponse['student_info'] = null; // Student not found
    //             }
    //         } elseif ($user->user_type === 'sponser') {
    //             $existingStudent = SponserMaster::where('name', 'like', $user->name)->first();
    //             if ($existingStudent) {
    //                 $permanentAddress = $existingStudent->address1 . ', ' . $existingStudent->address2;
    //                 $gst = $existingStudent->gst ?? '';
    //                 $pan = $existingStudent->pan ?? '';
    //                 $combinedAddress = [
    //                     'current_address' => '',
    //                     'permanent_address' => $permanentAddress,
    //                     'gst' => $gst,
    //                     'pan' => $pan,
    //                 ];
    //                 $reponse['student_info'] = $combinedAddress;
    //             }
    //         }
    //          elseif ($user->user_type === 'admin') {
    //              $reponse['staff_master_dd']  = Staff::where('email', 'like', $user->email)->first();
    //          }



    //         return response()->json($reponse, 200);
    //     } else {
            
    //         // Attempt authentication with admission number
    //         if (Auth::attempt(['roll_no' => $input, 'password' => $credentials['password']])) {
    //             $user = Auth::user();




    //             $reponse['token'] = $user->createToken('Myapp');
    //             $reponse['user_type'] = $user->user_type ?? '';
    //             $reponse['name'] = $user->name ?? '';
    //             $reponse['email'] = $user->email ?? '';
    //             $reponse['id'] = $user->id ?? '';
    //             $reponse['gender'] = $user->gender ?? '';
    //             $reponse['standard'] = $user->standard ?? '';
    //             $reponse['sec'] = $user->sec ?? '';
    //             $reponse['roll_no'] = $user->roll_no ?? '';
    //             $reponse['admission_no'] = $user->admission_no ?? '';
    //              $reponse['fee_by'] = $user->fee_by ?? '';
    //             $reponse['sponser_id'] = $user->sponser_id ?? '';
    //             $reponse['status'] = $user->status ?? '';
    //             if ($user->user_type === 'student') {
    //                 $existingStudent = Student::where('roll_no', 'like', $user->admission_no)->first();
    
    //                 if ($existingStudent) {
    //                     $currentAddress = $existingStudent->c_HouseNumber . ', ' .
    //                         $existingStudent->c_StreetName . ', ' .
    //                         $existingStudent->c_VillageTownName . ', ' .
    //                         $existingStudent->c_Postoffice . ', ' .
    //                         $existingStudent->c_Taluk . ', ' .
    //                         $existingStudent->c_District . ', ' .
    //                         $existingStudent->c_State . ', ' .
    //                         $existingStudent->c_Pincode;
    
    //                     $mobile = $existingStudent->Mobilenumber;
    //                     $WhatsAppNo = $existingStudent->WhatsAppNo;
    //                     $permanentAddress = $existingStudent->p_housenumber . ', ' .
    //                         $existingStudent->p_Streetname . ', ' .
    //                         $existingStudent->p_VillagetownName . ', ' .
    //                         $existingStudent->p_Postoffice . ', ' .  $existingStudent->p_Taluk . ', ' .
    //                         $existingStudent->p_District . ', ' .
    //                         $existingStudent->p_State . ', ' .
    //                         $existingStudent->p_Pincode;
    
    //                     $combinedAddress = [
    //                         'current_address' => $currentAddress,
    //                         'permanent_address' => $permanentAddress,
    //                         'mobile' => $mobile,
    //                         'WhatsAppNo' => $WhatsAppNo,
    //                     ];
    
    //                     $reponse['student_info'] = $combinedAddress;
    //                 } else {
    //                     $reponse['student_info'] = null; // Student not found
    //                 }
    //             } elseif ($user->user_type === 'sponser') {
    //                 $existingStudent = SponserMaster::where('name', 'like', $user->name)->first();
    //                 if ($existingStudent) {
    //                     $permanentAddress = $existingStudent->address1 . ', ' . $existingStudent->address2;
    //                     $gst = $existingStudent->gst ?? '';
    //                     $pan = $existingStudent->pan ?? '';
    //                     $combinedAddress = [
    //                         'current_address' => '',
    //                         'permanent_address' => $permanentAddress,
    //                         'gst' => $gst,
    //                         'pan' => $pan,
    //                     ];
    //                     $reponse['student_info'] = $combinedAddress;
    //                 }
    //             }

    //             return response()->json($reponse, 200);
    //         } else {
    //             // Attempt authentication with name
    //             // if (Auth::attempt(['name' => $input, 'password' => $credentials['password']])) {
    //             //     $user = Auth::user();



    //             //     $reponse['token'] = $user->createToken('Myapp');
    //             //     $reponse['user_type'] = $user->user_type ?? '';
    //             //     $reponse['name'] = $user->name ?? '';
    //             //     $reponse['email'] = $user->email ?? '';
    //             //     $reponse['id'] = $user->id ?? '';
    //             //     $reponse['gender'] = $user->gender ?? '';
    //             //     $reponse['standard'] = $user->standard ?? '';
    //             //     $reponse['sec'] = $user->sec ?? '';
    //             //     $reponse['fee_by'] = $user->fee_by ?? '';
    //             //     $reponse['sponser_id'] = $user->sponser_id ?? '';
    //             //     $reponse['status'] = $user->status ?? '';

    //             //     //  $reponse['id'] = $user->id;

    //             //     //   $reponse['token']->token->id

    //             //     return response()->json($reponse, 200);
    //             // } else {
    //             return response()->json(['message' => 'Invalid Credentials error'], 401);
    //             // Handle the failure scenario (e.g., display an error message)
    //             // }
    //         }
    //     }
    //     //     if (Auth::attempt($credentials)) {

    //     //     $user = Auth::user();


    //     //     $reponse['token'] = $user->createToken('Myapp');
    //     //     $reponse['user_type'] = $user->user_type;
    //     //     $reponse['name'] = $user->name;
    //     //     $reponse['email'] = $user->email;
    //     //     $reponse['id'] = $user->id;
    //     //     $reponse['gender'] = $user->gender;
    //     //     $reponse['standard'] = $user->standard;
    //     //     $reponse['sec'] = $user->sec;
    //     //     $reponse['fee_by'] = $user->fee_by;
    //     //     $reponse['sponser_id'] = $user->sponser_id;
    //     //     $reponse['status'] = $user->status;

    //     //     //  $reponse['id'] = $user->id;

    //     //     //   $reponse['token']->token->id

    //     //     return response()->json($reponse, 200);
    //     // } else {
    //     //     dd($credentials);
    //     //     return response()->json(['message' => 'Invalid Credentials error'], 401);
    //     // }
    // }
public function getMatchingUsersdd(Request $request)
{
    $input = trim($request->input('email'));
    // $password = $request->input('password');
    // $selectedUserId = $request->input('selected_user_id');

    // Validate request data
    $validator = Validator::make($request->all(), [
        'email' => 'required',
        // 'password' => 'required',
        // 'selected_user_id' => 'nullable|integer|exists:users,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // If a selected_user_id is provided, attempt to log in directly with that ID
 
    // If no selected_user_id is provided, perform the initial authentication attempt
    $matchingUsers = $this->getMatchingUsers($input);

    

    if ($matchingUsers->count() > 1) {
        $oldestUser = $matchingUsers->sortBy('id')->first();
        
        if ($oldestUser->user_type === 'admin' || $oldestUser->user_type === 'staff') {
            $response['staff_master_dd'] = Staff::where('email', $oldestUser->email)->first();
        }
        $response['matching_users'] = $this->filterMatchingUsers($matchingUsers);
        return response()->json($response, 200);

    } elseif ($matchingUsers->count() === 1) {
        $user = $matchingUsers->first();
        $tokenResult = $user->createToken('MyApp');
        $token = $tokenResult->accessToken;
        $response = [
 
            'user_type' => $user->user_type ?? '',
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'id' => $user->id ?? '',
            'gender' => $user->gender ?? '',
            'standard' => $user->standard ?? '',
            'sec' => $user->sec ?? '',
            'fee_by' => $user->fee_by ?? '',
            'sponser_id' => $user->sponser_id ?? '',
            'roll_no' => $user->roll_no ?? '',
            'admission_no' => $user->admission_no ?? '',
            'status' => $user->status ?? '',
         ];
        if ($user->user_type === 'admin' || $user->user_type === 'staff') {
            $response['staff_master_dd'] = Staff::where('email', $user->email)->first();
        }
        $response['matching_users'] = $this->filterMatchingUsers($matchingUsers);
        return response()->json($response, 200);
    } else {
        return response()->json(['message' => 'Invalid Credentials'], 401);
    }
}
public function login(Request $request)
{
    $input = trim($request->input('email'));
    $password = $request->input('password');
    $selectedUserId = $request->input('selected_user_id');

    // Validate request data
    $validator = Validator::make($request->all(), [
        'email' => 'required',
        'password' => 'required',
        'selected_user_id' => 'nullable|integer|exists:users,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // If a selected_user_id is provided, attempt to log in directly with that ID
    if ($selectedUserId) {
        $user = User::find($selectedUserId);
        if (!$user) {
            return response()->json(['message' => 'Invalid user ID provided.'], 400);
        }
        if (!Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid password for the selected user.'], 401);
        }

        $tokenResult = $user->createToken('MyApp');
        $token = $tokenResult->accessToken;
        $response = [
            'token' => $tokenResult->token,
            'accessToken' => $token, 
            'user' => [
            'role' => $user->user_type ?? '',
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'id' => $user->id ?? '',
            'gender' => $user->gender ?? '',
            'standard' => $user->standard ?? '',
            'sec' => $user->sec ?? '',
            'fee_by' => $user->fee_by ?? '',
            'sponser_id' => $user->sponser_id ?? '',
            'roll_no' => $user->roll_no ?? '',
            'admission_no' => $user->admission_no ?? '',
            'status' => $user->status ?? '',
            'message' => 'Login successful',
            ],
            'school_db' => [
                    'name' => $request->school
                ]
        ];

        if ($user->user_type === 'admin' || $user->user_type === 'staff') {
            $response['staff_master_dd'] = Staff::where('email', $user->email)->first();
        }

        // Include matching users (without password) in the response
        $matchingUsers = $this->getMatchingUsers($input);
        $response['matching_users'] = $this->filterMatchingUsers($matchingUsers);

        return response()->json($response, 200);
    }

    // If no selected_user_id is provided, perform the initial authentication attempt
    $matchingUsers = $this->getMatchingUsers($input);

    $matchingUsers = $matchingUsers->filter(function ($user) use ($password) {
        return Hash::check($password, $user->password);
    });

    if ($matchingUsers->count() > 1) {
        $oldestUser = $matchingUsers->sortBy('id')->first();
        $tokenResult = $oldestUser->createToken('MyApp');
        $token = $tokenResult->accessToken;
        $response = [
                            
            'token' => $tokenResult->token,
            'accessToken' => $token, 
            'user' => [
            'role' => $oldestUser->user_type ?? '',
            'name' => $oldestUser->name ?? '',
            'email' => $oldestUser->email ?? '',
            'id' => $oldestUser->id ?? '',
            'gender' => $oldestUser->gender ?? '',
            'standard' => $oldestUser->standard ?? '',
            'fee_by' => $oldestUser->fee_by ?? '',
            'sponser_id' => $oldestUser->sponser_id ?? '',
            'roll_no' => $oldestUser->roll_no ?? '',
            'admission_no' => $oldestUser->admission_no ?? '',
            'status' => $oldestUser->status ?? '',
            'message' => 'Login successful',
            ] ,
            'school_db' => [
                    'name' => $request->school
                ]
                
        ];
        if ($oldestUser->user_type === 'admin' || $oldestUser->user_type === 'staff') {
            $response['staff_master_dd'] = Staff::where('email', $oldestUser->email)->first();
        }
        $response['matching_users'] = $this->filterMatchingUsers($matchingUsers);
        return response()->json($response, 200);

    } elseif ($matchingUsers->count() === 1) {
        $user = $matchingUsers->first();
        $tokenResult = $user->createToken('MyApp');
        $token = $tokenResult->accessToken;
        $response = [
            'token' => $tokenResult->token,
            'accessToken' => $token, 
            'user' => [
            'role' => $user->user_type ?? '',
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'id' => $user->id ?? '',
            'gender' => $user->gender ?? '',
            'standard' => $user->standard ?? '',
            'sec' => $user->sec ?? '',
            'fee_by' => $user->fee_by ?? '',
            'sponser_id' => $user->sponser_id ?? '',
            'roll_no' => $user->roll_no ?? '',
            'admission_no' => $user->admission_no ?? '',
            'status' => $user->status ?? '',
            'message' => 'Login successful',
            ],
            'school_db' => [
                    'name' => $request->school
                ]
        ];
        if ($user->user_type === 'admin' || $user->user_type === 'staff') {
            $response['staff_master_dd'] = Staff::where('email', $user->email)->first();
        }
        $response['matching_users'] = $this->filterMatchingUsers($matchingUsers);
        return response()->json($response, 200);
    } else {
        return response()->json(['message' => 'Invalid Credentials'], 401);
    }
}
private function filterMatchingUsers($users)
{
    return $users->map(function ($user) {
        $userArray = $user->toArray();
        unset($userArray['password']);
        return $userArray;
    })->toArray();
}

public function getMatchingUsers($input)
{
    $usersByEmail = [];
    $usersByRollNo = [];
    $usersByMobile = [];
    $usersByMobileFromStudent = []; // Added to store users found via Student table


     if (is_numeric($input)) {
        //  Check if input is 8 to 10 digits.
        if (preg_match('/^\d{8,10}$/', $input)) {
            $students = Student::where('MOBILE_NUMBER', $input)->get(); // Get all students with this mobile number
            if ($students) {
                $rollNumbers = $students->pluck('roll_no')->toArray(); // Extract roll numbers
                $usersByRollNo = User::whereIn('roll_no', $rollNumbers)->get(); // Find users with those roll numbers
            }
        }
         else{
             $usersByRollNo = User::where('roll_no', $input)->get();
         }
     }
      else{
          $usersByEmail = User::where('email', $input)->get();
      }


    $matchingUsers = collect()
        ->merge($usersByEmail)
        ->merge($usersByRollNo)
        ->merge($usersByMobileFromStudent)
        ->unique('id')
        ->values();
    return $matchingUsers;
}



// public function login(Request $request)
// {
//     $input = trim($request->input('email'));
//     $password = $request->input('password');
//     $selectedUserId = $request->input('selected_user_id');

//     // Validate request data
//     $validator = Validator::make($request->all(), [
//         'email' => 'required',
//         'password' => 'required',
//         'selected_user_id' => 'nullable|integer|exists:users,id',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 400);
//     }

//     // If a selected_user_id is provided, attempt to log in directly with that ID
//     if ($selectedUserId) {
//         $user = User::find($selectedUserId);
//         if (!$user) {
//             return response()->json(['message' => 'Invalid user ID provided.'], 400);
//         }
//         if (!Hash::check($password, $user->password)) {
//             return response()->json(['message' => 'Invalid password for the selected user.'], 401);
//         }

//         $tokenResult = $user->createToken('MyApp');
//         $token = $tokenResult->accessToken; // Get the token string
//         return response()->json([
//             'token' => $token,
//             'user_type' => $user->user_type ?? '',
//             'name' => $user->name ?? '',
//             'email' => $user->email ?? '',
//             'id' => $user->id ?? '',
//             'gender' => $user->gender ?? '',
//             'standard' => $user->standard ?? '',
//             'sec' => $user->sec ?? '',
//             'fee_by' => $user->fee_by ?? '',
//             'sponser_id' => $user->sponser_id ?? '',
//             'roll_no' => $user->roll_no ?? '',
//             'admission_no' => $user->admission_no ?? '',
//             'status' => $user->status ?? '',
//             'message' => 'Login successful',
//         ], 200);
//     }

//     // If no selected_user_id is provided, perform the initial authentication attempt
//     $usersByEmail = User::where('email', $input)->get();
//     $usersByRollNo = User::where('roll_no', $input)->get();

//     // Fetch user by mobile number from student table and then find the corresponding user
//     $usersByMobile = [];
//     $student = Student::where('MOBILE_NUMBER', $input)->first();
//     if ($student) {
//         $user = User::where('id', $student->user_id)->get();
//         $usersByMobile = $user;
//     }

//     $matchingUsers = collect();
//     $matchingUsers = $matchingUsers->merge($usersByEmail);
//     $matchingUsers = $matchingUsers->merge($usersByRollNo);
//     $matchingUsers = $matchingUsers->merge($usersByMobile);
//     $matchingUsers = $matchingUsers->unique('id')->values();

//     $matchingUsers = $matchingUsers->filter(function ($user) use ($password) {
//         return Hash::check($password, $user->password);
//     });

//     if ($matchingUsers->count() > 1) {
//         $response = $matchingUsers->map(function ($user) {
//             return [
//                 'id' => $user->id ?? '',
//                 'name' => $user->name ?? '',
//                 'email' => $user->email ?? '',
//                 'user_type' => $user->user_type ?? '',
//                 'standard' => $user->standard ?? '',
//                 'sec' => $user->sec ?? '',
//                 'roll_no' => $user->roll_no ?? '',
//                 'admission_no' => $user->admission_no ?? '',
//             ];
//         });
//         return response()->json([
//             'users' => $response,
//             'message' => 'Multiple users found. Please select a user and send the user ID as selected_user_id in the next request.',
//         ], 200);
//     } elseif ($matchingUsers->count() === 1) {
//         $user = $matchingUsers->first();
//         $tokenResult = $user->createToken('MyApp');
//         $token = $tokenResult->accessToken; // Get the token string.  Important for older Passport versions
//         return response()->json([
//             'token' => $token,
//             'user_type' => $user->user_type ?? '',
//             'name' => $user->name ?? '',
//             'email' => $user->email ?? '',
//             'id' => $user->id ?? '',
//             'gender' => $user->gender ?? '',
//             'standard' => $user->standard ?? '',
//             'sec' => $user->sec ?? '',
//             'fee_by' => $user->fee_by ?? '',
//             'sponser_id' => $user->sponser_id ?? '',
//             'roll_no' => $user->roll_no ?? '',
//             'admission_no' => $user->admission_no ?? '',
//             'status' => $user->status ?? '',
//             'message' => 'Login successful',
//         ], 200);
//     } else {
//         return response()->json(['message' => 'Invalid Credentials'], 401);
//     }
// }






    public function detail()
    {
        $user = Auth::user();
        $reponse['user'] = $user;
        return response()->json($reponse, 200);
    }
    
 

    public function viewProfile(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Prepare the base response
        $response = [
            'user' => [
                'id' => $user->id,
    'admission_no' => $user->admission_no ?? null,
    'roll_no' => $user->roll_no ?? null,
    'name' => $user->name,
    'email' => $user->email,
    'user_type' => $user->user_type,
    'created_at' => $user->created_at,
    'updated_at' => $user->updated_at,
    'gender' => $user->gender ?? '',
    'standard' => $user->standard ?? '',
    'sec' => $user->sec ?? '',
    'roll_no' => $user->roll_no ?? '',
    'admission_no' => $user->admission_no ?? '',
    'fee_by' => $user->fee_by ?? '',
    'sponser_id' => $user->sponser_id ?? '',
    'status' => $user->status ?? '',
    'presence' => '97',
    'no_of_leaves' => '6'
            ],
            'user_type' => $user->user_type,
        ];

        // Add details based on user type
        if ($user->user_type === 'student') {
            // Query for student details
            $studentDetails = DB::selectOne(
                'SELECT id, roll_no, admission_no, STUDENT_NAME, date_form, MOTHERTONGUE, STATE, DOB_DD_MM_YYYY, SEX, BLOOD_GROUP, NATIONALITY, RELIGION, DENOMINATION, CASTE, CASTE_CLASSIFICATION, AADHAAR_CARD_NO, RATIONCARDNO, EMIS_NO, FOOD, chronic_des, medicine_taken, FATHER, OCCUPATION, MOTHER, mother_occupation, GUARDIAN, guardian_occupation, MOBILE_NUMBER, EMAIL_ID, WHATS_APP_NO, mother_email_id, guardian_contact_no, guardian_email_id, MONTHLY_INCOME, mother_income, guardian_income, PERMANENT_HOUSENUMBER, P_STREETNAME, P_VILLAGE_TOWN_NAME, P_DISTRICT, P_STATE, P_PINCODE, COMMUNICATION_HOUSE_NO, C_STREET_NAME, C_VILLAGE_TOWN_NAME, C_DISTRICT, C_STATE, C_PINCODE, CLASS_LAST_STUDIED, NAME_OF_SCHOOL, SOUGHT_STD, sec, syllabus, GROUP_12, group_no, second_group_no, LANG_PART_I, profile_photo, birth_certificate_photo, aadhar_card_photo, ration_card_photo, community_certificate, slip_photo, medical_certificate_photo, reference_letter_photo, church_certificate_photo, transfer_certificate_photo, admission_photo, payment_order_id, siblings, brother_1, brother_2, gender_1, gender_2, class_1, class_2, brother_3, gender_3, class_3, last_school_state, second_language_school, second_language, reference_name_1, reference_name_2, reference_phone_1, reference_phone_2, ORGANISATION, mother_organization, guardian_organization, created_at, updated_at, documents, admission_id, father_title, mother_title, status, upload_created_at, upload_updated_at FROM admitted_students WHERE admission_no = ? OR roll_no = ?',
                [$user->admission_no, $user->roll_no]
            );
     
     if (is_object($studentDetails)) {
         
         
    $admission_no = $user->admission_no;
    $studentDetails->photo = "https://www.santhoshavidhyalaya.com/SVSTEST/storage/app/public/photos/{$admission_no}.jpg";
    $response['details'] = $studentDetails;
    
     $photo = "https://www.santhoshavidhyalaya.com/SVSTEST/storage/app/public/photos/{$admission_no}.jpg";
        $name =  $user->name;
        $mobileNo = $studentDetails->MOBILE_NUMBER;

        $permanentAddress = [
            'PERMANENT_HOUSENUMBER' =>  $studentDetails->PERMANENT_HOUSENUMBER,
            'P_STREETNAME' =>  $studentDetails->P_STREETNAME,
            'P_VILLAGE_TOWN_NAME' =>  $studentDetails->P_VILLAGE_TOWN_NAME,
            'P_DISTRICT' =>  $studentDetails->P_DISTRICT,
            'P_STATE' =>  $studentDetails->P_STATE,
            'P_PINCODE' =>  $studentDetails->P_PINCODE,
        ];

        $communicationAddress = [
            'COMMUNICATION_HOUSE_NO' =>  $studentDetails->COMMUNICATION_HOUSE_NO,
            'C_STREET_NAME' => $studentDetails->C_STREET_NAME,
            'C_VILLAGE_TOWN_NAME' =>  $studentDetails->C_VILLAGE_TOWN_NAME,
            'C_DISTRICT' => $studentDetails->C_DISTRICT,
            'C_STATE' =>  $studentDetails->C_STATE,
            'C_PINCODE' =>  $studentDetails->C_PINCODE,
        ];
         $response['common'] = ResponseHelper::formatCommonResponse(
            $photo,
            $name,
            $mobileNo,
            $permanentAddress,
            $communicationAddress
        ); 
} else {
             $admission_no = $user->admission_no;
    $studentDetails = $this->getNullStudentDetails();
     $studentDetails['photo'] = "https://www.santhoshavidhyalaya.com/SVSTEST/storage/app/public/photos/{$admission_no}.jpg";
    $response['details'] = $studentDetails;
    
      $photo = "https://www.santhoshavidhyalaya.com/SVSTEST/storage/app/public/photos/{$admission_no}.jpg";
        $name =  $user->name;
        $mobileNo = null;

        $permanentAddress = [
            'PERMANENT_HOUSENUMBER' => null,
            'P_STREETNAME' =>   null,
            'P_VILLAGE_TOWN_NAME' =>  null,
            'P_DISTRICT' =>  null,
            'P_STATE' =>  null,
            'P_PINCODE' => null,
        ];

        $communicationAddress = [
            'COMMUNICATION_HOUSE_NO' =>   null,
            'C_STREET_NAME' =>  null,
            'C_VILLAGE_TOWN_NAME' =>  null,
            'C_DISTRICT' => null,
            'C_STATE' => null,
            'C_PINCODE' =>  null,
        ];
         $response['common'] = ResponseHelper::formatCommonResponse(
            $photo,
            $name,
            $mobileNo,
            $permanentAddress,
            $communicationAddress
        ); 
}

            // $response['details'] = $studentDetails ? $studentDetails : $this->getNullStudentDetails();
        } elseif ($user->user_type === 'admin') {
            // Query for admin details
            $adminDetails = DB::selectOne(
                'SELECT id, staffName, staffId, designation, mobileNo, email, permanentAddress, communicationAddress, staff_photo, date_of_joining, status, isdeleted, created_at, updated_at FROM staff WHERE email = ? OR staffName LIKE ?',
                [$user->email, "%{$user->name}%"]
            );
  if ($adminDetails) {
                $adminDetails->permanentAddress = json_decode($adminDetails->permanentAddress, true);
                $adminDetails->communicationAddress = json_decode($adminDetails->communicationAddress, true);
                  if ($adminDetails->staff_photo) {
        $adminDetails->photo = asset('storage/app/public/' . $adminDetails->staff_photo);
    } else {
        $adminDetails->photo = null; // If no photo, set to null
    }
          
              
      $photo = asset('storage/app/public/' . $adminDetails->staff_photo);
        $name =  $user->name;
        $mobileNo = null;

        $permanentAddress = [
            'PERMANENT_HOUSENUMBER' => $adminDetails->permanentAddress['addressLine1'],
            'P_STREETNAME' => $adminDetails->permanentAddress['addressLine2'],
            'P_VILLAGE_TOWN_NAME' => $adminDetails->permanentAddress['city'],
            'P_DISTRICT' =>  null,
            'P_STATE' => $adminDetails->permanentAddress['state'],
            'P_PINCODE' =>  $adminDetails->permanentAddress['pincode'],
        ];

        $communicationAddress = [
            'COMMUNICATION_HOUSE_NO' =>   $adminDetails->communicationAddress['addressLine1'],
            'C_STREET_NAME' => $adminDetails->communicationAddress['addressLine2'],
            'C_VILLAGE_TOWN_NAME' => $adminDetails->communicationAddress['city'],
            'C_DISTRICT' => null,
            'C_STATE' =>$adminDetails->communicationAddress['state'],
            'C_PINCODE' => $adminDetails->communicationAddress['pincode'],
        ];
         $response['common'] = ResponseHelper::formatCommonResponse(
            $photo,
            $name,
            $mobileNo,
            $permanentAddress,
            $communicationAddress
        ); 
                
            }
            
            $response['details'] = $adminDetails ? $adminDetails : $this->getNullAdminDetails();
        } else {
            $response['details'] = null;
            
            $photo = null ;
        $name =  $user->name;
        $mobileNo = null;

        $permanentAddress = [
            'PERMANENT_HOUSENUMBER' => null,
            'P_STREETNAME' =>   null,
            'P_VILLAGE_TOWN_NAME' =>  null,
            'P_DISTRICT' =>  null,
            'P_STATE' =>  null,
            'P_PINCODE' => null,
        ];

        $communicationAddress = [
            'COMMUNICATION_HOUSE_NO' =>   null,
            'C_STREET_NAME' =>  null,
            'C_VILLAGE_TOWN_NAME' =>  null,
            'C_DISTRICT' => null,
            'C_STATE' => null,
            'C_PINCODE' =>  null,
        ];
           $response['common'] = ResponseHelper::formatCommonResponse(
            $photo,
            $name,
            $mobileNo,
            $permanentAddress,
            $communicationAddress
        ); 
             
        }

        return response()->json($response);
    }

    /**
     * Get a template with null values for student details.
     */
    private function getNullStudentDetails()
    {
        return [
            'id' => null,
            'roll_no' => null,
            'admission_no' => null,
            'STUDENT_NAME' => null,
            'date_form' => null,
            'MOTHERTONGUE' => null,
            'STATE' => null,
            'DOB_DD_MM_YYYY' => null,
            'SEX' => null,
            'BLOOD_GROUP' => null,
            'NATIONALITY' => null,
            'RELIGION' => null,
            'DENOMINATION' => null,
            'CASTE' => null,
            'CASTE_CLASSIFICATION' => null,
            'AADHAAR_CARD_NO' => null,
            'RATIONCARDNO' => null,
            'EMIS_NO' => null,
            'FOOD' => null,
            'chronic_des' => null,
            'medicine_taken' => null,
            'FATHER' => null,
            'OCCUPATION' => null,
            'MOTHER' => null,
            'mother_occupation' => null,
            'GUARDIAN' => null,
            'guardian_occupation' => null,
            'MOBILE_NUMBER' => null,
            'EMAIL_ID' => null,
            'WHATS_APP_NO' => null,
            'mother_email_id' => null,
            'guardian_contact_no' => null,
            'guardian_email_id' => null,
            'MONTHLY_INCOME' => null,
            'mother_income' => null,
            'guardian_income' => null,
            'PERMANENT_HOUSENUMBER' => null,
            'P_STREETNAME' => null,
            'P_VILLAGE_TOWN_NAME' => null,
            'P_DISTRICT' => null,
            'P_STATE' => null,
            'P_PINCODE' => null,
            'COMMUNICATION_HOUSE_NO' => null,
            'C_STREET_NAME' => null,
            'C_VILLAGE_TOWN_NAME' => null,
            'C_DISTRICT' => null,
            'C_STATE' => null,
            'C_PINCODE' => null,
            'CLASS_LAST_STUDIED' => null,
            'NAME_OF_SCHOOL' => null,
            'SOUGHT_STD' => null,
            'sec' => null,
            'syllabus' => null,
            'GROUP_12' => null,
            'group_no' => null,
            'second_group_no' => null,
            'LANG_PART_I' => null,
            'profile_photo' => null,
            'birth_certificate_photo' => null,
            'aadhar_card_photo' => null,
            'ration_card_photo' => null,
            'community_certificate' => null,
            'slip_photo' => null,
            'medical_certificate_photo' => null,
            'reference_letter_photo' => null,
            'church_certificate_photo' => null,
            'transfer_certificate_photo' => null,
            'admission_photo' => null,
            'payment_order_id' => null,
            'siblings' => null,
            'brother_1' => null,
            'brother_2' => null,
            'gender_1' => null,
            'gender_2' => null,
            'class_1' => null,
            'class_2' => null,
            'brother_3' => null,
            'gender_3' => null,
            'class_3' => null,
            'last_school_state' => null,
            'second_language_school' => null,
            'second_language' => null,
            'reference_name_1' => null,
            'reference_name_2' => null,
            'reference_phone_1' => null,
            'reference_phone_2' => null,
            'ORGANISATION' => null,
            'mother_organization' => null,
            'guardian_organization' => null,
            'created_at' => null,
            'updated_at' => null,
            'documents' => null,
            'admission_id' => null,
            'father_title' => null,
            'mother_title' => null,
            'status' => null,
            'upload_created_at' => null,
            'upload_updated_at' => null
        ];
    }

    /**
     * Get a template with null values for admin details.
     */
    private function getNullAdminDetails()
        {
            return [
                'id' => null,
                'staffName' => null,
                'staffId' => null,
                'designation' => null,
                'mobileNo' => null,
                'email' => null,
                'permanentAddress' => null,
                'communicationAddress' => null,
                'staff_photo' => null,
                'date_of_joining' => null,
                'status' => null,
                'isdeleted' => null,
                'created_at' => null,
                'updated_at' => null
            ];
        }
     

   
       public function StudentByStandard($standard)
        { 
    
            $students = User::where('standard', '=', $standard)->where('status', '=', 1)->get();
    
            $response = [];
            foreach ($students as $student) {
                $response[] = [
                    'id' => $student->id,
                    'roll_no' => $student->roll_no,
                    'name' => $student->name,
                    'std' => $student->standard,
                    'concordinate_string' => $student->roll_no . ' - ' . $student->name
                ];
            }
    
            return response()->json($response);
        }
    public function searchStudents(Request $request)
        {
            $search = $request->input('search');
        
            // Query students by roll_no, name, or standard
           $students = User::where('status', 1)
        ->where(function ($query) use ($search) {
            $query->where('roll_no', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('standard', 'LIKE', "%$search%");
        })
        ->where('user_type', 'student') 
        ->get();
    
        
            $response = [];
            foreach ($students as $student) {
                 $admittedStudent = Student::where('roll_no', $student->roll_no)->first();
                $response[] = [
                    'id' => $student->id,
                    'roll_no' => $student->roll_no,
                    'name' => $student->name,
                    'sec' => $student->sec ?? null,
                    'std' => $student->standard ?? null,  //Dinesh|12345|12|A   
                     'admission_no' => $student->admission_no ?? null, 
                    'hostel_name' => 'sv hostel', 
                    'father_name' => $admittedStudent->FATHER ?? null, 
                    'father_mobile_no' => $admittedStudent->MOBILE_NUMBER ?? null, 
                    'mother_name' => $admittedStudent->MOTHER ?? null, 
                    'mother_mobile_no' => $admittedStudent->WHATS_APP_NO ?? null, 
                    'concordinate_string' => $student->name . ' | ' . $student->roll_no. ' | ' . $student->standard. ' | ' . $student->sec
                ];
            }
        
            return response()->json($response);
        }

        // public function SearchStandardSec($standard, Request $request)
        // { 
        //     // Retrieve 'sec' from query parameters (optional)
        //     $sec = $request->query('sec');
        
        //     // Build the query
        //     $query = User::where('standard', '=', $standard)
        //                  ->where('status', '=', 1);
        
        //     // Add condition for 'sec' if provided
        //     if ($sec) {
        //         $query->where('sec', '=', $sec);
        //     }
        
        //     // Fetch the students
        //     $students = $query->get();
        
        //     // Prepare the response
        //     $response = $students->map(function ($student) {
        //         // Extract the year from the created_at field
        //         $createdYear = date('Y', strtotime($student->created_at));
        //         $academicYear = $createdYear . '-' . ($createdYear + 1);
        
        //         return [
        //             'id' => $student->id,
        //             'roll_no' => $student->roll_no,
        //             'name' => $student->name,
        //             'std' => $student->standard,
        //             'sec' => $student->sec,
        //             'group' => $student->twe_group,
        //             'academic_year' => $academicYear, // Added academic year field
        //             'concordinate_string' => $student->roll_no . ' |' . $student->name,
        //         ];
        //     });
        
        //     return response()->json($response);
        // }
        
        public function SearchStandardSec(Request $request)
        { 
            $standard = $request->query('standard');
            $sec = $request->query('sec');           
            $group = $request->query('group');
            $academicYear = $request->query('academic_year');
            $query = User::where('standard', '=', $standard)
                         ->where('status', '=', 1);

            if ($sec && $sec !== "null") {
                $query->where('sec', '=', $sec);
            }
            if ($group && $group !== "null") {
                $query->where('twe_group', '=', $group);
            }
        
            if ($academicYear && $academicYear !== "null") {
                $query->where('academic_year', '=', $academicYear);
            }
           
            $students = $query->get();

            //dd($students);
        
            // Prepare the response
            $response = $students->map(function ($student) {
                // Calculate academic year from created_at
                $createdYear = date('Y', strtotime($student->created_at));
                $nextYear = $createdYear + 1;
                $calculatedAcademicYear = $createdYear . '-' . $nextYear;
        
                // Check if academic_year is missing, update in DB
                if (!$student->academic_year) {
                    $student->academic_year = $calculatedAcademicYear;
                    $student->save(); // Save the updated academic year
                }
                
                return [
                    'id' => $student->id,
                    'roll_no' => $student->roll_no,
                    'name' => $student->name,
                    'std' => $student->standard,
                    'sec' => $student->sec,
                    'group' => $student->twe_group,
                    'academic_year' => $student->academic_year ?? $calculatedAcademicYear, // Use stored or calculated year
                    'concordinate_string' => $student->roll_no . ' | ' . $student->name,
                    'grade_status' => $student->grade_status,
                ];
            });
        
            return response()->json($response);
        }



public function ADSearchStandardSec(Request $request, $standard)
{
    $standard = $request->query('standard');
    $sec = $request->query('sec');
    $group = $request->query('group');
    $academicYear = $request->query('academic_year');

    // Use chunking only if huge dataset — else get() is okay
    $students = DB::table('admitted_students')
        ->where('std_sought',(int)  $standard)
        // ->where('status', 1)
        ->when($sec && $sec !== "null", fn($q) => $q->where('sec', $sec))
        ->when($group && $group !== "null", fn($q) => $q->where('group_first_choice', $group))
        ->when($academicYear && $academicYear !== "null", fn($q) => $q->where('academic_year', $academicYear))
        ->get();

    //dd($students);

    // If many records missing academic year, batch update instead of per-record
    $missing = $students->filter(fn($s) => !$s->academic_year);

    if ($missing->isNotEmpty()) {
        $updates = $missing->map(function ($student) {
            $year = date('Y', strtotime($student->created_at));
            $academic = $year . '-' . ($year + 1);
            return ['id' => $student->id, 'academic_year' => $academic];
        });

        // Bulk update using raw query (super fast)
        foreach ($updates as $u) {
            DB::table('admitted_students')->where('id', $u['id'])->update([
                'academic_year' => $u['academic_year']
            ]);
        }

        // Update the collection in memory
        $students->transform(function ($student) use ($updates) {
            $match = $updates->firstWhere('id', $student->id);
            if ($match) {
                $student->academic_year = $match['academic_year'];
            }
            return $student;
        });
    }

    return response()->json($students);
}


    public function withoutSponsorStandard($standard)
    {

        //   $validator = Validator::make(
        //    $request->all(),
        //  [
        //    'grade' => 'required',
        //    'section' => 'nullable'                ]
        // );

        //$data = $request->all();

        // $standard = $request->standard;
        // $sec = $request->section;

        $students = User::where('standard', '=', $standard)
                ->where('status', '=', 1)
                ->whereNull('sponser_id')
                 ->whereNotNull('roll_no')
                ->get();

        $response = [];
        foreach ($students as $student) {
            $response[] = [
                'id' => $student->id,
                'roll_no' => $student->roll_no,
                'name' => $student->name,
                'std' => $student->standard,
                'concordinate_string' => $student->roll_no . ' - ' . $student->name
            ];
        }

        return response()->json($response);
    }
    public function logout(Request $request)
    {
        // //$user = Auth::user()->token();
        // if (Auth::check()) {
        //     $user = Auth::user();
        //     $delete  = $user->name;

        //     Auth::user()->AauthAcessToken()->delete();
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',

            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        } else {
            $data = $request->all();
            $accessToken = \Laravel\Passport\Token::find($data['id']);
            $accessToken->revoke(); // revoke the token
            $accessToken->delete(); // delete the token
            return [
                'message' => 'You have successfully logged out and the token was successfully deleted'
            ];
        }
    }
    public function delByid(Request $request)
            {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'id' => 'required',
                    ]
                );
        
                if ($validator->fails()) {
                    return response()->json(['message' => 'Validator error'], 401);
                } else {
                    $data = $request->all();
                    //  $user = User::find($data['id']);
                    $user = User::where('slno', $data['id'])->first();
        
                    if (!$user) {
                        return response()->json(['message' => 'User not found'], 404);
                    }
        
                    // Check if the user email is not equal to "admin@123.com"
                    if ($user->email === 'admin@123.com') {
                        return response()->json(['message' => 'You cannot delete the admin user'], 403);
                    }
        
                    // Perform any additional checks or operations before deleting the user
                    // For example, you may check if the authenticated user has the necessary permissions
        
                    $user->delete();
        
                    return response()->json(['message' => 'User deleted successfully']);
                }
            }
    
        public function sendOtp(Request $request)
            {
                // Step 1: Validate that at least one of mobile or email is provided
                $validator = Validator::make($request->all(), [
                    'username' => 'required',  // Corrected the syntax here
                ]);
                // return $request->all();
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $validator->errors(),
                    ], 422);
                }
                if (preg_match('/^[6-9][0-9]{9}$/', $request->username)) {
                    $existingStudent = Student::where('MOBILE_NUMBER', 'like', $request->username)->first();
                    $users = User::where('roll_no', $existingStudent->roll_no)->orWhere('email', $existingStudent->EMAIL_ID)->first();
            } else {
                $users = User::where('roll_no', $request->username)->orWhere('email', $request->username)->first();
            }
                
                if($users){
            if ($users->user_type === 'student') {
                            $existingStudent = Student::where('admission_no', 'like', $users->admission_no)->first();
                // Step 2: Generate a 6-digit OTP
               $otp = rand(10000, 99999);
                
                // Step 3: Send OTP to mobile if it's provided
                if ($existingStudent->MOBILE_NUMBER != "") {
                    try {
                        $mobile = $existingStudent->MOBILE_NUMBER;
                        //   $student = Student::where('MOBILE_NUMBER', $mobile)->first();
                          $rollNo = $existingStudent->roll_no;
                          $Admissionno = $existingStudent->admission_no;
                        // $otpdetails = ([
                        //     'sendotp' => $otp,
                        //     'otp_created_at' => now(),
                        //     'rollNo' => $rollNo,
                        //     'Admissionno' => $Admissionno,
                        // ]);
                         if (!$existingStudent || (empty($existingStudent->MOBILE_NUMBER))) {
                            return response()->json([
                                'message' => 'Mobile Number Not found .',
                            ], 422);
                        }
                        $recipientName = $existingStudent->STUDENT_NAME; // You can dynamically set this based on your application
            
                // Define the SMS template
                $smsTemplate = "Dear {#name#}, your OTP for Santhosha Vidhyalaya student portal is {#otp#}. This code is valid for 10 minutes. Please do not share it with anyone.";
            
                // Replace placeholders in the template with actual values
                $message = str_replace(
                    ['{#name#}', '{#otp#}'],
                    [$recipientName, $otp],
                    $smsTemplate
                );
            
                        // Send SMS via SMSCountry or any other SMS service
                         $smsResponse = Http::withHeaders([
                                    'Content-Type' => 'application/json',
                                    'Authorization' => 'Basic MXpnQjhHdHZMNm5DR2ZaeEpKZ1E6Q1o4ZDVBNWNta2k1R0dZaWZlcE5tSG02ZGh1Z0Rwb3haT29TRWRMMQ==', // Replace with your BASIC AUTH string
                                ])->post('https://restapi.smscountry.com/v0.1/Accounts/1zgB8GtvL6nCGfZxJJgQ/SMSes/', [
                                    "Text" => $message,
                                    "Number" => $mobile,
                                    "SenderId" => "SANTHV",
                                    "DRNotifyUrl" => "https://www.domainname.com/notifyurl",
                                    "DRNotifyHttpMethod" => "POST",
                                    "Tool" => "API",
                                ]);
                        // Check SMS response status
                        if ($smsResponse->status() != 202) {
                            Log::error('SMS sending failed. Response: ' . $smsResponse->body());
                            return response()->json(['status' => 'error', 'message' => 'Failed to send OTP via SMS'], 500);
                        }
            
                        Log::info('SMS sent successfully. OTP: ' . $otp);
                    } catch (\Exception $e) {
                        Log::error('Error sending SMS: ' . $e->getMessage());
                        return response()->json(['status' => 'error', 'message' => 'Failed to send OTP via SMS'], 500);
                    }
                }
            
                // Step 4: Send OTP to email if it's provided
                if ($existingStudent->EMAIL_ID) {
                    try {
                        $email = $existingStudent->EMAIL_ID;
                        $student = Student::where('EMAIL_ID', $email)->first();
                        // session(['otp' => $otp, 'otp_created_at' => now(), 'rollNo' => $rollNo, 'Admissionno' => $Admissionno]);
                        // $otpdetails = ([
                        //     'sendotp' => $otp,
                        //     'otp_created_at' => now(),
                        //     'rollNo' => $rollNo,
                        //     'Admissionno' => $Admissionno,
                        // ]);
                        if (!$existingStudent || (empty($existingStudent->EMAIL_ID))) {
                            return response()->json([
                                'message' => 'Email ID Not found .',
                            ], 422);
                        }
                        $recipientName = $existingStudent->STUDENT_NAME; // You can dynamically set this based on your application
            
                // Define the SMS template
                $emailTemplate = "Dear {#name#}, your OTP for Santhosha Vidhyalaya student portal is {#otp#}. This code is valid for 10 minutes. Please do not share it with anyone.";
            
                // Replace placeholders in the template with actual values
                $message = str_replace(
                    ['{#name#}', '{#otp#}'],
                    [$recipientName, $otp],
                    $emailTemplate
                );
                        Mail::raw($message, function ($message) use ($email) {
                            $message->to($email)
                                ->subject('Your OTP');
                        });
            
                        Log::info('Email sent successfully to ' . $email . '. OTP: ' . $otp);
                    } catch (\Exception $e) {
                        Log::error('Error sending email: ' . $e->getMessage());
                        return response()->json(['status' => 'error', 'message' => 'Failed to send OTP via email'], 500);
                    }
                }
                $expiresAt = Carbon::now()->addMinutes(15); 
               OtpDetail::updateOrCreate(
                        ['user_id' => $request->username],
                        ['otp_code' => $otp, 'expires_at' => $expiresAt]
                    );
                    $otpdetails = ([
                            'username' => $request->username,
                            'expires_at' => $expiresAt,
                        ]);
                // Step 5: Return success response
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent successfully via SMS and/or email',
                    'otpdetails' => $otpdetails,
                ], 200);
            } else {
                    return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found',
                ], 500);
                }
                } else {
                    return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found',
                ], 500);
                }
            }
            
                 public function verifyotp(Request $request)
                {
                    try {
                        $otpdetails = OtpDetail::where('user_id', $request->username)->first();
                        // return $request->otp_code;
                        // $otp = Session::get('otp');
                        $inputOtp = $request->input('otp');
                        // $sessionOtp = session('otp');
                        $sessionOtp = $request->input('sendotp');
                        $otpCreatedAt = $request->input('otp_created_at');
                        $username = $request->input('username');
                        $admission_no = $request->input('Admissionno');
                        
                        
                        if ($otpdetails && $otpdetails->otp_code == $request->otp_code) {
                            if (now()->diffInMinutes($otpCreatedAt) <= 10) {
                                // OTP verification successful
                                 $query = User::query();
                                if ($username) {
                                    $query->where('roll_no', $username)->orWhere('email', $username);
                                }
                                $user = $query->first();
                                $reponse['token'] = $user->createToken('Myapp');
                                $reponse['user_type'] = $user->user_type ?? '';
                                $reponse['name'] = $user->name ?? '';
                                $reponse['email'] = $user->email ?? '';
                                $reponse['id'] = $user->id ?? '';
                                $reponse['gender'] = $user->gender ?? '';
                                $reponse['standard'] = $user->standard ?? '';
                                $reponse['sec'] = $user->sec ?? '';
                                $reponse['fee_by'] = $user->fee_by ?? '';
                                $reponse['sponser_id'] = $user->sponser_id ?? '';
                                $reponse['status'] = $user->status ?? '';
                                if ($user->user_type === 'student') {
                                    $existingStudent = Student::where('admission_no', 'like', $user->admission_no)->first();
                   
                                    if ($existingStudent) {
                                        $currentAddress = $existingStudent->c_HouseNumber . ', ' .
                                            $existingStudent->c_StreetName . ', ' .
                                            $existingStudent->c_VillageTownName . ', ' .
                                            $existingStudent->c_Postoffice . ', ' .
                                            $existingStudent->c_Taluk . ', ' .
                                            $existingStudent->c_District . ', ' .
                                            $existingStudent->c_State . ', ' .
                                            $existingStudent->c_Pincode;
                    
                                        $mobile = $existingStudent->Mobilenumber;
                                        $WhatsAppNo = $existingStudent->WhatsAppNo;
                                        $permanentAddress = $existingStudent->p_housenumber . ', ' .
                                            $existingStudent->p_Streetname . ', ' .
                                            $existingStudent->p_VillagetownName . ', ' .
                                            $existingStudent->p_Postoffice . ', ' .  $existingStudent->p_Taluk . ', ' .
                                            $existingStudent->p_District . ', ' .
                                            $existingStudent->p_State . ', ' .
                                            $existingStudent->p_Pincode;
                    
                                        $combinedAddress = [
                                            'current_address' => $currentAddress,
                                            'permanent_address' => $permanentAddress,
                                            'mobile' => $mobile,
                                            'WhatsAppNo' => $WhatsAppNo,
                                        ];
                    
                                        $reponse['student_info'] = $combinedAddress;
                                    } else {
                                        $reponse['student_info'] = null; // Student not found
                                    }
                                } elseif ($user->user_type === 'sponser') {
                                    $existingStudent = SponserMaster::where('name', 'like', $user->name)->first();
                                    if ($existingStudent) {
                                        $permanentAddress = $existingStudent->address1 . ', ' . $existingStudent->address2;
                                        $gst = $existingStudent->gst ?? '';
                                        $pan = $existingStudent->pan ?? '';
                                        $combinedAddress = [
                                            'current_address' => '',
                                            'permanent_address' => $permanentAddress,
                                            'gst' => $gst,
                                            'pan' => $pan,
                                        ];
                                        $reponse['student_info'] = $combinedAddress;
                                    }
                                }
                                // session()->forget(['otp', 'otp_created_at', 'rollNo', 'Admissionno']);
                                return response()->json($reponse, 200);
                                
                                // return response()->json(['status' => 'success', 'message' => 'OTP verified successfully'], 201);
                            } else {
                                // OTP expired
                                return response()->json(['status' => 'error', 'message' => 'OTP expired. Please try again.'], 500);
                            }
                        } else {
                            // Incorrect OTP
                            return response()->json(['status' => 'error', 'message' => 'Invalid OTP. Please try again.'], 500);
                        }
                    } catch (\Exception $e) {
                        // General exception
                        return response()->json(['status' => 'error', 'message' => 'OTP verification failed.', 'error' => $e->getMessage()], 500);
                    }
                }

}
