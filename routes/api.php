<?php
//Central DB
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\CentralAuthController;
use App\Http\Controllers\school\SchoolUserController;

//==========================
//        School APIS
//===========================
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\listUserController;
use App\Http\Controllers\API\sponserMapController;
use App\Http\Controllers\API\dashboardController;
//master
use App\Http\Controllers\API\DonorController;
use App\Http\Controllers\API\sectionmasterController;
use App\Http\Controllers\API\classmasterController;
use App\Http\Controllers\API\DiscountCategoryMasterController;
use App\Http\Controllers\API\paymentmasterController;
use App\Http\Controllers\API\schoolfeesmasterController;
use App\Http\Controllers\API\schoolmiscellaneousbillmasterController;
use App\Http\Controllers\API\otherexpendituremasterController;
use App\Http\Controllers\API\hostelfeesmasterController;
use App\Http\Controllers\API\TwelvethGroupController;
use App\Http\Controllers\API\SponserMasterController;
use App\Http\Controllers\API\genrateInvoiceController;
use App\Http\Controllers\API\listStudentUser;
use App\Http\Controllers\API\reminderController;
use App\Http\Controllers\API\feesmapController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\SmsController;
use App\Http\Controllers\API\ReportsController;
use App\Http\Controllers\API\FeeMapArrayController;
use App\Http\Controllers\API\bulkstatusController;
use App\Http\Controllers\API\StudentHealthcareController;
use App\Http\Controllers\API\StaffController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\TileController;
use App\Http\Controllers\API\ClassSubjectController;
use App\Http\Controllers\API\ClassTeacherController;
use App\Http\Controllers\API\StudentMarkController;
use App\Http\Controllers\API\TermController;
use App\Http\Controllers\API\TemplateMasterController;
use App\Http\Controllers\API\ClassSubjectMappingController;
use App\Http\Controllers\API\subjectMasterController;
use App\Http\Controllers\API\TeachertypeMasterController;
use App\Http\Controllers\API\TargetannouncementMasterController;
use App\Http\Controllers\API\StandardMasterController;
use App\Http\Controllers\API\StandardSectionMappingController;
use App\Http\Controllers\API\GroupMasterController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\DropdowntypeMasterController;
use App\Http\Controllers\API\StudentPromotionController;
use App\Http\Controllers\API\NotificationCategoryController;
use App\Http\Controllers\API\EventCategoryMasterController;
use App\Http\Controllers\API\NoticeBoardController;
use App\Http\Controllers\API\EventCalendarController;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\LeaveApplicationController;
use App\Http\Controllers\API\WebinarController;
use App\Http\Controllers\API\TemplateEditorController;
use App\Http\Controllers\API\excelsample;
use App\Http\Controllers\API\StaffFeesMasterController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\StaffInvoiceController;
use App\Http\Controllers\API\MessageCategoryMasterController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\HostelAdmissionController;
use App\Http\Controllers\API\StudentAttendanceController;
use App\Http\Controllers\API\PhotoController;

// use App\Http\Controllers\API\school_fees_master; storeSendForm
// use App\Http\Controllers\API\school_miscellaneous_bill_master;
// use App\Http\Controllers\API\hostel_fees_master;
// use App\Http\Controllers\API\other_expenditure_master;
use App\Helpers\Autogeneratenumber;
use Carbon\Carbon;
use App\Helpers\HelperEmail;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//========================================================================================
//Central DB API
Route::post("/create-school", [SchoolController::class , "createSchool"]);

Route::post("/central/login", [CentralAuthController::class , "login"]);

Route::get('/school/{id}', [SchoolController::class, 'viewSchool']);

Route::put('/school/{id}', [SchoolController::class, 'updateSchool']);


Route::get("/test", function ()
{
    return response()->json(["message" => "API is working"]);
});

//=========================================================================================
// School Database
Route::post("/login", [ApiController::class , "login"]);


