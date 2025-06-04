<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SponserMaster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PhotoController extends Controller
{
    public function upload(Request $request)
{
    if ($request->hasFile('photos')) {
        $photos = $request->file('photos');

        foreach ($photos as $index => $photo) {
            $name = $photo->getClientOriginalName();

            // Check if the photo with the same name already exists
            $existingPath = public_path('photos/' . $name);
            if (file_exists($existingPath)) {
                // Delete the existing photo
                unlink($existingPath);
            }

            // Save the new photo to storage
            $path = $photo->storeAs('photos', $name, 'public');

            // Perform additional operations or validations on the saved photo
            // Example: Store the photo path in the database

        }

        return response()->json(['message' => 'Photos uploaded successfully']);
    }

    return response()->json(['message' => 'No photos found in the request'], 400);
}

// public function upload(Request $request)
//     {
//         if ($request->hasFile('photos')) {
//             $photos = $request->file('photos');

//             foreach ($photos as $index => $photo) {
//   $name = $photo->getClientOriginalName();
//             $path = $photo->storeAs('photos', $name, 'public');

//                 // Validate and process each photo as per your requirements
//                 // Save the photo to storage or perform additional operations
//                 // Example: $photo->store('photos');
//             }

//             return response()->json(['message' => 'Photos uploaded successfully']);
//         }

//         return response()->json(['message' => 'No photos found in the request'], 400);
//     }      
}
