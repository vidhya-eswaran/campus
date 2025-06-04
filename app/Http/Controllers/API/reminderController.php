<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Reminder;
use Illuminate\Support\Facades\Validator;
// 'user_id', 'date', 'title', 'description', 'color',send_to
class reminderController extends Controller
{
    public function insert(Request $request)
    {
        // if (!Auth::guard('api')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'date' => 'nullable',
                'title' => 'required',
                'description' => 'required',
                'color' => 'nullable',
                'send_to' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }
        $data = $request->all();
        $user = Reminder::create($data);
        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $reminders = Reminder::all(); // retrieve all sections from the 'sections' table

        $data = []; // create an empty array to hold the data
        // 'user_id', 'date', 'title', 'description', 'color',send_to

        foreach ($reminders as $reminder) {
            $data[] = [
                'id' => $reminder->id,
                'user_id' => $reminder->user_id,
                'date' => $reminder->date,
                'title' => $reminder->title,
                'description' => $reminder->description,
                'color' => $reminder->color,
                'send_to' => $reminder->send_to,
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
                'user_id' => 'required',
                'date' => 'required',
                'title' => 'required',
                'description' => 'required',
                'color' => 'required',
                'send_to' => 'required',
            ]
        );
        $data = $request->all();

        $reminder = Reminder::find($data['id']); // retrieve the reminder by ID

        if ($reminder) {
            $reminder->user_id = $data['user_id'];
            $reminder->date = $data['date'];
            $reminder->title = $data['title'];
            $reminder->description = $data['description'];
            $reminder->color = $data['color'];
            $reminder->send_to = $data['send_to'];

            $reminder->save(); // save the changes to the database
        }
        return response()->json(['data' => $reminder, 'message' => 'updated  successfully']);
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

        Reminder::destroy($id); // delete
    }
}
