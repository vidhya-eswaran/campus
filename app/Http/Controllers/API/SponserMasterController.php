<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SponserMaster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class SponserMasterController extends Controller
{
    public function insert(Request $request)
    {
        // 'name', 'occupation', 'company_name', 'location', 'email_id', 'phone', 'address1', 'address2', 'city', 'state', 'pincode', 'status', 'created_by'

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'occupation' => 'required',
                'company_name' => 'required',
                'location' => 'required',
                'email_id' => 'required',
                'phone' => 'required',
                'address1' => 'required',
                'address2' => 'required',
                'city' => 'required',
                'state' => 'required',
                'pincode' => 'required',
                'status' => 'required',
                'gst' => 'nullable',
                'pan' => 'nullable',
                'created_by' => 'nullable',


            ]
        );
$lastId = User::latest('id')->value('id');
$lastId = $lastId + 1;

// Check if the validator fails
if ($validator->fails()) {
    return response()->json(['message' => 'validator error'], 401);
}

// Retrieve all data from the request
$data = $request->all();

// Add user_id to the data array
$data['user_id'] = $lastId;

// Merge the updated data back into the request
$request->merge($data);

// Create the new SponserMaster record using the merged request data
$user = SponserMaster::create($request->all());
    //  $user = SponserMaster::create($request->all());


        $password = 'svs@123';

        $sponserData = [
            'name' => $data['name'],
            'email' => $data['email_id'],
            'user_type' => 'sponser',
            'id' => $lastId,
            'password' => Hash::make($password),
            'created_by' => $data['created_by']
        ];

        $sponsor = User::create($sponserData);
        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

      //  $SponserMasters = SponserMaster::all(); // retrieve all sections from the 'sections' table
$SponserMasters = SponserMaster::orderBy('id', 'desc')->get();

        $data = []; // create an empty array to hold the data
        // 'user_id', 'date', 'title', 'description', 'color',send_to

        foreach ($SponserMasters as $SponserMaster) {
            $data[] = [
                'id' => $SponserMaster->id,
                'name' => $SponserMaster->name,
                'occupation' => $SponserMaster->occupation,
                'company_name' => $SponserMaster->company_name,
                'location' => $SponserMaster->location,
                'email_id' => $SponserMaster->email_id,
                'phone' => $SponserMaster->phone,
                'address1' => $SponserMaster->address1,
                'address2' => $SponserMaster->address2,
                'city' => $SponserMaster->city,
                'state' => $SponserMaster->state,
                'pincode' => $SponserMaster->pincode,
                'status' => $SponserMaster->status == 1 ? 'Active' : 'Inactive',
                'gst' => $SponserMaster->gst ?? '',
                'pan' => $SponserMaster->pan ?? '',
                'created_by' => User::where('id', $SponserMaster->created_by)->value('name') ?? '',


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
                'name' => 'required',
                'occupation' => 'required',
                'company_name' => 'required',
                'location' => 'required',
                'email_id' => 'required',
                'phone' => 'required',
                'address1' => 'required',
                'address2' => 'required',
                'city' => 'required',
                'state' => 'required',
                'pincode' => 'required',
                'status' => 'required',
                 'gst' => 'nullable',
                'pan' => 'nullable',
            ]
        );
        $data = $request->all();

        $SponserMaster = SponserMaster::find($data['id']); // retrieve the SponserMaster by ID

        if ($SponserMaster) {
            $SponserMaster->name = $data['name'];
            $SponserMaster->occupation = $data['occupation'];
            $SponserMaster->company_name = $data['company_name'];
            $SponserMaster->location = $data['location'];
            $SponserMaster->email_id = $data['email_id'];
            $SponserMaster->phone = $data['phone'];
            $SponserMaster->address1 = $data['address1'];
            $SponserMaster->address2 = $data['address2'];
            $SponserMaster->city = $data['city'];
            $SponserMaster->state = $data['state'];
            $SponserMaster->pincode = $data['pincode'];
            $SponserMaster->gst = $data['gst'];
            $SponserMaster->pan = $data['pan'];
            $SponserMaster->status = $data['status'];

            $SponserMaster->save(); // save the changes to the database
            
           $user = User::where('email', $SponserMaster->email_id)
              //  ->where('name', $SponserMaster->name)
              ->where('user_type', 'sponser')
                ->first();
  $SponserMaster->save();
    if ($user) {
          $user->name =  $data['name'];  
        $user->email = $data['email_id'];  
        $user->updated_at = now();  
        $user->save();
    
    } }
        
        
        return response()->json(['data' => $SponserMaster, 'message' => 'updated  successfully']);
    }
    public function delete(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error'], 400);
        }
    
        $data = $request->all();
        $sponserMaster = SponserMaster::find($data['id']); // Retrieve the SponserMaster by ID
    
        if ($sponserMaster) {
            $sponserMaster->status = '0';
            $sponserMaster->save();
    
            // Update the associated User status
            $user = User::where('id', $sponserMaster->user_id)->first();
            if ($user) {
                $user->status = '0';
                $user->save();
            }
    
            return response()->json(['message' => 'SponserMaster deleted successfully'], 200);
        }
    
        return response()->json(['message' => 'SponserMaster not found'], 404);
    }
    public function ViewSponserID(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error'], 400);
        }
    
        $data = $request->all();
        $sponserMaster = SponserMaster::find($data['id']); // Retrieve the SponserMaster by ID
    
        if ($sponserMaster) {
            return response()->json(['data' =>$sponserMaster], 200);

        }
    
        return response()->json(['message' => 'SponserMaster not found'], 404);
    }
    
    
}
