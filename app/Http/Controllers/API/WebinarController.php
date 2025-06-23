<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Webinar;

class WebinarController extends Controller
{
    public function index()
    {
        return response()->json(Webinar::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|string',
            'class' => 'required|string',
            'section' => 'required|string',
            'teacher_name' => 'required|string',
            'host_name' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
        ]);

        $webinar = Webinar::create($validated);
        return response()->json(['message' => 'Webinar created', 'data' => $webinar], 201);
    }

    public function show(Request $request,$id)
    {
        $id = $request->id;
        $webinar = Webinar::find($id);
        if (!$webinar) return response()->json(['message' => 'Not Found'], 404);
        return response()->json($webinar);
    }

    public function update(Request $request, $id)
    {
        $id = $request->id;
        $webinar = Webinar::find($id);
        if (!$webinar) return response()->json(['message' => 'Not Found'], 404);

        $validated = $request->validate([
            'staff_id' => 'required|string',
            'class' => 'required|string',
            'section' => 'required|string',
            'teacher_name' => 'required|string',
            'host_name' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
        ]);

        $webinar->update($validated);
        return response()->json(['message' => 'Webinar updated', 'data' => $webinar]);
    }

    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $webinar = Webinar::find($id);
        if (!$webinar) return response()->json(['message' => 'Not Found'], 404);
        $webinar->delete();
        return response()->json(['message' => 'Webinar deleted']);
    }
}
