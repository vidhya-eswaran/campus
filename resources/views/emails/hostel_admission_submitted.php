<!DOCTYPE html>
<html>
<head>
    <title>Hostel Admission Form Submitted</title> 
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
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; color: #333;">

    <div class="header" style="text-align: center; margin-bottom: 30px;">
        <img src="{{ $message->embed(public_path('images/1.jpg')) }}" alt="Logo" class="logo" style="max-width: 150px;">
        <h1 style="margin-top: 10px;">Santhosha Vidhyalaya</h1>
    </div>

    <div class="content" style="background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <p>Dear Parent,</p>

        <p>Thank you for submitting your child’s hostel admission application.</p>

        <p>We confirm that we have received your application and our admissions team will review it shortly. You will be notified once the review process is complete or if any additional information is required.</p>
 
        <p>Warm regards,<br>
        Principal<br>
        Santhosha Vidhyalaya</p>
    </div>

    <div class="footer" style="text-align: center; margin-top: 30px; font-size: 14px; color: #999;">
        &copy; {{ date('Y') }} Santhosha Vidhyalaya. All rights reserved.
    </div>

</body>

</html>