Route::group(["prefix" => "{school}", "middleware" => ["school.db"]], function ()
{
    Route::post("/users", [SchoolUserController::class , "store"]);

    Route::post("/register", [ApiController::class , "register"]);


    Route::post("/healthcare/add", [StudentHealthcareController::class , "addHealthcareRecord", ]);
    Route::post("/healthcare/edit/{id}", [StudentHealthcareController::class , "editHealthcareRecord", ]);
    Route::get("/healthcare/view/{id}", [StudentHealthcareController::class , "viewHealthcareRecord", ]);
    Route::get("/healthcare/viewAll", [StudentHealthcareController::class , "viewAllHealthcareRecords", ]);
    Route::delete("/healthcare/delete/{id}", [StudentHealthcareController::class , "destroy", ]);

    //StudentMark
    Route::post("/StudentMark-Upload", [StudentMarkController::class , "store", ]);
    Route::post("/StudentMark-view", [StudentMarkController::class , "viewAll", ]);
    Route::post("/StudentMark-update", [StudentMarkController::class , "update", ]);
    Route::post("/marks/save-temporary", [StudentMarkController::class , "saveTemporary", ]);
    Route::get("/marks/temporary", [StudentMarkController::class , "getTemporary", ]);
    Route::get("/reportCard", [StudentMarkController::class , "viewReportCard", ]);

    Route::get("/viewProfile", [App\Http\Controllers\API\ApiController::class , "viewProfile", ]);

    Route::post('/login', [ApiController::class, 'login']);

    Route::post('/users', [SchoolUserController::class, 'store']);

    Route::group(['middleware' => ['auth:api']], function () {

        Route::post('/healthcare/add', [StudentHealthcareController::class, 'addHealthcareRecord']);
        Route::post('/healthcare/edit/{id}', [StudentHealthcareController::class, 'editHealthcareRecord']);
        Route::get('/healthcare/view/{id}', [StudentHealthcareController::class, 'viewHealthcareRecord']);
        Route::get('/healthcare/viewAll', [StudentHealthcareController::class, 'viewAllHealthcareRecords']);

    });

    Route::get("/detail", function (Request $request)
    {
        return auth()->user();
    });
    Route::get("/count", [dashboardController::class , "count"]);

    //ApiController
    Route::get("/lifecycle", [ApiController::class , "lifecycle"]);


    //Promotion student Routes
    Route::post("/studentpromotion-update", [StudentPromotionController::class , "update", ]);
    Route::post("/move-to-detention", [StudentPromotionController::class , "moveToDetention", ]);

    //Reports
    Route::post("/getExcessDuesReport", [ReportsController::class , "getExcessDuesReport", ]);
    Route::post("/getPaymentReport", [ReportsController::class , "getPaymentReport", ]);
    Route::post("/StudentLedger", [ReportsController::class , "StudentLedger", ]);
    Route::post("/LedgerSummary", [ReportsController::class , "LedgerSummary", ]);

    //FeeMapArrayController
    Route::post("/feesmaparray-insert", [FeeMapArrayController::class , "insert"]);
    Route::post("/feesmaparray-read", [FeeMapArrayController::class , "read"]);


    Route::prefix("webinars")->group(function ()
    {
        Route::get("/", [WebinarController::class , "index"]);
        Route::post("/", [WebinarController::class , "store"]);
        Route::get("/{id}", [WebinarController::class , "show"]);
        Route::put("/{id}", [WebinarController::class , "update"]);
        Route::delete("/{id}", [WebinarController::class , "destroy"]);
    });

    Route::prefix("eventcalendars")->group(function ()
    {
        Route::get("/", [EventCalendarController::class , "index"]);
        Route::post("/", [EventCalendarController::class , "store"]);
        Route::put("/{id}", [EventCalendarController::class , "update"]);
        Route::get("/{id}", [EventCalendarController::class , "viewbyid"]);
        Route::delete("/{id}", [EventCalendarController::class , "destroy"]);
    });

    //leaveapplications
    Route::prefix("leaveapplications")->group(function ()
    {
        Route::get("/", [LeaveApplicationController::class , "index"]);
        Route::post("/", [LeaveApplicationController::class , "store"]);
        Route::put("/{id}", [LeaveApplicationController::class , "update"]);
        Route::get("/{id}", [LeaveApplicationController::class , "viewbyid", ]);
        Route::delete("/{id}", [LeaveApplicationController::class , "destroy", ]);
    });





    //fessmap
    Route::post("/feesmap-insert", [feesmapController::class , "insert"]);
    Route::post("/feesmap-insertArray", [feesmapController::class , "insertArray", ]);
    Route::post("/feesmap-insertbyIDArray", [feesmapController::class , "insertbyIDArray", ]);
    Route::post("/feesmap-update", [feesmapController::class , "update"]);
    //VIEW MAPPING WITH STUDENTS
    Route::post("/studentsMaps/{standard}", [feesmapController::class , "fetchByStandard", ]);
    Route::post("/fetchByhostel", [feesmapController::class , "fetchByhostel", ]);
    Route::post("/view/studentsMaps/{id}", [feesmapController::class , "fetchByid", ]);
    Route::post("/del/studentsMaps/{id}", [feesmapController::class , "delByid", ]);
    Route::get("/feesmap-read", [feesmapController::class , "read"]);
    Route::post("/feesmap-delete", [feesmapController::class , "delete"]);
    Route::post("/feesmap-deleteforStudent", [feesmapController::class , "deleteforStudent", ]);
    Route::post("/feesmap-insertByID", [feesmapController::class , "insertByID"]);





    //staff
    Route::post("/staff/add", [StaffController::class , "addStaff"]);
    Route::post("/staff/edit/{id}", [StaffController::class , "editStaff"]);
    Route::get("/staff/view/{id}", [StaffController::class , "viewStaff"]);
    Route::get("/staff/viewAll", [StaffController::class , "viewAllStaff"]);
    Route::post("/staff/delete/{id}", [StaffController::class , "deleteStaff"]);

    //StudentController
    Route::post("/add-student", [StudentController::class , "store"]);
    Route::post("/upload-student", [StudentController::class , "uploadStudentData"]);
    Route::get("/read-student", [StudentController::class , "read"]);
    Route::get("/get-admissionddstudents/{standard}", [StudentController::class , "getadmissionStandards", ]);
    Route::get("/read-student-history", [StudentController::class , "readHistory"]);
    Route::post("/view/studentinfo/{admission_no}", [StudentController::class , "readByadmissioNo", ]);
    Route::get("/studentadmitted/{id}", [StudentController::class , "show"]);
    Route::post("/studentadmitted-update/{id}", [StudentController::class , "update", ]);
    Route::get("/viewbyidadmission/{id}", [StudentController::class , "showfromAdmission", ]);
    Route::post("/Admission-update/{id}", [StudentController::class , "updatefromAdmission", ]);
    Route::post("/upload-photos", [PhotoController::class , "upload"]);
    Route::post("/bulk-status/profile", [bulkstatusController::class , "status"]);

    //TemplateEditorController
    Route::get("studentContactView", [TemplateEditorController::class , "studentContactView", ]);
    Route::get("studentAttendanceView", [TemplateEditorController::class , "studentAttendanceView", ]);
    Route::get("studenbonafideView", [TemplateEditorController::class , "bonafideView", ]);
    Route::get("courseCompletionView", [TemplateEditorController::class , "courseCompletion", ]);
    Route::get("noDueView", [TemplateEditorController::class , "noDue"]);
    Route::post("idcardView", [TemplateEditorController::class , "idcard"]);
    Route::get("/studentcertificatelist", [TemplateEditorController::class , "studentCertificatelist"]);
    
    Route::prefix("templateeditor")->group(function ()
    {
        Route::get("/", [TemplateEditorController::class , "index"]);
        Route::post("/", [TemplateEditorController::class , "store"]);
        Route::put("/{id}", [TemplateEditorController::class , "update"]);
        Route::get("/{id}", [TemplateEditorController::class , "viewbyid"]);
        Route::get("/{id}/preview-pdf", [TemplateEditorController::class , "previewPdf", ]);
        Route::delete("/{id}", [TemplateEditorController::class , "destroy"]);
    });


    Route::prefix("terms")->group(function ()
    {
        Route::get("/", [TermController::class , "index"]);
        Route::post("/", [TermController::class , "store"]);
        Route::put("/{id}", [TermController::class , "update"]);
        Route::get("/{id}", [TermController::class , "viewbyid"]);

        Route::delete("/{id}", [TermController::class , "destroy"]);
    });

    Route::prefix("standards")->group(function ()
    {
        Route::get("/", [StandardMasterController::class , "index"]);
        Route::post("/", [StandardMasterController::class , "store"]);
        Route::put("/{id}", [StandardMasterController::class , "update"]);
        Route::get("/{id}", [StandardMasterController::class , "viewbyid"]);
        Route::delete("/{id}", [StandardMasterController::class , "destroy", ]);
    });

    //section
    Route::post("/section-master-insert", [sectionmasterController::class , "insert", ]);
    Route::get("/section-master-read", [sectionmasterController::class , "read", ]);
    Route::get("/section-master-view/{id}", [sectionmasterController::class , "viewbyid", ]);
    Route::post("/section-master-update", [sectionmasterController::class , "update", ]);
    Route::post("/section-master-delete", [sectionmasterController::class , "delete", ]);

    Route::get("/templates", [TemplateMasterController::class , "index"]);
    Route::get("/templates/{id}", [TemplateMasterController::class , "show", ]);
    Route::post("/templates", [TemplateMasterController::class , "store"]);
    Route::put("/templates/{id}", [TemplateMasterController::class , "update", ]);
    Route::delete("/templates/{id}", [TemplateMasterController::class , "destroy", ]);

    Route::prefix("eventcategorymasters")->group(function ()
    {
        Route::get("/", [EventCategoryMasterController::class , "index"]);
        Route::post("/", [EventCategoryMasterController::class , "store"]);
        Route::put("/{id}", [EventCategoryMasterController::class , "update", ]);
        Route::get("/{id}", [EventCategoryMasterController::class , "viewbyid", ]);
        Route::delete("/{id}", [EventCategoryMasterController::class , "destroy", ]);
    });

    //class
    Route::post("/class-master-insert", [classmasterController::class , "insert"]);
    Route::get("/class-master-read", [classmasterController::class , "read"]);
    Route::post("/class-master-update", [classmasterController::class , "update"]);
    Route::post("/class-master-delete", [classmasterController::class , "delete"]);
    //paymentZ
    Route::post("/pay-master-insert", [paymentmasterController::class , "insert"]);
    Route::get("/pay-master-read", [paymentmasterController::class , "read"]);
    Route::post("/pay-master-update", [paymentmasterController::class , "update"]);
    Route::post("/pay-master-delete", [paymentmasterController::class , "delete"]);
    //fees subheading master
    //payment
    Route::post("/schoolfees-master-insert", [schoolfeesmasterController::class , "insert", ]);
    Route::get("/schoolfees-master-read", [schoolfeesmasterController::class , "read", ]);
    Route::post("/schoolfees-master-update", [schoolfeesmasterController::class , "update", ]);
    Route::post("/schoolfees-master-delete", [schoolfeesmasterController::class , "delete", ]);

    Route::post("/schoolmiscelfees-master-insert", [schoolmiscellaneousbillmasterController::class , "insert", ]);
    Route::get("/schoolmiscelfees-master-read", [schoolmiscellaneousbillmasterController::class , "read", ]);
    Route::post("/schoolmiscelfees-master-update", [schoolmiscellaneousbillmasterController::class , "update", ]);
    Route::post("/schoolmiscelfees-master-delete", [schoolmiscellaneousbillmasterController::class , "delete", ]);

    Route::post("/otherfees-master-insert", [otherexpendituremasterController::class , "insert", ]);
    Route::get("/otherfees-master-read", [otherexpendituremasterController::class , "read", ]);
    Route::post("/otherfees-master-update", [otherexpendituremasterController::class , "update", ]);
    Route::post("/otherfees-master-delete", [otherexpendituremasterController::class , "delete", ]);

    Route::post("/hostelfee-master-insert", [hostelfeesmasterController::class , "insert", ]);
    Route::get("/hostelfee-master-read", [hostelfeesmasterController::class , "read", ]);
    Route::post("/hostelfee-master-update", [hostelfeesmasterController::class , "update", ]);
    Route::post("/hostelfee-master-delete", [hostelfeesmasterController::class , "delete", ]);

    Route::post("/TwelvethGroup-master-insert", [TwelvethGroupController::class , "insert", ]);
    Route::get("/TwelvethGroup-master-read", [TwelvethGroupController::class , "read", ]);
    Route::post("/TwelvethGroup-master-update", [TwelvethGroupController::class , "update", ]);
    Route::post("/TwelvethGroup-master-delete", [TwelvethGroupController::class , "delete", ]);

    Route::post("/sponser-master-insert", [SponserMasterController::class , "insert", ]);
    Route::get("/sponser-master-read", [SponserMasterController::class , "read"]);
    Route::post("/sponser-master-update", [SponserMasterController::class , "update", ]);
    Route::post("/sponser-master-delete", [SponserMasterController::class , "delete", ]);
    Route::post("/ViewSponserID", [SponserMasterController::class , "ViewSponserID", ]);

    //class
    Route::post("/DiscountCategory-master-insert", [DiscountCategoryMasterController::class , "insert", ]);
    Route::get("/SchoolDiscountCategory-read", [DiscountCategoryMasterController::class , "schoolread", ]);
    Route::get("/HostelDiscountCategory-read", [DiscountCategoryMasterController::class , "hostelread", ]);
    Route::post("/DiscountCategory-update", [DiscountCategoryMasterController::class , "update", ]);
    Route::post("/DiscountCategory-delete", [DiscountCategoryMasterController::class , "delete", ]);
});
 //invoice API's
