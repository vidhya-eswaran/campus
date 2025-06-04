<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SponserMaster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetNotification;
use App\Models\Student;
use Carbon\Carbon;

class listUserController extends Controller
{
    public function read(Request $request)
    {

        $users = User::all(); // retrieve all users from the 'users' table

        $data = []; // create an empty array to hold the data
        //  SELECT `slno`, `id`, `roll_no`, `name`, `email`, `password`, `user_type`, `fee_by`, `sponser_id`, `status`, `remember_token`, `created_at`, `updated_at` FROM `users` WHERE 1
        foreach ($users as $user) {
            $data[] = [
                'slno' => $user->slno,
                'id' => $user->id,
                'roll_no' => $user->roll_no,
                'name' => $user->name,
                'gender' => $user->gender,
                'standard' => $user->standard,
                'twe_group' => $user->twe_group,
                'sec' => $user->sec,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'fee_by' => $user->fee_by,
                'sponser_id' => $user->sponser_id,
                'sponser_name' => SponserMaster::find($user->sponser_id)->name ?? '',
                'status' => $user->status,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'hostelOrDay' => $user->hostelOrDay
            ];
        }
        return response()->json(['data' => $data]);
    }
    
    public function listRoles(Request $request)
    {

       $userTypes = User::select('user_type')->distinct()->pluck('user_type');
        return response()->json(['data' => $userTypes]);
    }
    
    public function roleBasedUsers(Request $request)
    {
        // Get filter inputs
        $userType = $request->input('user_type');
        $standard = $request->input('standard');
        $sec = $request->input('sec');
        $tweGroup = $request->input('twe_group');
    
        // Start query
        $query = User::query();
    
        // Apply filters only if the parameter is provided
        if (!empty($userType)) {
            $query->where('user_type', $userType);
        }
        if (!empty($standard)) {
            $query->where('standard', $standard);
        }
        if (!empty($sec)) {
            $query->where('sec', $sec);
        }
        if (!empty($tweGroup)) {
            $query->where('twe_group', $tweGroup);
        }
    
        // Get filtered users
        $users = $query->get();
    
        // Format user data
        $data = $users->map(function ($user) {
            $admission_no = $user->admission_no;
            $existingStudent = Student::where('admission_no',  'LIKE', $admission_no)->first();
            return [
                'slno' => $user->slno,
                'id' => $user->id,
                'roll_no' => $user->roll_no,
                'name' => $user->name,
                'gender' => $user->gender,
                'standard' => $user->standard,
                'twe_group' => $user->twe_group,
                'sec' => $user->sec,
                'email' => $user->email,
                'fee_by' => $user->fee_by,
                'role' => $user->user_type,
                'sponser_id' => $user->sponser_id,
                'sponser_name' => SponserMaster::find($user->sponser_id)->name ?? '',
                'status' => $user->status,
                'mobile_number' => $existingStudent->MOBILE_NUMBER ?? '',
                'mother_name' => $existingStudent->MOTHER ?? '',
                'father_name' => $existingStudent->FATHER ?? '',
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'hostelOrDay' => $user->hostelOrDay
            ];
        });
    
        return response()->json([
            'filters' => [
                'user_type' => $userType ?? 'All',
                'standard' => $standard ?? 'All',
                'sec' => $sec ?? 'All',
                'twe_group' => $tweGroup ?? 'All',
            ],
            'users' => $data
        ]);
    }

