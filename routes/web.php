<?php

use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\dashboardController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\DonationController;

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
// Route::get('/donate', [DonationController::class, 'showForm']);
// Route::post('/donate/step1', [DonationController::class, 'storeStep1'])->name('donate.step1');
// Route::post('/donate/step2', [DonationController::class, 'storeStep2'])->name('donate.step2');
// Route::post('/donate/submit', [DonationController::class, 'storeFinal'])->name('donate.submit');
// Route::get('/thank-you', [DonationController::class, 'thankYou'])->name('thank.you');

// Route::post('/add-student', [App\Http\Controllers\API\StudentController::class, 'index']);
// Route::get('/dashboard', [App\Http\Controllers\API\dashboardController::class, 'countstudent']);

// Route::get('/intiate-payment', [PaymentsController::class, 'intiatePayment']);
// Route::post('/process-data', [PaymentsController::class, 'processData']);
// Route::post('/redirect', [PaymentsController::class, 'processRetrunResponse']);
// Route::resource('admission',AdmissionController::class);
// Route::get('/admission-test', [AdmissionController::class, 'indexdemo']);
// Route::post('/admission-redirect', [AdmissionController::class, 'admissionRetrunResponse']);
// Route::get('/admission-data', [AdmissionController::class, 'Data']);
// Route::get('/admission-transection-data', [AdmissionController::class, 'Data_1']);
// Route::get('/admission-offline', [AdmissionController::class, 'offline_store']);
//Route::post('/offline-store', [AdmissionController::class,'offline_store'])->name('admission.offline_store');



// Route::get('/donate', function () {
//     return view('donation.donate');
// })->name('donate');
//Route::post('/donate/submit', [DonationController::class, 'submitDonation'])->name('submitDonation');
Route::get('/thank-you', function () {
    return view('thank-you');
})->name('thank.you');
//Route::post('/donation-redirect', [DonationController::class, 'donationRedirect'])->name('donation.redirect');



// Route::get('/admission/addedit', 'AdmissionController@addedit')->name('admission.addedit');

//Email
//Route::get('/admission-view/{id}', [AdmissionController::class, 'view'])->name('admission.view_admission');
//Email

// Route::get('/', function () {
//     dd("Ffffff");
// });
//Route::get('/offline-store', [AdmissionController::class,'offline_store'])->name('admission.offline_store');

//Route::get('/admission-view/{id}', 'App\Http\Controllers\AdmissionController@view')->name('admission.view_admission');
//Route::get('/documents/{id}',[AdmissionController::class,'documents'])->name('admission.documents');

// Route::get('/admission-view/{id}', function () {
// return view('admission.view_admission');
    
// });


// Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
// Route::get('/donation-details/{id}', [DonationController::class, 'show'])->name('donation.details');
// Route::post('/donation-confirm', [DonationController::class, 'confirm'])->name('donation.confirm');
// // Route::post('/donation-payment', [DonationController::class, 'processPayment'])->name('donation.payment');
// Route::get('/donation-success/{transaction_id}', [DonationController::class, 'paymentSuccess'])->name('donations.success');
// // Route::post('/donation-payment', [DonationController::class, 'processPayment'])->name('donation.payment');
// Route::post('/payment-response', [DonationController::class, 'paymentResponse'])->name('payment.response');
// Route::post('/donation-redirect', [DonationController::class, 'donationRedirect'])->name('donation.redirect');
// // Route::get('/donation-payment', function () {
// //     return view('donation_payment'); // Your Blade file
// // })->name('donation.payment');
// Route::post('/process-payment', [DonationController::class, 'processPayment'])->name('donation.payment');
