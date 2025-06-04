<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SectionMaster;
use Illuminate\Support\Facades\Validator;

class sectionmasterController extends Controller
{
    public function insert(Request $request)
    {
        // if (!Auth::guard('api')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'section' => 'required',
                'created_by' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }
        $data = $request->all();
        $user = SectionMaster::create($data);
        $data['created_by'] = User::find($request->created_by)->name ?? '';
        $data['id'] = $user->id;
        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $sections = SectionMaster::all(); // retrieve all sections from the 'sections' table

        $data = []; // create an empty array to hold the data

        foreach ($sections as $section) {
            $data[] = [
                'id' => $section->id,
                'section' => $section->section,
                'created_by' => User::find($section->created_by)->name ?? ''
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
                'section' => 'required',
                'created_by' => 'required',
            ]
        );
        $data = $request->all();

        $section = SectionMaster::find($data['id']); // retrieve the section by ID

        if ($section) {
            $section->section = $data['section'];
            $section->created_by = $data['created_by'];
            $section->save(); // save the changes to the database
        }
        $section['created_by'] = User::find($data['created_by'])->name ?? '';
        $section['id'] = $data['id'];

        return response()->json(['data' => $section, 'message' => 'updated  successfully']);
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

        SectionMaster::destroy($id); // delete
    }
}
