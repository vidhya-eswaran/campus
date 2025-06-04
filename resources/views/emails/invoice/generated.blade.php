<!DOCTYPE html>
<html>
<head>
    <title>Invoice Generated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
        <img class="logo" src="{{ $message->embed(public_path('images/1.jpg')) }}" alt="Santhosha Vidhyalaya Logo">

             <h1>Santhosha Vidhyalaya</h1>
        </div>
        <h2>Invoice Generated</h2>
        <table>
            <tr>
                <th>Invoice No:</th>
                <td>{{ $invoiceNo }}</td>
            </tr>
            <tr>
                <th>Name:</th>
                <td>{{ $name }}</td>
            </tr>
            <tr>
                <th>Roll No:</th>
                <td>{{ $rollNo }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $email }}</td>
            </tr>
            <tr>
                <th>Standard:</th>
                <td>{{ $standard }}</td>
            </tr>
            <tr>
                <th>Section:</th>
                <td>{{ $sec }}</td>
            </tr>
            <tr>
                <th>Fees Category:</th>
 
@if($feesCategory == 'school')
    <td>School Fees</td>
@else
   <td>Hostel Fees</td>

@endif

            </tr>
            <tr>
                <th>Fees Glance:</th>
                <td>{!! $feesGlance !!}</td>
 
            </tr>
 <tr>
                <th>Total Amount:</th>
                <td>Rs. {{ $amount }}</td>
 
            </tr>
 <!--<tr>-->
 <!--       <th>Paid Amount:</th>-->
 <!--       <td>-->
 <!--           @if(isset($amount) && isset($invoice_pending_amount))-->
 <!--               Rs. {{ $amount - $invoice_pending_amount }}-->
 <!--           @else-->
 <!--               N/A-->
 <!--           @endif-->
 <!--       </td>-->
 <!--   </tr>-->
<!--<tr>-->
<!--    <th>Pending Amount:</th>-->
<!--    @if(isset($invoice_pending_amount))-->
<!--        <td>Rs. {{ $invoice_pending_amount }}</td>-->
<!--    @else-->
<!--        <td>N/A</td>-->
<!--    @endif-->
<!--</tr>-->

            <tr>
    <th>Payment:</th>
    <td>
        <a href="https://santhoshavidhyalaya.com/Payfeeportaltest/" target="_blank" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 4px;">Pay Now</a>
    </td>
</tr>

        </table>
        <p>This is an automatically generated email. Please do not reply.</p>
    </div>
</body>
</html>