Route::get("/invoice-list", [InvoiceController::class , "getInvoiceList", ]);
Route::get("/invoice-list-short", [InvoiceController::class , "getInvoiceListshort", ]);
Route::get("/receipt-list-short", [InvoiceController::class , "getReceiptListshort", ]);
Route::get("/payment-receipt-list", [InvoiceController::class , "getPaymentReceiptList", ]);
Route::get("/payment-receipt", [InvoiceController::class , "getPaymentReceipt", ]);
Route::post("/geteachsponserstudent", [InvoiceController::class , "getsponserstudent", ]);
Route::get("/sponsor/{sponsorId}/students", [InvoiceController::class , "getSponsorIDStudents", ]);
Route::get("/sponsortwo/{sponsorId}/students", [InvoiceController::class , "getSponsorIDStudentstwo", ]);
Route::get("/sponsor/select", [InvoiceController::class , "getSponsorSelectOptions", ]);
Route::get("/student/select", [InvoiceController::class , "getParentSelectOptions", ]);
Route::post("/sponsor/processCashPayment", [InvoiceController::class , "processCashPayment", ]);
Route::post("/student/nodues", [InvoiceController::class , "getNoDuesCertificatesAndPendingDues", ]);
Route::get("/student/nodues/{id}/selectbyid", [InvoiceController::class , "getNoDuesCertificatesbyid", ]);
Route::get("/getUserDetailsWithExcessAmount", [InvoiceController::class , "getUserDetailsWithExcessAmount", ]);
Route::get("/viewExcessAmount/{id}", [InvoiceController::class , "viewExcessAmount", ]);
Route::get("/viewpreExcessAmount/{id}", [InvoiceController::class , "viewpreExcessAmount", ]);
Route::post("/updateExcessAmount", [InvoiceController::class , "updateExcessAmount", ]);
Route::post("/addExcessAmount", [InvoiceController::class , "addExcessAmountForSponsor", ]);
Route::get("/getsponinfo", [InvoiceController::class , "getsponinfo"]);

