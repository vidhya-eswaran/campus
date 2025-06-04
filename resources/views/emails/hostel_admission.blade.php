<!DOCTYPE html>
<html>
<head>
    <title>Hostel Admission Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        h1, h2 {
            color: #333;
        }

        .content {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            text-align: left;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .download-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #FF5722;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .download-btn:hover {
            background-color: #e64a19;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }

    </style>
</head>
<body>

    <div class="header">
        <img src="{{ $message->embed(public_path('images/1.jpg')) }}" alt="Logo" class="logo">
        <h1>Santhosha Vidhyalaya</h1>
        <strong>Hostel Admission Form</strong>
    </div>

    <div class="content">
        <p>Dear Parent,</p>

        <p>We are pleased to inform you that Hostel Admission process is now open. for the academic year {{ $student['acad_year']  }} .</p>

        <p><strong>Please fill in all the required details in the form carefully.</strong></p>

 
        <p>You may submit your application by clicking the link below:</p>
<!--<p>{{ $student['url']  }}</p>-->
<!--<p>{{ $student['arrUrl']  }}</p>-->
<!--<pre>{{ print_r($student, true) }}</pre>-->

      <a href="{{ $student['url']  }}" class="download-btn" target="_blank">Fill the Hostel Admission Form</a>

<p><strong>Download the Arrival Form from here:</strong> 
   <a href="{{ $student['arrUrl'] }}" target="_blank">Click here</a>
</p>
    <p>For any assistance or queries, please contact our school/hostel office.</p>
    </div>

    <div class="footer">
        <p>Warm regards, <br> Principal<br>Santhosha Vidhyalaya</p>
    </div>

</body>
</html>

