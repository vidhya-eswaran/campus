<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TemplateMaster;

class TemplateMasterController extends Controller
{
    public function index()
    {
        return response()->json(TemplateMaster::all());
    }

    // Fetch a single template by ID
    public function show(Request $request, $id)
    {
        $id = $request->id;
        $template = TemplateMaster::find($id);
        if ($template) {
            return response()->json($template);
        }
        return response()->json(["message" => "Template not found"], 404);
    }

    // Create a new template
    public function store(Request $request)
    {
        $data = $request->validate([
            "template_name" => "required|max:255",
            "extra" => "nullable",
            "comment" => "nullable|max:255",
            "template" => "required",
        ]);

        $template = TemplateMaster::create($data);
        return response()->json($template, 201);
    }

    // Update an existing template
    public function update(Request $request, $id)
    {
        $id = $request->id;
        $template = TemplateMaster::find($id);
        if ($template) {
            $data = $request->validate([
                "template_name" => "sometimes|required|string|max:255",
                "extra" => "nullable",
                "comment" => "nullable|string|max:255",
                "template" => "nullable",
            ]);

            $template->update($data);
            return response()->json($template);
        }
        return response()->json(["message" => "Template not found"], 404);
    }

    // Delete a template
    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $template = TemplateMaster::find($id);
        if ($template) {
            $template->delete();
            return response()->json([
                "message" => "Template deleted successfully",
            ]);
        }
        return response()->json(["message" => "Template not found"], 404);
    }
}
