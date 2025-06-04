<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
           .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        
    </style>
</head>
<body>
    
     <div class="header">
                    <img src="{{ $message->embed(public_path('images/1.jpg')) }}" alt="Logo" style="width: 20%;">

             <h1>Santhosha Vidhyalaya</h1>
                 <strong>Payment Receipt</strong>

        </div>

    <p>Dear {{ $invoiceDetails->name }},</p>
 <h1 style="color: red;">This is a New Santhosha Vidhyalaya payment software test ,Please ignore this email.</h1>
     <p>Thank you for your payment. Below are your payment details:</p>

    <ul>   
        <li>Invoice ID: {{ $invoiceDetails->invoice_no }}</li>
        <li>Transaction ID: {{ $transactionId}}</li>
        <li>Amount Paid: Rs. {{ $amount }}.00</li>
        <li>Payment Status: {{ $payment_status }}</li>
    </ul>

    <p>You can download your payment receipt by clicking the link/button below:</p>
  <a href="{{ $downloadLink }}" class="download-btn">
        <img src="{{ $message->embed(public_path('images/download.png')) }}" alt="PDF" width="270" height="70"/>
     </a>
    <p>This email confirms your recent payment!</p>

 <p>Sincerely,</p>
  <p>Santhosha Vidhyalaya Administration</p></body>
</html>
