<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SchoolMiscellaneousBillMaster;
use Illuminate\Support\Facades\Validator;

class schoolmiscellaneousbillmasterController extends Controller
{
    public function insert(Request $request)
    {
        // if (!Auth::guard('api')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'sub_heading' => 'required',
                'created_by' => 'required'

            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }
        $data = $request->all();
        $user = SchoolMiscellaneousBillMaster::create($data);
        $data['created_by'] = User::find($request->created_by)->name ?? '';
        $data['id'] = $user->id;

        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $SchoolMiscellaneousBillMasters = SchoolMiscellaneousBillMaster::all(); // retrieve all sections from the 'sections' table

        $data = []; // create an empty array to hold the data
        // 'user_id', 'date', 'title', 'description', 'color',send_to

        foreach ($SchoolMiscellaneousBillMasters as $SchoolMiscellaneousBillMaster) {
            $data[] = [
                'id' => $SchoolMiscellaneousBillMaster->id,
                'sub_heading' => $SchoolMiscellaneousBillMaster->sub_heading,
                'created_by' => User::find($SchoolMiscellaneousBillMaster->created_by)->name ?? ''
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
                'sub_heading' => 'required',
                'created_by' => 'required'
            ]
        );
        $data = $request->all();

        $SchoolMiscellaneousBillMaster = SchoolMiscellaneousBillMaster::find($data['id']); // retrieve the SchoolMiscellaneousBillMaster by ID

        if ($SchoolMiscellaneousBillMaster) {
            $SchoolMiscellaneousBillMaster->sub_heading = $data['sub_heading'];
            $SchoolMiscellaneousBillMaster->created_by = $data['created_by'];
            $SchoolMiscellaneousBillMaster->save(); // save the changes to the database
        }
        $SchoolMiscellaneousBillMaster['created_by'] = User::find($data['created_by'])->name ?? '';
        $SchoolMiscellaneousBillMaster['id'] = $data['id'];

        return response()->json(['data' => $SchoolMiscellaneousBillMaster, 'message' => 'updated  successfully']);
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

        SchoolMiscellaneousBillMaster::destroy($id); // delete
    }
}
