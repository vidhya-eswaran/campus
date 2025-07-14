<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

use App\Models\PaymentNotificationData;
use Illuminate\Support\Facades\Validator;
use App\Models\GenerateInvoiceView;
use App\Models\UserNotification;
use App\Models\Student;
use App\Models\NotificationCategory;
use App\Models\Targetannouncement;
use App\Models\Announcement;
use Carbon\Carbon;

class NotificationController extends Controller
{
    private $base_url = "https://www.santhoshavidhyalaya.com/SVSTEST/"; // Base URL without "public/"
    public function getNotifications(Request $request)
{
$userId = $request->input('user_id');
$user = User::find($userId);

if (!$user) {
    return response()->json(['message' => 'No user notifications found'], 200);
}


    $query = PaymentNotificationData::query();

    if ($user->user_type === 'admin') {
        $query->where('show_admin', true);
    } else {
        $query->where('student_id', $user->id);
    }

    // Get notifications from the last one month
    $oneMonthAgo = Carbon::now()->subMonth();
    $notifications = $query->where('created_at', '>=', $oneMonthAgo)->get();

    // Group by category
    $grouped = $notifications->groupBy('notification_category');

    $response = [];

    foreach ($grouped as $category => $notifs) {
        $response[] = [
            'notification_category' => $category,
            'count' => $notifs->count(),
            'notifications' => $notifs->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'student_id' => $notif->student_id,
                    'email' => $notif->email,
                    'txnId' => $notif->txnId,
                    'paidAmount' => $notif->paidAmount,
                    'invoice_nos' => $notif->invoice_nos,
                    'status' => $notif->status,
                    'show_hide' => $notif->show_hide,
                    'created_at' => $notif->created_at,
                    'formatted_time' => Carbon::parse($notif->created_at)->diffForHumans(),
                    'updated_at' => $notif->updated_at,
                    'notification_type' => $notif->notification_type,
                    'notification_category' => $notif->notification_category,
                    'urllink' => $notif->urllink,
                    'show_admin' => $notif->show_admin,
                    'content' => $notif->content,
                ];
            }),
        ];
    }

    return response()->json([
        'user_type' => $user->user_type,
        'grouped_notifications' => $response,
    ]);
}
 public function allnotification(Request $request)
    {
        $data = $request->all();
        $usertype = User::find($data['id'])->user_type;
        if (isset($usertype) && $usertype == 'student') {
            $usernoti = PaymentNotificationData::where('status', 'success')->first();
            $invoice_id = GenerateInvoiceView::where('invoice_no', $usernoti['invoice_nos'])->slno;
            $invoiceLists = DB::table('invoice_lists')
                ->where('invoice_lists.invoice_id', '=', $invoice_id)
                ->pluck('payment_transaction_id');
            $paymentTransactionId = $invoiceLists->first();
            $url = '/svsportaladmin/PaymentReceipt/' . $paymentTransactionId;

            // /PaymentReceipt/20230620122516990



            $reponse['url'] = $url;
            return response()->json($reponse, 200);
        } elseif (isset($usertype) && $usertype == 'admin') {
            $usernoti = DB::table('payment_notification_datas')
                ->where('status', 'success')
                ->orderByDesc('created_at')
                ->get();

            $response = [];
            $data = [];

            foreach ($usernoti as $usernot) {
                $no = $usernot->invoice_nos;
                $invoiceView = GenerateInvoiceView::where('invoice_no', $no)->first();
                if ($invoiceView) {
                    $invoice_id = $invoiceView->slno;

                    $invoiceLists = DB::table('invoice_lists')
                        ->where('invoice_id', '=', $invoice_id)
                        ->select('payment_transaction_id', 'updated_at')
                        ->orderByDesc('updated_at')
                        ->get();
                    $invoice = $invoiceLists->first();

                    // $paymentTransactionId = $invoiceLists->first();
                    $paymentTransactionId = $invoice ? $invoice->payment_transaction_id : null;

                    $name = User::where('id', $usernot->student_id)->first()->name;

                    $url = '/svsportaladmin/PaymentReceipt/' . $paymentTransactionId;

                    if ($invoice && $invoice->updated_at) {
                        $receivedTime = $invoice->updated_at;
                        $formattedTime = Carbon::parse($receivedTime)->diffForHumans();
                    } else {
                        $formattedTime = 'N/A';
                    }
                    $data[] = [
                        'id' => $usernot->id,
                        'url'  => $url,
                        'text' => 'Payment success by ' . $name,
                        'receivedTime' => $formattedTime,
                        'date' => $receivedTime


                    ];
                } else {
                    // Handle the case where GenerateInvoiceView is not found for the given invoice number
                    // You can log an error or perform any necessary actions
                }
            }

            return response()->json(['data' => $data]);
        }
    }

    public function notification(Request $request)
    {
        $data = $request->all();
        $usertype = User::find($data['id'])->user_type;
        if (isset($usertype) && $usertype == 'student') {
            $usernoti = PaymentNotificationData::where('show_hide', 1)->where('status', 'success')->first();
            $invoice_id = GenerateInvoiceView::where('invoice_no', $usernoti['invoice_nos'])->slno;
            $invoiceLists = DB::table('invoice_lists')
                ->where('invoice_lists.invoice_id', '=', $invoice_id)
                ->pluck('payment_transaction_id');
            $paymentTransactionId = $invoiceLists->first();
            $url = '/svsportaladmin/PaymentReceipt/' . $paymentTransactionId;

            // /PaymentReceipt/20230620122516990



            $reponse['url'] = $url;
            return response()->json($reponse, 200);
        } elseif (isset($usertype) && $usertype == 'admin') {
            $usernoti = DB::table('payment_notification_datas')
                ->where('show_hide', 1)
                ->where('status', 'success')
                ->orderByDesc('created_at')
                ->get();

            $response = [];
            $data = [];

            foreach ($usernoti as $usernot) {
                $no = $usernot->invoice_nos;
                $invoiceView = GenerateInvoiceView::where('invoice_no', $no)->first();
                if ($invoiceView) {
                    $invoice_id = $invoiceView->slno;

                    $invoiceLists = DB::table('invoice_lists')
                        ->where('invoice_id', '=', $invoice_id)
                        ->select('payment_transaction_id', 'updated_at')
                        ->orderByDesc('updated_at')
                        ->get();
                    $invoice = $invoiceLists->first();

                    // $paymentTransactionId = $invoiceLists->first();
                    $paymentTransactionId = $invoice ? $invoice->payment_transaction_id : null;

                    $name = User::where('id', $usernot->student_id)->first()->name;

                    $url = '/svsportaladmin/PaymentReceipt/' . $paymentTransactionId;

                    if ($invoice && $invoice->updated_at) {
                        $receivedTime = $invoice->updated_at;
                        $formattedTime = Carbon::parse($receivedTime)->diffForHumans();
                    } else {
                        $formattedTime = 'N/A';
                    }
                    $data[] = [
                        'id' => $usernot->id,
                        'url'  => $url,
                        'text' => 'Payment success by ' . $name,
                        'receivedTime' => $formattedTime,

                    ];
                } else {
                    // Handle the case where GenerateInvoiceView is not found for the given invoice number
                    // You can log an error or perform any necessary actions
                }
            }

            return response()->json(['data' => $data]);
        }
    }
    public function hidenoti(Request $request)
    {

        $data = $request->all();

        $id = $data['id'];

        DB::table('payment_notification_datas')
            ->where('id', $id)
            ->update(['show_hide' => 0]);

        $response = [
            'message' => 'show_hide column updated successfully',
        ];

        return response()->json($response, 200);
    }
    public function graph(Request $request)
    {
       $invoiceLists = DB::table('invoice_lists')
    ->select(DB::raw('MONTH(created_at) AS month'), DB::raw('SUM(transaction_amount) AS total_amount'))
    ->where('status', 'success') // Filter by status = 'success'
    ->where('created_at', '>=', now()->subMonths(30)) // Created within the last 30 months
    ->groupBy(DB::raw('MONTH(created_at)')) // Group by month of creation
    ->get();
        $months = [];
        $amounts = [];

        foreach ($invoiceLists as $invoice) {
            $month = date("M", mktime(0, 0, 0, $invoice->month, 1));
            $months[] = $month;
            $amounts[] = $invoice->total_amount;
        }

        $reponse['labels'] =  $months;
        $reponse['amounts'] =  $amounts;

        return response()->json($reponse, 200);
    }
    public function userNotificationSend(Request $request)
    {
        $request->validate([
            'target_type' => 'required|string',
            'category' => 'required|integer',
            'announcementDescription' => 'required|string',
            'announcementType' => 'required|integer|in:0,1',
            'announcementDate' => 'nullable|date',
            'file' => 'nullable|string', // Base64 file should be sent as string
        ]   );
        // Extract validated values explicitly
        $requestData = [
            'target_type' => $request->target_type,
            'category' => $request->category,
            'announcementDescription' => $request->announcementDescription,
            'announcementType' => $request->announcementType,
            'announcementDate' => $request->announcementDate,
        ];
        
      
        // Handle Base64 file upload
        if ($request->has('file')) {
            $base64File = $request->file;
            $originalFileName = $request->input('file_name'); // <- Get original file name
            // Validate Base64 format
            if (preg_match('/^data:(\w+\/[\w\-.+]+);base64,/', $base64File, $matches)) {
                $fileType = $matches[1]; // Get MIME type (e.g., image/jpeg, application/pdf)

                // Allowed MIME types and corresponding extensions
                $mimeToExtension = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'application/vnd.ms-excel' => 'xls',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                    'application/octet-stream' => 'xlsx', // Sometimes Excel sends xlsx as octet-stream
                    'application/zip' => 'xlsx', // XLSX files may be treated as zip files
                    'text/csv' => 'csv',
                ];

                // Check if the MIME type is allowed and get the correct extension
                if (array_key_exists($fileType, $mimeToExtension)) {
                    $extension = $mimeToExtension[$fileType];
                } else {
                    return response()->json(['error' => 'Invalid file type'], 400);
                }

                // Decode Base64 data
                $decodedData = base64_decode(preg_replace('/^data:\w+\/[\w\-.+]+;base64,/', '', $base64File));

                // Check if decoding was successful
                if (!$decodedData) {
                    return response()->json(['error' => 'Invalid base64 data'], 400);
                }

                // Generate a unique file name with a timestamp
                $fileName = time() . '_announcement.' . $extension;

                // Define the destination path in public_html
                $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/SVSTEST/announcements/';

                // Save the decoded data to the target path
                file_put_contents($destinationPath . $originalFileName, $decodedData);

                // Generate the public URL for the file
                $fullFileName = $this->base_url . 'announcements/' . $originalFileName;

                // Add the full file URL to the request data
                $requestData['file'] = $fullFileName;

                // Optional: Return file URL if needed
                // return response()->json([
                //     'success' => true,
                //     'file_url' => $fullFileName
                // ]);
            } else {
                return response()->json(['error' => 'Invalid file format'], 400);
            }
        }

        // Create announcement
        $announcement = Announcement::create($requestData);
        $notifications = [];
        if($request->target_type == "target"){
            $targetData = Targetannouncement::findOrFail($request->target);
            $announcementrolesusers = json_decode($targetData->user_details, true);
        } elseif($request->target_type == "role"){
            // $targetData = Targetannouncement::findOrFail($request->role);
            // $announcementroles = json_decode($targetData->target_group, true);
            $announcementrolesusers = User::whereIn('user_type', $request->role)->get();
        } else {
            // $staticStandardValues = ['1', '2']; // Example static values
            // $announcementrolesusers = User::whereIn('standard', $staticStandardValues)->get();
            // Fetch users based on class details
            // Fetch users based on class details
            $announcementrolesusers = collect(); // Initialize empty collection
        
            foreach ($request->standard_details as $standard) {
                $std = $standard['std'];
                $sections = $standard['sec'];
                $groups = $standard['group'];
        
                // Fetch users where standard, section, or group match
                $users = User::where('standard', $std)
                    ->orWhereIn('sec', $sections)
                    ->orWhereIn('twe_group', $groups)
                    ->get();
        
                // Merge users into the main collection
                $announcementrolesusers = $announcementrolesusers->merge($users);
        }
        }
        // dd($announcementrolesusers);
        foreach ($announcementrolesusers as $usersdata) {
            $notificationData = [
                'user_id' => $usersdata['id'],
                'notification_type' => $request->announcementType == 0 ? 'immediate' : 'scheduled',
                'notification_category' => $request->category,
                'notification_text' => $request->announcementDescription,
                'schedule_time' => $request->announcementType == 1 ? Carbon::parse($request->announcementDate)->format('Y-m-d H:i:s') : null,
                'send_status' => $request->announcementType == 0 ? 'sent' : 'pending',
                'status' => 'unread',
                'created_at' => now(),
                'updated_at' => now(),
            ];
    
            // Store notification in the database
            $notifications[] = $notificationData;
           
        }
        // Bulk Insert Notifications
        UserNotification::insert($notifications);
        return response()->json(['message' => 'Notifications created successfully', 'notifications' => $notifications], 201);
    }
    
    public function getannouncementUnreadNotifications($user_id)
    {
        // Fetch unread and sent notifications with category details
        $notifications = UserNotification::where('user_id', $user_id)
            ->where('status', 'unread')
            ->where('send_status', 'sent')
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Check if notifications exist
        if ($notifications->isEmpty()) {
            return response()->json(['message' => 'No unread notifications found'], 200);
        }
    
        // Initialize an empty array
        $formattedNotifications = [];
    
        // Loop through each notification and get category name
        foreach ($notifications as $notification) {
            $formattedNotifications[] = [
                'id' => $notification->id,
                'user_id' => $notification->user_id,
                'status' => $notification->status,
                'send_status' => $notification->send_status,
                'message' => $notification->notification_text,
                'created_at' => $notification->created_at,
                'notification_category' => $notification->notification_category ? NotificationCategory::find($notification->notification_category)->notification_category : 'Unknown'
            ];
        }
    
        return response()->json([
            'message' => 'Unread notifications retrieved successfully',
            'notifications' => $formattedNotifications
        ], 200);
    }
     public function getannouncementallNotifications(Request $request)
    {
        // Fetch unread and sent notifications with category details
        $notifications = UserNotification::orderBy('created_at', 'desc')->get();
    
        // Check if notifications exist
        if ($notifications->isEmpty()) {
            return response()->json(['message' => 'No unread notifications found'], 200);
        }
    
        // Initialize an empty array
        $formattedNotifications = [];
    
        // Loop through each notification and get category name
        foreach ($notifications as $notification) {
            $formattedNotifications[] = [
                'id' => $notification->id,
                'user_id' => $notification->user_id,
                'user_name' => $notification->user_id ? User::find($notification->user_id)->name : 'Unknown',
                'role' => $notification->user_id ? User::find($notification->user_id)->user_type : 'Unknown',
                'status' => $notification->status,
                'send_status' => $notification->send_status,
                'message' => $notification->notification_text,
                'created_at' => $notification->created_at,
                'notification_category' => $notification->notification_category ? NotificationCategory::find($notification->notification_category)->notification_category : 'Unknown'
            ];
        }
    
        return response()->json([
            'message' => 'All announcement notifications retrieved successfully',
            'notifications' => $formattedNotifications
        ], 200);
    }
     public function announcementmarkAsRead($id)
    {
        // Find notification by ID
        $notification = UserNotification::find($id);

        // Check if notification exists
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        // Update status to read (assuming 'status' 0 = unread, 1 = read)
        $notification->update(['status' => "read"]);

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification
        ], 200);
    }
}