//genrate invoice
Route::post("/invoiceSearch", [genrateInvoiceController::class , "invoiceSearch", ]);
Route::post("/ReciptSearch", [genrateInvoiceController::class , "ReciptSearch"]);
Route::post("/deleterecipt", [genrateInvoiceController::class , "deleterecipt"]);
Route::post("/deletereciptview", [genrateInvoiceController::class , "deletereciptview", ]);
Route::post("/deleteinvoiceview", [genrateInvoiceController::class , "deleteinvoiceview", ]);
Route::post("/deleteinvoice", [genrateInvoiceController::class , "deleteinvoice", ]);
Route::post("/geneachStdInvoiceView", [genrateInvoiceController::class , "genrateForGrade", ]);
Route::post("/paycashgenrate", [genrateInvoiceController::class , "cashgenrate", ]);
Route::post("/paycashgenratetwo", [genrateInvoiceController::class , "cashgenratetwo", ]);
Route::post("/listgenrate", [genrateInvoiceController::class , "listgenrate", ]);
Route::post("/listgenratefilter", [genrateInvoiceController::class , "listgenratefilter", ]);
Route::post("/listgenrateById", [genrateInvoiceController::class , "listgenrateById", ]);
Route::post("/genschoolInvoiceView", [genrateInvoiceController::class , "getTotalAmount", ]);
Route::post("/docGenerate", [genrateInvoiceController::class , "docGenerate", ]);
Route::post("/discountTotalAmount", [genrateInvoiceController::class , "discountTotalAmount", ]);
Route::get("/readDiscount", [genrateInvoiceController::class , "readDiscount"]);
Route::post("/deleteDiscount", [genrateInvoiceController::class , "deleteDiscount", ]);
Route::get("/getDiscountCategories", [genrateInvoiceController::class , "getDiscountCategories", ]);
Route::group(["middleware" => ["auth:api"]], function ()
{
    Route::get("/dashboard", [App\Http\Controllers\API\ApiController::class , "countstudent", ]);

    Route::get("/tiles", [TileController::class , "getTiles"]);
    Route::post("/tiles", [TileController::class , "insertTile"]);
    Route::put("/tiles/{id}", [TileController::class , "updateTile"]);
});

