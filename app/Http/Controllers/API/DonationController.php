<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonationList;

class DonationController extends Controller
{
    public function index()
    {
        $donors = DonationList::where("delete_status", 0)->get();
        return response()->json($donors);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            "heading" => "required|string|max:255",
            "mainDescription" => "required|string",
            "shortDescription" => "nullable|string",
            "amt1" => "required|numeric|min:0",
            "amt2" => "required|numeric|min:0",
            "amt3" => "required|numeric|min:0",
            "rangeSlide" => "nullable|string|max:200",
            "image" => "required", // max 2MB
        ]);

        // Handle image upload
        if ($request->hasFile("image")) {
            $file = $request->file("image");
            $fileName = time() . "_" . $file->getClientOriginalName();

            $destinationPath = public_path("donation"); // this resolves to public/donation

            // Create folder if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            // Image path for database (relative to public/)
            $imagePath = "donation/" . $fileName;
        }
        // dd($imagePath);
        // Create the donation record
        $donation = DonationList::create([
            "heading" => $validatedData["heading"],
            "main_description" => $validatedData["mainDescription"],
            "short_description" => $validatedData["shortDescription"] ?? null,
            "btn_amt_1" => $validatedData["amt1"],
            "btn_amt_2" => $validatedData["amt2"],
            "btn_amt_3" => $validatedData["amt3"],
            "range_slide" => $validatedData["rangeSlide"] ?? null,
            "image" => $imagePath ?? null, // Saved as donation/filename.jpg
        ]);

        return response()->json(
            [
                "message" => "Donation created successfully",
                "data" => $donation,
            ],
            201
        );
    }

    public function update(Request $request)
    {
        $id = $request->id;
        // Find the donation record
        $donation = DonationList::findOrFail($id);
        // Validate the request data
        $validatedData = $request->validate([
            "heading" => "nullable",
            "mainDescription" => "nullable",
            "shortDescription" => "nullable",
            "amt1" => "nullable",
            "amt2" => "nullable",
            "amt3" => "nullable",
            "rangeSlide" => "nullable",
            "image" => "nullable",
        ]);

        // Handle new image upload if provided
        if ($request->hasFile("image")) {
            // Delete old image if it exists
            if (
                $donation->image &&
                file_exists(public_path($donation->image))
            ) {
                unlink(public_path($donation->image));
            }

            $file = $request->file("image");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $destinationPath = public_path("donation");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $donation->image = "donation/" . $fileName;
        }

        // Update other fields
        $donation->heading = $validatedData["heading"];
        $donation->main_description = $validatedData["mainDescription"];
        $donation->short_description =
            $validatedData["shortDescription"] ?? null;
        $donation->btn_amt_1 = $validatedData["amt1"];
        $donation->btn_amt_2 = $validatedData["amt2"];
        $donation->btn_amt_3 = $validatedData["amt3"];
        $donation->range_slide = $validatedData["rangeSlide"] ?? null;

        $donation->save();

        return response()->json([
            "message" => "Donation updated successfully",
            "data" => $donation,
        ]);
    }

    public function viewbyid($id)
    {
        $donor = DonationList::findOrFail($id);
        return response()->json([
            "message" => "Donation retrieved successfully",
            "data" => $donor,
        ]);
    }

    public function destroy($id)
    {
        $donor = DonationList::findOrFail($id);
        $donor->delete_status = 1;
        $donor->save();
        return response()->json([
            "message" => "Donation deleted successfully",
        ]);
    }
}
