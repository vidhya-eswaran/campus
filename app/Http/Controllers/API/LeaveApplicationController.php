<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use App\Helpers\LifecycleLogger;
use App\Models\User;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveApplication::where('delete_status', 0);

        if ($request->has('studentId')) {
            $query->where('studentId', $request->studentId);
        }

        if ($request->has('fromDate') && $request->has('toDate')) {
            $from = Carbon::parse($request->fromDate)->startOfDay();
            $to = Carbon::parse($request->toDate)->endOfDay();

            $query->where(function ($q) use ($from, $to) {
                $q->where('fromDate', '<=', $to)
                ->where('toDate', '>=', $from);
            });
        }


        $leaveApplications = $query->orderBy('id', 'desc')->get();

        return response()->json($leaveApplications);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'studentName' => 'required|string',
            'studentId' => 'required|integer',
            'rollNo' => 'nullable|string',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
            'fatherName' => 'nullable|string',
            'motherName' => 'nullable|string',
            'fromDate' => 'required|date',
            'toDate' => 'nullable|date',
            'leaveDays' => 'nullable|integer',
            'reason' => 'nullable|string',
        ]);

        $leaveApplication = LeaveApplication::create($validated);
        $userId = User::where('slno', $validated['studentId'])->value('id');

        // Log the leave application submission
        LifecycleLogger::log(
            'Leave Application Submitted',
            $userId,
            'leave_application_submitted',
            [
                'student_name' => $validated['studentName'],
                'from_date' => $validated['fromDate'],
                'to_date' => $validated['toDate']
            ]
        );

        return response()->json(['message' => 'Leave application submitted successfully', 'data' => $leaveApplication], 201);
    }

    public function update(Request $request, $id)
    {
        $id = $request->id;
        $id = (int) $id;

        $validated = $request->validate([
            'studentName' => 'required|string',
            'studentId' => 'required|integer',
            // 'rollNo' => 'required|string',
            // 'class' => 'required|string',
            // 'section' => 'required|string',
            // 'group' => 'nullable|string',
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'reason' => 'nullable|string',
            // 'leaveDays' => 'required|integer',
            // 'contactNumber' => 'required|string',
            // 'fatherName' => 'required|string',
            // 'motherName' => 'required|string',
        ]);

        $leaveApplication = LeaveApplication::findOrFail($id);
        $leaveApplication->update($validated);

        return response()->json(['message' => 'Leave application updated successfully', 'data' => $leaveApplication]);
    }

    public function viewbyid(Request $request, $id)
    {
        $id = $request->id;
        $id = (int) $id;
        $leaveApplication = LeaveApplication::findOrFail($id);

        return response()->json(['message' => 'Leave application retrieved successfully', 'data' => $leaveApplication]);
    }

    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        $id = (int) $id;
        $leaveApplication = LeaveApplication::findOrFail($id);

        // Update delete_status instead of deleting
        $leaveApplication->update(['delete_status' => 1]);

        return response()->json(['message' => 'Leave application deleted successfully']);
    }
}