Route::get("/excel", [excelsample::class , "downloadExcel"]);
Route::get("/email/check", function (Request $request)
{
    $modulesToCheck = ["Student Management - Application", "Fee Management - donation from Sponsor", "Hostel Admission", "Hostel Admission - application by parent", "Hostel Admission - update Status of hostel application", ];
    $results = [];

    foreach ($modulesToCheck as $module)
    {
        $template = HelperEmail::getEmailTemplate($module);
         $results[] = [
            "module" => $module,
            "found" => $template,
            "subject" => $template["emailTemplate"]["subject"] ?? null,
        ];
    }

    return response()->json(["status" => "success", "data" => $results, ]);
});
Route::get("/php", function ()
{
    return response()->json(["php_version" => phpversion() , ]);
});
Route::any("/source", function (Request $request)
{
    return response()->json(["full_url" => $request->fullUrl() , "ip_address" => $request->ip() , "user_agent" => $request->header("User-Agent") , "referer" => $request
        ->headers
        ->get("referer") , "host" => $request->getHost() , "source" => strpos($request->header("User-Agent") , "PostmanRuntime") !== false ? "Postman" : "Browser/Other", ]);
});

Route::get("/graph", [NotificationController::class , "graph"]);
Route::post("/notification", [NotificationController::class , "notification"]);
Route::get("/getNotifications", [NotificationController::class , "getNotifications", ]);
Route::post("/allnotification", [NotificationController::class , "allnotification", ]);
Route::post("/hide-notification", [NotificationController::class , "hidenoti"]);
Route::post("/announcementNotificationSend", [NotificationController::class , "userNotificationSend", ]);
Route::put("/announcementnotifications/{id}/read", [NotificationController::class , "announcementmarkAsRead", ]);
Route::get("/announcementnotifications/unread/{user_id}", [NotificationController::class , "getannouncementUnreadNotifications", ]);
Route::get("/announcementnotificationsall", [NotificationController::class , "getannouncementallNotifications", ]);


Route::prefix("messages")->group(function ()
{
    Route::get("/", [MessageController::class , "allMessages"]);
    Route::get("/{id}", [MessageController::class , "viewSingleMessage"]);
    Route::post("/store", [MessageController::class , "store"]);
    Route::post("/{id}/reply", [MessageController::class , "reply"]);
});
Route::get("download/{fileName}", [MessageController::class , "downloadFile", ])->name("download.file");

//subjects
Route::prefix("subjects")->group(function ()
{
    Route::get("/", [subjectMasterController::class , "index"]);
    Route::post("/", [subjectMasterController::class , "store"]);
    Route::put("/{id}", [subjectMasterController::class , "update"]);
    Route::get("/{id}", [subjectMasterController::class , "show"]);
    Route::delete("/{id}", [subjectMasterController::class , "destroy"]);
});


