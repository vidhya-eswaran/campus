<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TwelvethGroup;
use App\Models\User;

use Illuminate\Support\Facades\Validator;

class TwelvethGroupController extends Controller
{

    public function insert(Request $request)
    {
        // if (!Auth::guard('api')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'group' => 'required',
                'group_des' => 'required',
                'created_by' => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }
        $data = $request->all();
        $user = TwelvethGroup::create($data);
        $data['created_by'] = User::find($request->created_by)->name ?? '';
        $data['id'] = $user->id;

        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $TwelvethGroups = TwelvethGroup::all(); // retrieve all sections from the 'sections' table

        $data = []; // create an empty array to hold the data
 

        foreach ($TwelvethGroups as $TwelvethGroup) {
            $data[] = [
                'id' => $TwelvethGroup->id,
                'group' => $TwelvethGroup->group,
                'group_des' => $TwelvethGroup->group_des,
                'created_by' =>  User::find($request->created_by)->name ?? ''
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
                'group' => 'required',
                'group_des' => 'required',
                'created_by' => 'required'
            ]
        );
        $data = $request->all();

        $TwelvethGroup = TwelvethGroup::find($data['id']); // retrieve the TwelvethGroup by ID

        if ($TwelvethGroup) {
            $TwelvethGroup->group= $data['group'];
            $TwelvethGroup->group_des = $data['group_des'];
            $TwelvethGroup->created_by = $data['created_by'];
            $TwelvethGroup->save(); // save the changes to the database
        }
        $TwelvethGroup['created_by'] = User::find($data['created_by'])->name ?? '';

        return response()->json(['data' => $TwelvethGroup, 'message' => 'updated  successfully']);
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

        TwelvethGroup::destroy($id); // delete
    }
}
