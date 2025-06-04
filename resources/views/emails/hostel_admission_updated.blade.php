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

        <p>We would like to inform you that your hostel admission status has been <strong class="status">updated</strong>.</p>

        <p><strong>Status:</strong> <span class="status">{{ $hostelAdmission->status }}</span></p>

        <p>If you have any questions or need further assistance, please feel free to contact us.</p>

        <!--<a href="https://sna.form.com?student_id={{ $hostelAdmission->student_id }}&roll_no={{ $hostelAdmission->student->roll_no }}&acad_year={{ $hostelAdmission->acad_year }}" class="download-btn" target="_blank">-->
        <!--    View Updated Form-->
        <!--</a>-->
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Santhosha Vidhyalaya. All rights reserved.
    </div>

</body>
</html>
