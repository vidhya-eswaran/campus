<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SchoolFeeMaster;
use Illuminate\Support\Facades\Validator;

class schoolfeesmasterController extends Controller
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
        $user = SchoolFeeMaster::create($data);
        $data['created_by'] = User::find($request->created_by)->name ?? '';
        $data['id'] = $user->id;

        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $SchoolFeeMasters = SchoolFeeMaster::all(); // retrieve all sections from the 'sections' table

        $data = []; // create an empty array to hold the data
        // 'user_id', 'date', 'title', 'description', 'color',send_to

        foreach ($SchoolFeeMasters as $SchoolFeeMaster) {
            $data[] = [
                'id' => $SchoolFeeMaster->id,
                'sub_heading' => $SchoolFeeMaster->sub_heading,
                'created_by' => User::find($SchoolFeeMaster->created_by)->name ?? ''
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

        $SchoolFeeMaster = SchoolFeeMaster::find($data['id']); // retrieve the SchoolFeeMaster by ID

        if ($SchoolFeeMaster) {
            $SchoolFeeMaster->sub_heading = $data['sub_heading'];
            $SchoolFeeMaster->created_by = $data['created_by'];
            $SchoolFeeMaster->save(); // save the changes to the database
        }
        $SchoolFeeMaster['created_by'] = User::find($data['created_by'])->name ?? '';
        $SchoolFeeMaster['id'] = $data['id'];

        return response()->json(['data' => $SchoolFeeMaster, 'message' => 'updated  successfully']);
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

        SchoolFeeMaster::destroy($id); // delete
    }
}
