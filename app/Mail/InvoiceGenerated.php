<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class InvoiceGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceNo;
    public $rollNo;
    public $name;
    public $standard;
    public $tweGroup;
    public $sec;
    public $hostelOrDay;
    public $sponsorId;
    public $email;
    public $feesGlance;
    public $feesCategory;
    public $feesItemsDetails;
    public $discount_items_details;
    public $amount;
    public $total_invoice_amount;
    public $discount_percent;
    public $actual_amount;
    public $invoice_pending_amount;
    public $created_at;
    public $due_amount;
    public $excess_amount;
    public $h_excess_amount;
   

    /**
     * Create a new message instance.
     *
     * @param  string  $invoiceNo
     * @param  string  $rollNo
     * @param  string  $name
     * @param  string  $standard
     * @param  string  $tweGroup
     * @param  string  $sec
     * @param  string  $hostelOrDay
     * @param  string  $sponsorId
     * @param  string  $email
     * @param  string  $feesGlance
     * @param  string  $feesCategory
     * @param  string  $feesItemsDetails
     * @param  string  $discount_items_details
     * @param  string  $amount
     * @param  string  $total_invoice_amount
     * @param  string  $discount_percent
     * @param  string  $actual_amount
     * @param  string  $invoice_pending_amount
     * @return void
     */
    public function __construct(
        $invoiceNo,
        $rollNo,
        $name,
        $standard,
        $tweGroup,
        $sec,
        $hostelOrDay,
        $sponsorId,
        $email,
        $feesGlance,
        $feesCategory,
        $feesItemsDetails,
        $discount_items_details,
        $amount,
        $total_invoice_amount,
        $discount_percent,
        $actual_amount,
        $invoice_pending_amount,
        $created_at,
        $due_amount,
        $excess_amount ,
        $h_excess_amount


    ) {
        $this->invoiceNo = $invoiceNo;
        $this->rollNo = $rollNo;
        $this->name = $name;
        $this->standard = $standard;
        $this->tweGroup = $tweGroup;
        $this->sec = $sec;
        $this->hostelOrDay = $hostelOrDay;
        $this->sponsorId = $sponsorId;
        $this->email = $email;
        $this->feesGlance = nl2br($feesGlance); // Replaced <br> tags with line breaks
        $this->feesCategory = $feesCategory;
        $this->feesItemsDetails = $feesItemsDetails;
        $this->discount_items_details = $discount_items_details;
        $this->amount = $amount;
        $this->total_invoice_amount = $total_invoice_amount;
        $this->discount_percent = $discount_percent;
        $this->actual_amount = $actual_amount;
        $this->invoice_pending_amount = $invoice_pending_amount;
        $this->created_at = $created_at;
        $this->due_amount = $due_amount;
        $this->excess_amount = $excess_amount;
        $this->h_excess_amount = $h_excess_amount;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->subject('Invoice Generated')
            //->view('emails.invoice.generated');
            // json_encode($invoice->discount_items_details),                        json_encode($invoice->fees_items_details),

            // $invoice->amount,
            // $invoice->total_invoice_amount,
            // $invoice->discount_percent
 
            $pdf = PDF::loadView('emails.invoice.generateds', [
                'invoiceNo' => $this->invoiceNo,
                'rollNo' => $this->rollNo,
                'name' => $this->name,
                'standard' => $this->standard,
                'tweGroup' => $this->tweGroup,
                'sec' => $this->sec,
                'hostelOrDay' => $this->hostelOrDay,
                'sponsorId' => $this->sponsorId,
                'email' => $this->email,
                'feesGlance' => $this->feesGlance,
                'feesCategory' => $this->feesCategory,
                'feesItemsDetails' => $this->feesItemsDetails,
                'discount_items_details' => $this->discount_items_details,
                'amount' => $this->amount,
                'total_invoice_amount' => $this->total_invoice_amount,
                'discount_percent' => $this->discount_percent,
                'actual_amount' => $this->actual_amount,
                'invoice_pending_amount' => $this->invoice_pending_amount,
                'created_at' => $this->created_at,
                'due_amount' => $this->due_amount,
                'excess_amount' => $this->excess_amount,
                'h_excess_amount' => $this->h_excess_amount,

            ]);
            // $currentDate = Carbon::now()->toDateString();
            $currentDate = Carbon::now()->format('d-m-Y');
            $pdfFileName = 'invoice_generated_' . $this->invoiceNo. '_'.$currentDate.'.pdf';
    
            // Save the PDF to storage
            Storage::put('pdf/' . $pdfFileName, $pdf->output());
    
            return $this->subject('Invoice Generated')
                ->view('emails.invoice.generated')
                ->attachData($pdf->output(), $pdfFileName, [
                    'mime' => 'application/pdf',
                ]);
    }
}