//ApiController
//Route::post("/register", [ApiController::class , "register"]);
Route::get("/getMatchingUsers", [ApiController::class , "getMatchingUsersdd"]);
Route::get("/dashboard", [ApiController::class , "countstudent"]);
Route::post("/logout", [ApiController::class , "logout"]);
Route::post("/userdelByid", [ApiController::class , "delByid"]);
Route::post("/sendotp", [ApiController::class , "sendOtp"]);
Route::post("/verifyotp", [ApiController::class , "verifyotp"]);
Route::get("/searchStudents", [ApiController::class , "searchStudents"]);
Route::post("/student-master-readstudents/{grade}/{section}", [ApiController::class , "readstudents", ]);
Route::post("/studentByGrades/{standard}", [ApiController::class , "StudentByStandard", ]);
Route::get("/studentByGradesSec/{standard}", [ApiController::class , "SearchStandardSec", ]);
Route::get("/ADstudentByGradesSec/{standard}", [ApiController::class , "ADSearchStandardSec", ]);
Route::post("/withoutSponsorGrades/{standard}", [ApiController::class , "withoutSponsorStandard", ]);
Route::post("/student-fees", [ApiController::class , "studentfees"]);
Route::post("/sponser-fees", [ApiController::class , "sponserfees"]);
Route::post("/admissionSearch", [ApiController::class , "admissionSearch"]);

//listUserController
Route::post("/resetsvsUser", [listUserController::class , "resetpassword"]);
Route::post("/changesUserpsd", [listUserController::class , "changepassword"]);
Route::get("/listSVSUser", [listUserController::class , "read"]);
Route::post("/EditSVSUser", [listUserController::class , "changeUserDetails"]);
Route::post("/addSVSUser", [listUserController::class , "addUser"]);
Route::post("/deleteSVSUser", [listUserController::class , "deleteUser"]);
Route::get("/listSVSsponser", [listUserController::class , "sponserUser"]);
Route::post("/IdUserDetails", [listUserController::class , "IdUserDetails"]);
Route::get("/listRoles", [listUserController::class , "listRoles"]);
Route::get("/roleBasedUsers", [listUserController::class , "roleBasedUsers"]);

//listStudentUser
Route::get("/listSVSStudent", [listStudentUser::class , "read"]);
Route::post("/viewStudentinfo", [listStudentUser::class , "viewstuInfo"]);
Route::post("/editSVSStudent", [listStudentUser::class , "changeUserDetails"]);


//User Announcement Notification send
Route::get("/generate-contact-id", function ()
{
    $currentDate = Carbon::now()->toDateString(); // Get the current date
    return Autogeneratenumber::generateContactId($currentDate);
});
//mail
Route::get("/testemail", function ()
{
    $email = "s.harikiran@eucto.com";
    Mail::to($email)->send(new TestEmail());

    return "Test email sent to $email";
});
Route::get("/sendsms", [SmsController::class , "sendTestSms"]);
Route::post("/Invoicesendsmsandmail", [InvoiceController::class , "sendsms"]);

//count
//sponser-add remove
Route::post("/mapSponserStudent", [sponserMapController::class , "mapstudents"]);
Route::post("/removemapstudents", [sponserMapController::class , "removemapstudents", ]);
Route::get("/readmapstudents", [sponserMapController::class , "readmapstudents", ]);
Route::get("/GetSponserall", [sponserMapController::class , "getSponser"]);

//reminder
Route::post("/reminder-insert", [reminderController::class , "insert"]);
Route::get("/reminder-read", [reminderController::class , "read"]);
Route::post("/reminder-update", [reminderController::class , "update"]);
Route::post("/reminder-delete", [reminderController::class , "delete"]);


Route::prefix("message-category")->name("message-category.")->group(function ()
{
    Route::get("/", [MessageCategoryMasterController::class , "index", ])
        ->name("index");
    Route::post("/", [MessageCategoryMasterController::class , "store", ])
        ->name("store");
    Route::put("/{id}", [MessageCategoryMasterController::class , "update", ])
        ->name("update");
    Route::get("/{id}", [MessageCategoryMasterController::class , "viewbyid", ])
        ->name("view");
    Route::delete("/{id}", [MessageCategoryMasterController::class , "destroy", ])
        ->name("delete");
});


Route::prefix("teachertypes")->group(function ()
{
    Route::get("/", [TeachertypeMasterController::class , "index"]);
    Route::post("/", [TeachertypeMasterController::class , "store"]);
    Route::put("/{id}", [TeachertypeMasterController::class , "update"]);
    Route::get("/{id}", [TeachertypeMasterController::class , "viewbyid"]);

    Route::delete("/{id}", [TeachertypeMasterController::class , "destroy"]);
});

Route::prefix("targetannouncement")->group(function ()
{
    Route::get("/", [TargetannouncementMasterController::class , "index"]);
    Route::post("/", [TargetannouncementMasterController::class , "store"]);
    Route::put("/{id}", [TargetannouncementMasterController::class , "update"]);
    Route::get("/{id}", [TargetannouncementMasterController::class , "viewbyid", ]);

    Route::delete("/{id}", [TargetannouncementMasterController::class , "destroy", ]);
});

