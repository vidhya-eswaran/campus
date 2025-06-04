<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PaymentModeMaster;
use Illuminate\Support\Facades\Validator;


class paymentmasterController extends Controller
{
    public function insert(Request $request)
    {
        // if (!Auth::guard('api')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'paymenttype' => 'required',
                'created_by' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }
        $data = $request->all();
        $user = PaymentModeMaster::create($data);
        $data['created_by'] = User::find($request->created_by)->name ?? '';
        $data['id'] = $user->id;

        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function read(Request $request)
    {

        $paymenttypes = PaymentModeMaster::all(); // retrieve all sections from the 'sections' table

        $data = []; // create an empty array to hold the data

        foreach ($paymenttypes as $paymenttype) {
            $data[] = [
                'id' => $paymenttype->id,
                'paymenttype' => $paymenttype->paymenttype,
                'created_by' => User::find($paymenttype->created_by)->name ?? ''
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
                'paymenttype' => 'required',
                'created_by' => 'required',
            ]
        );
        $data = $request->all();

        $paymenttype = PaymentModeMaster::find($data['id']); // retrieve the paymenttype by ID

        if ($paymenttype) {
            $paymenttype->paymenttype = $data['paymenttype'];
            $paymenttype->created_by = $data['created_by'];
            $paymenttype->save(); // save the changes to the database
        }
        $paymenttype['created_by'] = User::find($data['created_by'])->name ?? '';
        $paymenttype['id'] = $data['id'];

        return response()->json(['data' => $paymenttype, 'message' => 'updated  successfully']);
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

        PaymentModeMaster::destroy($id); // delete
    }
}
