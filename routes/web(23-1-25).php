<?php

use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\dashboardController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\AdmissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/add-student', [App\Http\Controllers\API\StudentController::class, 'index']);
Route::get('/dashboard', [App\Http\Controllers\API\dashboardController::class, 'countstudent']);

Route::get('/intiate-payment', [PaymentsController::class, 'intiatePayment']);
Route::post('/process-data', [PaymentsController::class, 'processData']);
Route::post('/redirect', [PaymentsController::class, 'processRetrunResponse']);
Route::resource('admission',AdmissionController::class);
Route::post('/admission-redirect', [AdmissionController::class, 'admissionRetrunResponse']);
Route::get('/admission-data', [AdmissionController::class, 'Data']);
Route::get('/admission-offline', [AdmissionController::class, 'Offline']);
Route::post('/offline-store', [AdmissionController::class,'offline_store'])->name('admission.offline_store');


// Route::get('/admission/addedit', 'AdmissionController@addedit')->name('admission.addedit');

//Email
Route::get('/admission-view/{id}', [AdmissionController::class, 'view'])->name('admission.view_admission');
//Email

Route::get('/', function () {
    dd("Ffffff");
});
Route::get('/offline-store', [AdmissionController::class,'offline_store'])->name('admission.offline_store');

//Route::get('/admission-view/{id}', 'App\Http\Controllers\AdmissionController@view')->name('admission.view_admission');
Route::get('/documents/{id}',[AdmissionController::class,'documents'])->name('admission.documents');

// Route::get('/admission-view/{id}', function () {
// return view('admission.view_admission');
    
// });
