<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invoice</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            /*color: #333;*/
            /*font-size: 12px;*/
            /*margin: 0;*/
            /*padding: 0;*/
        }

        /* Title Styling */
        .invoice-title {
            background-color: rgb(12, 131, 220);
            text-align: center;
            padding: 5px 0;
            color: white;
            /*font-size: 16px;*/
            margin: 0;
            border-radius: 0px 6px 6px 0px;
        }

        .centered-title {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .centered-title h2 {
            display: inline-block;
            text-decoration: underline;
            /*font-size: 18px;*/
            margin: 0;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            /*font-size: 12px;*/
        }

        .striped-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .striped-table tr:hover {
            background-color: #e0e0e0;
        }

        .fees-details th,
        .fees-details td,
        .bank-details th,
        .bank-details td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .text-end {
            text-align: right;
        }

        /* Footer Section */
        .footer {
            text-align: center;
            font-size: 10px;
            /*margin-top: 15px;*/
        }
    </style>
</head>
<body>
  <div style="border: 1px solid black;">
    <!-- Invoice Header -->
    <div style="margin-bottom: 10px;width:40%;padding-top: .25rem !important;">
        <h5 class="invoice-title">INVOICE</h5>
    </div>

    <!-- School Details and Logo -->
    <div style="padding:10px;">
    <table>
        <tr>
            <td width="60%">
                <h3 style="margin: 0; font-size: 22px;line-height: 29px;">Santhosha Vidhyalaya</h3>
                <p style="margin: 2px 0;font-size: 16px;">Dohnavur Fellowship, Dohnavur – 627102, Tirunelveli District, Tamil Nadu</p>
                <p style="margin: 2px 0;font-size: 16px;">Mobile: +91 80125 12145 | Email: finance@santhoshavidhayalaya.com</p>
            </td>
            <td width="40%" class="text-end">
                <img src="https://santhoshavidhyalaya.com/svsportaladmin/static/media/Svs-invoice.f86bd51493e0e8166940.jpg" alt="Logo" style="width: 60%; max-width: 150px;">
            </td>
        </tr>
    </table>

    <div class="centered-title">
        @if($feesItemsDetails[0]['fees_heading'] == 'School Fees')
        <h2 style="font-size: 25px;">SCHOOL FEES</h2>
        @else
        <h2 style="font-size: 25px;">FEES</h2>
        @endif
    </div>

    <!-- Student Details -->
    <table>
        <tr>
            <td width="50%">
                <h3 style="margin: 5px 0;font-size: 22px;line-height: 29px;">Student Details</h3>
                <table>
                    <tr><td><strong><span  style="font-size: 14px;">Name</span></strong></td><td>: {{ $name }}</td></tr>
                    <tr><td><strong><span  style="font-size: 14px;">Class</span></strong></td><td>: {{ $standard }}</td></tr>
                    <tr><td><strong><span  style="font-size: 14px;">Section</span></strong></td><td>: {{ $sec }}</td></tr>
                    <tr><td><strong><span  style="font-size: 14px;">Roll No</span></strong></td><td>: {{ $rollNo }}</td></tr>
                    <tr><td><strong><span  style="font-size: 14px;">Group</span></strong></td><td>: {{ $tweGroup }}</td></tr>
                </table>
            </td>
            <td width="50%" class="text-end">
                <p style="margin: 2px 0;font-size: 14px;line-height: 19px;"><strong>INVOICE DATE:</strong> {{$created_at}}</p>
                <p style="margin: 2px 0;font-size: 14px;line-height: 19px;"><strong>INVOICE NO:</strong> {{ $invoiceNo }}</p>
            </td>
        </tr>
    </table>

    <!-- Fees Details -->
    <table class="fees-details">
        <thead style="font-size: 16px;">
            <tr>
                <th>No</th>
                <th>Fees Description</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody style="font-size: 16px;" class="striped-table">
            @php
            $i = 1;
            @endphp
            @foreach($feesItemsDetails as $fee)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $fee['fees_sub_heading'] }}</td>
                <td class="text-end">Rs. {{ $fee['amount'] }}</td>
            </tr>
            @php
            $i++;
            @endphp
            @endforeach
        </tbody>
    </table>

    <!-- Bank Details and Summary in Same Row -->
    <table style="margin-top: 15px;">
        <tr>
            <td width="60%">
                <table class="bank-details">
                    <tr>
                        <td colspan="6" style="padding: 4px;background-color: #f2f2f2;font-size: 14px;">
                            You can make the payment through bank transfer to the below mention account, or scan the QR Code or use the UPI ID.
                        </td>
                    </tr>
                    <tr>
                        @if($feesItemsDetails[0]['fees_heading'] == 'School Fees')
                        <td colspan="4" style="font-size: 12px;">
                            <strong>SCHOOL BANK DETAILS</strong><br>
                            Account Name: SANTHOSHA VIDHYALAYA - SCHOOL<br>
                            Account No.: 1379 0200 0000 272<br>
                            IFSC Code: IOBA0001379<br>
                            Bank: Indian Overseas Bank<br>
                            Branch: Dohnavur
                        </td>
                        <td colspan="2">
                            <img src="http://santhoshavidhyalaya.com/svsportaladmin/static/media/school%201.25fcf93c6f1d9ad2fb4e.jpg" alt="QR Code" style="width: 80px; height: 80px;">
                        </td>
                        @else
                        <td colspan="4" style="font-size: 12px;">
                            <strong>BANK DETAILS</strong><br>
                            Account Name: SANTHOSHA VIDHYALAYA - HOSTEL<br>
                            Account No.: 1379 0200 0000 272<br>
                            IFSC Code: IOBA0001379<br>
                            Bank: Indian Overseas Bank<br>
                            Branch: Dohnavur
                        </td>
                        <td colspan="2">
                            <img src="https://santhoshavidhyalaya.com/svsportaladmin/static/media/hostel%201.fd5265bee10d41c58b4c.jpg" alt="QR Code" style="width: 80px; height: 80px;">
                        </td>
                        @endif
                    </tr>
                </table>
            </td>
            <td width="40%" class="text-end">
                <table width="100%" style="font-size: 14px;">
                    <tr><th style="text-align: left;">Total :</th><td class="text-end">{{$actual_amount}}</td></tr>
                                        @foreach($discount_items_details as $dee)
                    <tr><th style="text-align: left;">{{$dee['discount_cat']}} :</th>
                    <td class="text-end">{{$dee['dis_amount']}}</td>
                                        </tr>
                    @endforeach
                    <tr><th style="text-align: left;">Total Discount :</th><td class="text-end">(-) {{ $discount_percent  }}</td></tr>
                    @if(isset($amount))
                    <tr><th style="text-align: left;">Total Payable :</th><td class="text-end">Rs. {{ $amount }}</td></tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <p style="margin-top: 10px;font-size: 11px;">
        <strong>After making the payment, please notify us with your transaction details, child's full name, and class to the contact below:</strong><br>
        WhatsApp: (+91 801 251 2145)<br>
        Email: finance@santhoshavidhayalaya.com.
    </p>
     <table width="100%">
        <tr>
            <td width="60%">
                <table style="font-size: 14px;">
                 <tr><td><strong>Due Amount : </strong>{{ ($due_amount ?? 0) !== 0 ? 'Rs.' . number_format($due_amount ?? 0, 2) : 'Rs.0.00' }}</td></tr>
                     @if($feesItemsDetails[0]['fees_heading'] == 'School Fees')
                    <tr><td><strong>Excess Amount : </strong>{{ ($excess_amount ?? 0) !== 0 ? 'Rs.' . number_format($excess_amount ?? 0, 2) : 'Rs.0.00' }}</td></tr>
                    @else
                    <tr><td><strong>Excess Amount : </strong>{{ ($h_excess_amount ?? 0) !== 0 ? 'Rs.' . number_format($h_excess_amount ?? 0, 2) : 'Rs.0.00' }}</td></tr>
                    @endif
                </table>
            </td>
            <td width="40%" class="text-end">
                <p style="font-size: 14px;"><strong>Accounts Coordinator/Assistant</strong></p>
                
            </td>
        </tr>
    </table>
    </div>
</div>
    <div class="footer">
        <p>THIS IS A COMPUTER-GENERATED INVOICE. NO SIGNATURE IS REQUIRED.</p>
    </div>
</body>
</html>
