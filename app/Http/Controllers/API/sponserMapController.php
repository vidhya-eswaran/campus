<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class sponserMapController extends Controller
{
    public function mapstudents(Request $request)
    {
        $requestData = $request->all();
        $sponseriD = $requestData['sponseriD'];
        $Grade = $requestData['Grade'];
        $studentIDs = $requestData['student_id'];

        foreach ($studentIDs as $studentID) {
            $student = User::where('id', $studentID)->where('status', 1)->first();
            $sponser = User::where('id', $sponseriD)->where('status', 1)->first();

            if ($student && $sponser) {
                $student->fee_by = 'sponser';
                $student->sponser_id = $sponseriD;
                $student->save();
            }
        }

        $sponser = User::where('id', $sponseriD)->where('status', 1)->first();
        $response['Sponsername'] = $sponser->name ?? '';

        return response()->json($response, 200);
    }

    public function getSponser(Request $request)
    {
        $sponsors = User::where('user_type', 'sponser')->where('status', 1)->get();
        return response()->json(['data' => $sponsors]);
    }
    public function removemapstudents(Request $request)
    {
        $requestData = $request->all();
        $slno = $requestData['slno'];

        $student = User::where('slno', $slno)->first();

        if ($student) {
            $student->fee_by = 'parent';
            $student->sponser_id = null;
            $student->save();

            $response['response'] = 'Student removed from Sponsor successfully';
            return response()->json($response, 200);
        } else {
            $response['response'] = 'Student not found';
            return response()->json($response, 404);
        }
    }

    public function readmapstudents(Request $request)
    {
        $students = User::where('fee_by', 'sponser')->get();
        $data = [];

        foreach ($students as $student) {
            $sponsor = User::where('id', $student->sponser_id)->first();
            $sponsorName = $sponsor ? $sponsor->name : '';

            $data[] = [
                'slno' => $student->slno,
                'Sponsername' => $sponsorName,
                'grade' => $student->standard,
                'sec' => $student->sec ?? '',
                'Studentname' => $student->name,
            ];
        }

        return response()->json(['data' => $data], 200);
    }
}
