<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\HostelFeeMaster;
use Illuminate\Support\Facades\Validator;

class hostelfeesmasterController extends Controller
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
        $user = HostelFeeMaster::create($data);
        $data['created_by'] = User::find($request->created_by)->name ?? '';
        $reponse[] = $data;

        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $HostelFeeMasters = HostelFeeMaster::all(); // retrieve all sections from the 'sections' table

        $data = []; // create an empty array to hold the data
        // 'user_id', 'date', 'title', 'description', 'color',send_to

        foreach ($HostelFeeMasters as $HostelFeeMaster) {
            $data[] = [
                'id' => $HostelFeeMaster->id,
                'sub_heading' => $HostelFeeMaster->sub_heading,
                'created_by' => User::find($HostelFeeMaster->created_by)->name ?? ''
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

        $HostelFeeMaster = HostelFeeMaster::find($data['id']); // retrieve the HostelFeeMaster by ID

        if ($HostelFeeMaster) {
            $HostelFeeMaster->sub_heading = $data['sub_heading'];
            $HostelFeeMaster->created_by = $data['created_by'];
            $HostelFeeMaster->save(); // save the changes to the database
        }
        $HostelFeeMaster['created_by'] = User::find($HostelFeeMaster->created_by)->name ?? '';

        return response()->json(['data' => $HostelFeeMaster, 'message' => 'updated  successfully']);
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

        HostelFeeMaster::destroy($id); // delete
    }
}
