<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
        font-family: Calibri, Arial, sans-serif;
            margin: 0;
            padding: 0;
        line-height: 0.6;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 1px;
            border-bottom: 1px solid #ccc;
        }
        .footer {
  font-size: 8px;
 
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
  .total-discounts {
            margin-top: 5px;
            text-align: right;
        }
.total-discountsb {
            margin-top: 5px;
            text-align: left;
        }
  .total-discountsc {
        font-family: Arial, sans-serif;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .school-name {
        font-family: "Your Special Font", Arial, sans-serif;
        /* Add your special font name after "Your Special Font" */
        font-size: 24px; /* Adjust the font size as needed */
        margin-bottom: 10px;
    }

    .total-discountsc img {
        max-width: 100px; /* Adjust the maximum width as needed */
        height: auto;
        margin-bottom: 10px;
    }  
.total-discountjk{
 
            text-align: center;
        }

  </style>


</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
        </div>
 
           <div class="total-discountsc">
    <img src="https://santhoshavidhyalaya.com/svsportaladmin/static/media/Svs-invoice.f86bd51493e0e8166940.jpg" alt="Company Logo">
            <p class="school-name">Santhosha Vidhyalaya</p>
            <p>Dohnavur Tirunelveli</p>
            <p>pincode - 627102 </p>
            <p>Tamilnadu </p>
            <p>+91 8012512100 / 8012512143</p
        </div>
 
<div class="total-discountjk">

@if($feesItemsDetails[0]['fees_heading'] == 'School Fees')
    <p><b><u>School Fees</u></b></p>
@else
    <p><b><u>Hostel Fees</u></b></p>

@endif
</div>

  

   <div class="total-discountb">
            <p>Name: {{ $name }}</p>
<p>Grade/Sec: {{ $standard }} - {{ $sec }}</p>
<p>ROLLNO: {{ $rollNo }}</p>
        </div>

        <table>
            <thead>
                <tr>
            <!-- <th>Fees Heading</th> -->
                    <th>Fees Sub Heading</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                  @foreach($feesItemsDetails as $fee)
            <tr>
               <!-- <td>{{ $fee['fees_heading'] }}</td> -->

                <td>{{ $fee['fees_sub_heading'] }}</td>
                <td>Rs. {{ $fee['amount'] }}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
         <div class="total-discounts">
            <p>Total Actual Amt:  {{ $total_invoice_amount }}</p>

   @foreach($discount_items_details as $dee)
             <p> $dee['discount_cat']: - $dee['dis_amount']</p> 
        @endforeach

            <p>Discounts Total:- {{ $discount_percent  }}</p>
            <p>Total:  {{ $amount }}</p>
        </div>
        <div class="footer">
    <p>Thank you for your attention. This is a system-generated invoice. If there are any issues, please contact the finance team of Santhosha Vidhyalaya.</p>
        </div>
    </div>
</body>
</html>
