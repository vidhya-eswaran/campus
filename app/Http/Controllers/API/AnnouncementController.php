<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class AnnouncementController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->base_url = config("app.url") . "/"; // Or set it to a custom string
    }
    // Base URL without "public/"

    // Fetch all announcements
    public function index()
    {
        $perPage = $request->get('per_page', 10);

        $announcements = Announcement::with("categoryDetails")
            ->orderBy("created_at", "desc")
            ->paginate($perPage);
        return response()->json($announcements);
    }

    // Store a new announcement with file upload
    public function store(Request $request)
    {
        $validated = $request->validate([
            "target_type" => "required|in:target,role,class",
            "target" => "nullable|integer",
            "category" => "required",
            "announcementDescription" => "required|string",
            "announcementType" => "required|in:0,1",
            "announcementDate" => "required|date",
            "file" => "nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048",
            'createdBy' => 'required',
        ]);

        $user = Auth::user();

        $schoolSlug = request()->route('school');

       // dd($user);

        // Handle file upload
        if ($request->hasFile("file")) {
            $file = $request->file("file");
            $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

               $path = 'documents/' . $schoolSlug . '/Announcement/' . $filename;        

                // Upload to S3
                Storage::disk('s3')->put($path, file_get_contents($file));

                // Get public URL
                $validated['file'] = Storage::disk('s3')->url($path);
        }

        $announcement = Announcement::create($validated);

        // Convert file path to full URL
        if ($announcement->file) {
            $announcement->file = $announcement->file;
        }

        return response()->json(
            [
                "message" => "Announcement created successfully",
                "announcement" => $announcement,
            ],
            201
        );
    }

    // Fetch a single announcement
    public function viewbyid(Request $request,$id)
    {
        $id = $request->id;
        $announcement = Announcement::with("categoryDetails")->findOrFail($id);

        // Convert file path to full URL
        if ($announcement->file) {
            $announcement->file =
                $this->base_url . "announcements/" . $announcement->file;
        }

        return response()->json([
            "message" => "Announcement retrieved successfully",
            "announcement" => $announcement,
        ]);
    }

    // Update an announcement with file upload
    public function update(Request $request, $id)
    {
        $id = $request->id;
        $announcement = Announcement::findOrFail($id);
        $validated = $request->validate([
            "target_type" => "required|in:target,role,class",
            "target" => "nullable|integer",
            "category" => "required",
            "announcementDescription" => "required|string",
            "announcementType" => "required|in:0,1",
            "announcementDate" => "required|date",
            "file" => "nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048",
            'createdBy' => 'required',
        ]);

        $schoolSlug = request()->route('school');

        // Handle file upload
        if ($request->hasFile("file")) {            

            $file = $request->file("file");
            $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

               $path = 'documents/' . $schoolSlug . '/Announcement/' . $filename;         

                // Upload to S3
                Storage::disk('s3')->put($path, file_get_contents($file));

                // Get public URL
                $validated['file'] = Storage::disk('s3')->url($path);
        }

        $announcement->update($validated);

        // Convert file path to full URL
        if ($announcement->file) {
            $announcement->file =
                $this->base_url . "announcements/" . $announcement->file;
        }

        return response()->json([
            "message" => "Announcement updated successfully",
            "announcement" => $announcement,
        ]);
    }

    // Delete an announcement
    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $announcement = Announcement::findOrFail($id);

        // // Delete file if exists
        // if ($announcement->file) {
        //     $folderName = env("APP_FOLDER", "SVSTEST"); // Get folder name from .env, default to 'SVSTEST'
        //     $filePath =
        //         $_SERVER["DOCUMENT_ROOT"] .
        //         "/" .
        //         $folderName .
        //         "/announcements/" .
        //         $announcement->file;
        //     if (file_exists($filePath)) {
        //         unlink($filePath);
        //     }
        // }

        $announcement->delete();

        return response()->json([
            "message" => "Announcement deleted successfully",
        ]);
    }
}
