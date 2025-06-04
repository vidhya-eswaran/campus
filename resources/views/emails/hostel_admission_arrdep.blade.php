<!DOCTYPE html>
<html>
<head>
    <title>Hostel Admission Form Updated</title> 
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-top: 20px;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        h1 {
            color: #932128;
            margin-bottom: 0;
        }

        .content {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            max-width: 650px;
            margin: 0 auto;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .status {
            font-weight: bold;
            color: #932128;
        }

        .download-btn {
            display: inline-block;
            margin-top: 25px;
            background-color: #FF5722;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease-in-out;
        }

        .download-btn:hover {
            background-color: #e64a19;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            margin: 40px auto 20px;
            font-size: 14px;
            color: #888;
        }

    </style>
</head>
<body>

    <div class="header">
        <img src="{{ $message->embed(public_path('images/1.jpg')) }}" alt="Logo" class="logo">
        <h1>Santhosha Vidhyalaya</h1>
    </div>

    <div class="content">
   <p>Dear {{ $hostelAdmission->student->name }},</p>

<p>
    This is to inform you that your 
    <strong>Hostel 
        @if($hostelAdmission->arr_dep_status == 1)
            Arrival
        @elseif($hostelAdmission->arr_dep_status == 2)
            Departure
        @else
            —
        @endif 
        Slip
    </strong> is now available.
</p>


<p><strong>Status:</strong> 
  <span class="status">
     @if($hostelAdmission->arr_dep_status == 1)
        Arrival
    @elseif($hostelAdmission->arr_dep_status == 2)
        Departure
    @else
        —
    @endif
  </span>
</p>

<p>You can download your slip from the link below:</p>

<p><a href="{{ $hostelAdmission->url  }}" target="_blank">Click here to download the slip</a></p>

<p>If you have any questions or require assistance, please don't hesitate to reach out to us.</p>

<p>Thank you,<br>Hostel Administration</p>
 <!--<a href="https://sna.form.com?student_id={{ $hostelAdmission->student_id }}&roll_no={{ $hostelAdmission->student->roll_no }}&acad_year={{ $hostelAdmission->acad_year }}" class="download-btn" target="_blank">-->
        <!--    View Updated Form-->
        <!--</a>-->
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Santhosha Vidhyalaya. All rights reserved.
    </div>

</body>
</html>
