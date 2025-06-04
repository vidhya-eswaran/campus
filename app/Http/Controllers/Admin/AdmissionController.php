<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use App\Models\Admission;
use Illuminate\Database\QueryException;
use Session;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
                    return view("pages.admission");   

    }
public function store(Request $request)
{
    try {
        dd($request->all());
        // $request->validate([
        //     'file' => 'required|file|mimes:pdf,doc,docx', // Adjust file validation rules as needed
        // ]);

        // Handle File Upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('public/pics');
            $path = str_replace('public/', '', $path); // Remove 'public/' from the path for database storage
        } else {
            $path = null;
        }

        $insertData = [
            "student_name" => $request->input('name'), // Make sure 'name' is available in your form
            "aadhar_card" => $path,
            // Add other fields as needed
        ];
// dd($insertData);

        Admission::create($insertData);

        return view("pages.create")->with('success', 'Record created successfully!');
    } catch (\Exception $ex) {
        return view("pages.create")->with('error', 'Error creating record.');
    }
}

}