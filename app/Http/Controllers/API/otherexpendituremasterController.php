<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\OtherExpenditureMaster;
use Illuminate\Support\Facades\Validator;

class otherexpendituremasterController extends Controller
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
        $user = OtherExpenditureMaster::create($data);
        $data['created_by'] = User::find($request->created_by)->name ?? '';
        $data['id'] = $user->id;

        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $OtherExpenditureMasters = OtherExpenditureMaster::all(); // retrieve all sections from the 'sections' table

        $data = []; // create an empty array to hold the data
        // 'user_id', 'date', 'title', 'description', 'color',send_to

        foreach ($OtherExpenditureMasters as $OtherExpenditureMaster) {
            $data[] = [
                'id' => $OtherExpenditureMaster->id,
                'sub_heading' => $OtherExpenditureMaster->sub_heading,
                'created_by' => User::find($OtherExpenditureMaster->created_by)->name ?? ''
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

        $OtherExpenditureMaster = OtherExpenditureMaster::find($data['id']); // retrieve the OtherExpenditureMaster by ID

        if ($OtherExpenditureMaster) {
            $OtherExpenditureMaster->sub_heading = $data['sub_heading'];
            $OtherExpenditureMaster->created_by = $data['created_by'];
            $OtherExpenditureMaster->save(); // save the changes to the database
        }
        $OtherExpenditureMaster['created_by'] = User::find($data['created_by'])->name ?? '';
        $OtherExpenditureMaster['id'] = $data['id'];

        return response()->json(['data' => $OtherExpenditureMaster, 'message' => 'updated  successfully']);
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

        OtherExpenditureMaster::destroy($id); // delete
    }
}
