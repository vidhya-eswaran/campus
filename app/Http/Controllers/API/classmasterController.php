<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ClassMaster;
use Illuminate\Support\Facades\Validator;

class classmasterController extends Controller
{

    public function insert(Request $request)
    {
        // if (!Auth::guard('api')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'class' => 'required',
                'created_by' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }
        $data = $request->all();
        $user = ClassMaster::create($data);
        $data['created_by'] = User::find($request->created_by)->name ?? '';
        $data['id'] = $user->id;

        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $classes = ClassMaster::all(); // retrieve all classes from the 'classes' table

        $data = []; // create an empty array to hold the data

        foreach ($classes as $class) {
            $data[] = [
                'id' => $class->id,
                'class' => $class->class,
                'created_by' => User::find($class->created_by)->name ?? ''
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
                'class' => 'required',
                'created_by' => 'required',
            ]
        );
        $data = $request->all();

        $class = ClassMaster::find($data['id']); // retrieve the class by ID

        if ($class) {
            $class->class = $data['class'];
            $class->created_by = $data['created_by'];
            $class->save(); // save the changes to the database
        }
        $class['created_by'] = User::find($data['created_by'])->name ?? '';
        $class['id'] = $data['id'];

        return response()->json(['data' => $class, 'message' => 'updated  successfully']);
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

        ClassMaster::destroy($id); // delete
    }
}