Route::prefix("notificationcategory")->group(function ()
{
    Route::get("/", [NotificationCategoryController::class , "index"]);
    Route::post("/", [NotificationCategoryController::class , "store"]);
    Route::put("/{id}", [NotificationCategoryController::class , "update"]);
    Route::get("/{id}", [NotificationCategoryController::class , "viewbyid"]);
    Route::delete("/{id}", [NotificationCategoryController::class , "destroy"]);
});

Route::prefix("noticeboard")->group(function ()
{
    Route::get("/", [NoticeBoardController::class , "index"]);
    Route::post("/", [NoticeBoardController::class , "store"]);
    Route::put("/{id}", [NoticeBoardController::class , "update"]);
    Route::get("/{id}", [NoticeBoardController::class , "viewbyid"]);
    Route::delete("/{id}", [NoticeBoardController::class , "destroy"]);
});

Route::prefix("announcements")->group(function ()
{
    Route::get("/", [AnnouncementController::class , "index"]);
    Route::post("/", [AnnouncementController::class , "store"]);
    Route::put("/{id}", [AnnouncementController::class , "update"]);
    Route::get("/{id}", [AnnouncementController::class , "viewbyid"]);
    Route::delete("/{id}", [AnnouncementController::class , "destroy"]);
});

Route::prefix("std_sec_group_mapping")->group(function ()
{
    Route::get("/", [StandardSectionMappingController::class , "index"]);
    Route::post("/", [StandardSectionMappingController::class , "store"]);
    Route::put("/{id}", [StandardSectionMappingController::class , "update"]);
    Route::get("/{id}", [StandardSectionMappingController::class , "viewbyid"]);
    Route::post("/sections", [StandardSectionMappingController::class , "getSectionsByStandardAndGroup", ]);
    Route::delete("/{id}", [StandardSectionMappingController::class , "destroy", ]);
});

Route::prefix('groups')->group(function () {
    Route::get('/', [GroupMasterController::class, 'index']);
    Route::post('/', [GroupMasterController::class, 'store']);
    Route::put('/{id}', [GroupMasterController::class, 'update']);
    Route::get('/{id}', [GroupMasterController::class, 'viewbyid']);
    Route::delete('/{id}', [GroupMasterController::class, 'destroy']);
});
Route::prefix('exam_master')->group(function () {
    Route::get('/', [ExamMasterController::class, 'index']);
    Route::get('/add-list/{id}', [ExamMasterController::class, 'addList']);
    Route::post('/', [ExamMasterController::class, 'store']);
    Route::put('/{id}', [ExamMasterController::class, 'update']);
    Route::get('/{id}', [ExamMasterController::class, 'viewbyid']);
    Route::delete('/{id}', [ExamMasterController::class, 'destroy']);
});

Route::prefix('groups')->group(function () {
    Route::get('/', [GroupMasterController::class, 'index']);
    Route::post('/', [GroupMasterController::class, 'store']);
    Route::put('/{id}', [GroupMasterController::class, 'update']);
    Route::get('/{id}', [GroupMasterController::class, 'viewbyid']);
    Route::delete('/{id}', [GroupMasterController::class, 'destroy']);
});
Route::prefix("dropdowntypes")->group(function ()
{
    Route::get("/", [DropdowntypeMasterController::class , "index"]);
    Route::post("/", [DropdowntypeMasterController::class , "store"]);
    Route::put("/{id}", [DropdowntypeMasterController::class , "update"]);
    Route::get("/{id}", [DropdowntypeMasterController::class , "viewbyid"]);

    Route::delete("/{id}", [DropdowntypeMasterController::class , "destroy"]);
});
Route::prefix("donor")->group(function ()
{
    Route::get("/", [DonorController::class , "index"]);
    Route::post("/", [DonorController::class , "store"]);
    Route::put("/{id}", [DonorController::class , "update"]);
    Route::get("/{id}", [DonorController::class , "viewbyid"]);
    Route::delete("/{id}", [DonorController::class , "destroy"]);
});
Route::prefix("donation")->group(function ()
{
    Route::get("/", [DonationController::class , "index"]);
    Route::post("/", [DonationController::class , "store"]);
    Route::post("/update", [DonationController::class , "update"]);
    Route::get("/{id}", [DonationController::class , "viewbyid"]);
    Route::delete("/{id}", [DonationController::class , "destroy"]);
});

Route::prefix("contact")->group(function ()
{
    Route::get("/all", [ContactController::class , "viewAllStaff"]);
    Route::get("/view/{id}", [ContactController::class , "viewStaff"]);
    Route::post("/add", [ContactController::class , "addStaff"]);
    Route::post("/edit/{id}", [ContactController::class , "editStaff"]);
    Route::delete("/{id}", [ContactController::class , "destroy"]);

});
Route::get("/get_all_Tiles", [TileController::class , "getallTiles"]);

