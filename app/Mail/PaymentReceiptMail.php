<?php
namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceDetails;
    public $downloadLink;
    public $amount;
    public $payment_status;
    public $transactionId;
    /**
     * Create a new message instance.Mail::to($invoiceDetails->email)->queue(new PaymentReceiptMail($invoiceDetails, $downloadLink,$amount,$payment_status));     

     *
     * @param $invoiceDetails
     * @param $downloadLink
     */
    public function __construct($invoiceDetails, $downloadLink,$amount ,$payment_status,$transactionId )
    {
        $this->invoiceDetails = $invoiceDetails;
        $this->downloadLink = $downloadLink;
        $this->amount = $amount;
        $this->payment_status = $payment_status;
         $this->transactionId =$transactionId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
    {
        try {
            // Log the download attempt
            // Log::info('Attempting to fetch PDF from ' . $this->downloadLink);

            // // Use Laravel's Storage to download the file to local storage
            // $pdfFileName = 'invoice_generated_' . time() . '.pdf';
            // $pdfPath = 'temp/' . $pdfFileName;

            // // Download the PDF from the URL using Storage facade
            // Storage::disk('local')->put($pdfPath, file_get_contents($this->downloadLink));

            // // Log success
            // Log::info('PDF successfully downloaded to ' . $pdfPath);

            // Attach the PDF to the email
            return $this->subject('Payment Receipt')
                        ->view('emails.invoice.payment_receipt');
                    //    ->attach(storage_path('app/' . $pdfPath), [
                      //      'as' => $pdfFileName,
                        //    'mime' => 'application/pdf',
                       // ]);
        } catch (\Exception $e) {
            // Log any error that occurs during the process
            Log::error( ' | Error: ' . $e->getMessage());

            // If the download fails, just return the email without an attachment
            return $this->subject('Payment Receipt')
                        ->view('emails.invoice.payment_receipt');
        }
    }
}



 