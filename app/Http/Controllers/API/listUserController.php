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

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'email' => 'required|email|unique:users,email',
            'user_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $lastid =User::latest("id")->value("id") ?? 0;
        $lastid = $lastid + 1;
        $user = new User();
        $user->name = $request->name;
        $user->id = $lastid;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->user_type = $request->user_type;
        $user->password = Hash::make("svs@123");
        $user->status = 1; // default active
        $user->save();

        // If student, add to students table
        if ($user->user_type === 'student') {
            $student = new Student();
            $student->admission_no = $user->admission_no ?? $user->id;
            $student->STUDENT_NAME = $user->name;
            $student->EMAIL_ID = $user->email;
            $student->save();
        }
        return response()->json([
            'status' => true,
            'message' => 'User created successfully.',
            'user' => $user
        ], 201);
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
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'name' => 'required|string',
            'gender' => 'nullable|string',
            'email' => 'required|email',
            'standard' => 'nullable|string', // From "Grade"
            'sec' => 'nullable|string',      // From "Section"
            'twe_group' => 'nullable|string' // From "Group"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'id', 'name', 'gender', 'email', 'standard', 'sec', 'twe_group'
        ]);

        $user = User::find($data['id']);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update user table
        $user->name = $data['name'];
        $user->gender = $data['gender'] ?? $user->gender;
        $user->email = $data['email'];
        $user->standard = $data['standard'] ?? $user->standard;
        $user->sec = $data['sec'] ?? $user->sec;
        $user->twe_group = $data['twe_group'] ?? $user->twe_group;
        $user->save();

        // If user is a student, also update the `students` table
        if ($user->user_type === 'student') {
            $student = Student::where('admission_no', $user->admission_no)->first();
            if ($student) {
                $student->STUDENT_NAME = $data['name'];
                $student->EMAIL_ID = $data['email'];
                $student->SOUGHT_STD = $data['standard'] ?? $student->SOUGHT_STD;
                $student->sec = $data['sec'] ?? $student->sec;
                $student->GROUP_12 = $data['twe_group'] ?? $student->GROUP_12;
                $student->save();
            }
        }

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
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

    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        // If the user is a student, delete from students table
        if ($user->user_type === 'student') {
            Student::where('EMAIL_ID', $user->email)->orWhere('admission_no', $user->id)->delete();
        }

        $user->delete();

        return response()->json(['status' => true, 'message' => 'User deleted successfully']);
    }

}