Route::get("/class-subjects-mark", [ClassSubjectController::class , "viewAll"]); // View all
Route::get("/class-subjects/{class}", [ClassSubjectController::class , "viewByClass", ]); // View by class
Route::post("/class-subjects-mark", [ClassSubjectController::class , "bulkInsert", ]); // Insert
Route::delete("/class-subjects-delete", [ClassSubjectController::class , "delete", ]); // Delete
Route::put("/class-subjects-update", [ClassSubjectController::class , "updateBulk", ]); // Bulk Update
Route::get("/class-teachers", [ClassTeacherController::class , "index"]); // View all
Route::post("/class-teachers", [ClassTeacherController::class , "store"]); // Store teacher details
Route::get("/class-teachers/{id}", [ClassTeacherController::class , "show"]); // Fetch teacher details
Route::put("/class-teachers/{id}", [ClassTeacherController::class , "update"]); // Update teacher details
Route::delete("/class-teachers/{id}", [ClassTeacherController::class , "destroy", ]); // Delete teacher
Route::post("/class-subjects", [ClassSubjectMappingController::class , "bulkInsert", ]); // Bulk Insert
Route::get("/class-subjects", [ClassSubjectMappingController::class , "getAll"]); // Get All Mappings
Route::get("/class-subjects-map/{class}", [ClassSubjectMappingController::class , "getByClass", ]); // Get Subjects by Class
Route::put("/class-subjects", [ClassSubjectMappingController::class , "updateSubjects", ]); // Update Subjects for a Class
Route::delete("/class-subjects-delete/{class}", [ClassSubjectMappingController::class , "deleteSubjects", ]); // Delete Subjects for a Class
Route::prefix("staff-fees-masters")->group(function ()
{
    Route::get("/", [StaffFeesMasterController::class , "index"]); // List all records
    Route::post("/", [StaffFeesMasterController::class , "store"]); // Create a new record
    Route::get("{id}", [StaffFeesMasterController::class , "viewbyid"]); // View a single record by ID
    Route::put("{id}", [StaffFeesMasterController::class , "update"]); // Update a record by ID
    Route::delete("{id}", [StaffFeesMasterController::class , "destroy"]); // Delete a record by ID

});
Route::post("/permissions/add", [PermissionController::class , "store"]); // Insert or Update All
Route::get("/permissions", [PermissionController::class , "index"]); // Get All in Given Format
Route::put("/permissions/{id}", [PermissionController::class , "update"]); // Update Single
Route::post("/mapStaffFees/create", [StaffInvoiceController::class , "mapStaffFees", ]);
Route::get("/mapStaffFees", [StaffInvoiceController::class , "listAllStaffmapFees", ]);
Route::get("/getAllStaffFees", [StaffInvoiceController::class , "getAllStaffFees", ]);
Route::get("/mapStaffFees/{id}", [StaffInvoiceController::class , "mapStaffFeesbyid", ]);
Route::put("/updatemapStaffFees/{id}", [StaffInvoiceController::class , "updateStaffFees", ]);
Route::delete("/deletemapStaffFees/{id}", [StaffInvoiceController::class , "deletemapStaffFees", ]);
Route::post("/staff-invoices/create", [StaffInvoiceController::class , "createInvoices", ]);
Route::post("/staff-invoices/pay", [StaffInvoiceController::class , "storePayment", ]);
Route::get("/staff-invoices/{id}", [StaffInvoiceController::class , "getInvoice", ]);
Route::get("/staff-receiptNo/{receiptNo}", [StaffInvoiceController::class , "getReceiptDetails", ]);
Route::get("/staff-invoiceNo/{invoiceNo}", [StaffInvoiceController::class , "getInvoiceDetails", ]);
Route::delete("/staff-invoice/delete/{invoice_no}", [StaffInvoiceController::class , "deleteStaffInvoiceByInvoiceNo", ]);

Route::apiResource("hostel-admissions", HostelAdmissionController::class);
Route::post("hostel-admissions/bulk-update-status", [HostelAdmissionController::class , "bulkUpdateStatus", ])
    ->name("hostel-admissions.bulk-update-status");
Route::post("hostel-admissions/bulkArrialDepature", [HostelAdmissionController::class , "bulkArrialDepature", ]);
Route::get("send-mails-bg/{filename}", [HostelAdmissionController::class , "sendMailsBackground", ])
    ->name("hostel.sendMails.bg");

Route::post("storeSendForm", [HostelAdmissionController::class , "storeSendForm", ])
    ->middleware("throttle:1000,1");
Route::post("/hostel-admissions/verify-otp", [HostelAdmissionController::class , "verifyOtpAndStore", ]);
Route::post("/hostel-admissions/sendOtp", [HostelAdmissionController::class , "sendOtp", ]);

Route::get("/attendance", [StudentAttendanceController::class , "index"]);
Route::post("/attendance", [StudentAttendanceController::class , "store"]);
Route::get("/attendance/{id}", [StudentAttendanceController::class , "show"]);
Route::put("/attendance/{id}", [StudentAttendanceController::class , "update"]);
Route::delete("/attendance/{id}", [StudentAttendanceController::class , "destroy", ]);
Route::post("/attendance/bulk", [StudentAttendanceController::class , "storeBulk", ]);

