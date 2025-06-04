<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DiscountCategoryMaster;
use Illuminate\Support\Facades\Validator;

class DiscountCategoryMasterController extends Controller
{

    public function insert(Request $request)
    {
        // if (!Auth::guard('api')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'discount_name' => 'required',
                'amount' => 'required',
                'feestype' => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'validator error'], 401);
        }
        $data = $request->all();
        $user = DiscountCategoryMaster::create($data);
        // $data['created_by'] = User::find($request->created_by)->name ?? '';
        // $data['id'] = $user->id;

        $reponse[] = $data;
        return response()->json($reponse, 200);
    }
    public function schoolread(Request $request)
    {

        // $Discountcat = DiscountCategoryMaster::where('feestype','school')->get(); // retrieve all classes from the 'classes' table
        $Discountcat = DiscountCategoryMaster::where('feestype', '=', 'school')->get();


        $data = []; // create an empty array to hold the data

        foreach ($Discountcat as $discount) {
            $data[] = [
                'id' => $discount->id,
                'discount_name' => $discount->discount_name,
                'discount_type' => $discount->discount_type,
                'amount' => $discount->amount,
                'date' => $discount->date,
            ];
        }
        // return response()->json(['data' => $data]);
        return response()->json($data);
    }
    
    public function hostelread(Request $request)
    {

        // $Discountcat = DiscountCategoryMaster::where('feestype','school')->get(); // retrieve all classes from the 'classes' table
        $Discountcat = DiscountCategoryMaster::where('feestype', '=', 'hostel')->get();

        $data = []; // create an empty array to hold the data

        foreach ($Discountcat as $discount) {
            $data[] = [
                'id' => $discount->id,
                'discount_name' => $discount->discount_name,
                'discount_type' => $discount->discount_type,
                'amount' => $discount->amount,
                'date' => $discount->date,
            ];
        }
        // return response()->json(['data' => $data]);
        return response()->json($data);
    }

    public function update(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'discount_name' => 'required',
                // 'discount_type' => 'required',
                'amount' => 'required',
            ]
        );
        $data = $request->all();

        $discountcat = DiscountCategoryMaster::find($data['id']); // retrieve the class by ID

        if ($discountcat) {
            $discountcat->discount_name = $data['discount_name'];
            // $discountcat->discount_type = $data['discount_type'];
            $discountcat->amount = $data['amount'];
            $discountcat->date = $data['date'];
            $discountcat->save(); // save the changes to the database
        }
        // $class['created_by'] = User::find($data['created_by'])->name ?? '';
        // $class['id'] = $data['id'];

        return response()->json(['data' => $discountcat, 'message' => 'updated  successfully']);
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

        DiscountCategoryMaster::destroy($id); // delete
         return response()->json(['message' => 'deleted  successfully']);
    }
}