    public function sponserUser(Request $request)
    {

        $users = User::where('user_type', '=', 'sponser')->where('status', '=', 1)->get(); // retrieve all users from the 'users' table

        $data = []; // create an empty array to hold the data
        //  SELECT `slno`, `id`, `roll_no`, `name`, `email`, `password`, `user_type`, `fee_by`, `sponser_id`, `status`, `remember_token`, `created_at`, `updated_at` FROM `users` WHERE 1
        foreach ($users as $user) {
            $data[] = [
                'slno' => $user->slno,
                'id' => $user->id,
                // 'roll_no' => $user->roll_no,
                'name' => $user->name,
                // 'gender' => $user->gender,
                // 'standard' => $user->standard,
                // 'sec' => $user->sec,
                'email' => $user->email,
                'user_type' => $user->user_type,
                // 'fee_by' => $user->fee_by,
                // 'sponser_id' => $user->sponser_id,
                // 'sponser_name' => SponserMaster::find($user->sponser_id)->name ?? '',
                'status' => $user->status,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'hostelOrDay' => $user->hostelOrDay
            ];
        }
        return response()->json(['data' => $data]);
    }
    public function changeUserDetails(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'roll_no' => 'required',
                'name' => 'required',
                'gender' => 'nullable',
                'standard' => 'nullable',
                'sec' => 'nullable',
                'twe_group' => 'nullable',
                'email' => 'required',
                'user_type' => 'required',
                // 'fee_by' => 'nullable',
                // 'sponsor_id' => 'nullable',
                // 'sponsor_name' => 'nullable',
                'status' => 'nullable',
                'hostelOrDay' => 'nullable'
            ]
        );

        $data = $request->all();

        $user = User::find($data['id']); // retrieve the user by ID


        // if ($user) {
        //     $user->roll_no = $data['roll_no'];
        //     $user->name = $data['name'];
        //     $user->gender = $data['gender'];
        //     $user->standard = $data['standard'];
        //     $user->sec = $data['sec'];
        //     $user->twe_group = $data['twe_group'];
        //     $user->hostelOrDay = $data['hostelOrDay'];
        //     $user->email = $data['email'];
        //     $user->user_type = $data['user_type'];
        //     // $user->fee_by = $data['fee_by'];
        //     // $user->sponsor_id = $data['sponsor_id'];
        //     $user->status = $data['status'];
        //     $user->save(); // save the changes to the database
        //     if ($data['user_type'] == 'student') {
        //          $admission_no =  $user->admission_no ;
        //          $existingStudent = Student::where('admission_no', 'like', $admission_no)->first();
        //          $existingStudent->sec =  $data['sec'];
        //          $existingStudent->sought_Std = $data['standard'];
        //          $existingStudent->Group = $data['twe_group'];
        //          $existingStudent->student_name = $data['name'];
        //          $existingStudent->roll_no =  $data['roll_no'];
        //          $existingStudent->EmailID = $data['email'];
        //          $existingStudent->hostelOrDay = $data['hostelOrDay'];
        //          $existingStudent->save();

        //     }  
        // }


        if ($user) {
            if (!empty($data['roll_no'])) {
                $user->roll_no = $data['roll_no'];
            }

            if (!empty($data['name'])) {
                $user->name = $data['name'];
            }

            if (!empty($data['gender'])) {
                $user->gender = $data['gender'];
            }

            if (!empty($data['standard'])) {
                $user->standard = $data['standard'];
            }

            if (!empty($data['sec'])) {
                $user->sec = $data['sec'];
            }
            if (!empty($data['twe_group'])) {
                $user->twe_group = $data['twe_group'];
            }

            if (!empty($data['hostelOrDay'])) {
                $user->hostelOrDay = $data['hostelOrDay'];
            }

            if (!empty($data['email'])) {
                $user->email = $data['email'];
            }
            // return response()->json(['message' => 'updated data successfully','user' =>$user ]);
            $user->save(); 
            if (!empty($data['user_type'])) {
                $user->user_type = $data['user_type'];

                // Update student-specific fields if the user is a student
                if ($data['user_type'] == 'student') {
                    $admission_no = $user->admission_no;
                    $existingStudent = Student::where('admission_no',  'LIKE', $admission_no)->first();

                    if ($existingStudent) {
                        if (!empty($data['sec'])) {
                            $existingStudent->sec = $data['sec'];
                        }

                        if (!empty($data['standard'])) {
                            $existingStudent->SOUGHT_STD = $data['standard'];
                        }

                        if (!empty($data['twe_group'])) {
                            $existingStudent->GROUP_12 = $data['twe_group'];
                        }

                        if (!empty($data['name'])) {
                            $existingStudent->STUDENT_NAME = $data['name'];
                        }

                        if (!empty($data['roll_no'])) {
                            $existingStudent->roll_no = $data['roll_no'];
                        }

                        if (!empty($data['email'])) {
                            $existingStudent->EMAIL_ID = $data['email'];
                        }

                        // if (!empty($data['hostelOrDay'])) {
                        //     $existingStudent->hostelOrDay = $data['hostelOrDay'];
                        // }

                        $existingStudent->save();
                    }
                }
            }
        }
        return response()->json(['message' => 'updated data successfully','existingStudent' =>$existingStudent ?? '','user' =>$user ]);
    }
    public function IdUserDetails(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
            ]
        );

        $data = $request->all();

        $user = User::find($data['id']); // retrieve the user by ID

        return response()->json(['data' => $user]);
    }
    public function changepassword(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'new_password' => 'required',
                'old_password' => 'required'

            ]
        );

        $data = $request->all();
        $new_password_H = Hash::make($data['new_password']);
        $newPassword = 'Please make sure you dont share your new password to anyone.';

        $user = User::find($data['id']); // retrieve the user by ID

        if ($user) {
            if (Hash::check($data['old_password'], $user['password'])) {
                $user->password = $new_password_H;
                $user->save(); // save the changes to the database
                Mail::to($user->email)->send(new PasswordResetNotification($user->email, $user->name, $newPassword));
                return response()->json(['message' => $user->name]);
            } else {
                return response()->json(['message' => 'validator error'], 401);
            }
        }
    }



    public function resetpassword(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required'
            ]
        );
        $data = $request->all();
        $new_password_H = Hash::make('svs@123');
        $newPassword = 'New Password: svs@123';
        $user = User::find($data['id']); // retrieve the user by ID

        if ($user) {
            $user->password = $new_password_H;
            $user->save(); // save the changes to the database
            Mail::to($user->email)->send(new PasswordResetNotification($user->email, $user->name, $newPassword));
        }
        return response()->json(['message' => 'Resetted password successfully', 'email' =>  $user->email, 'name' =>  $user->name]);
    }
}
