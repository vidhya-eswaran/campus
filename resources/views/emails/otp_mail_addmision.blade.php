<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style=" padding: 20px; text-align: center;">
            <!-- <img src="{{ $message->embed(public_path('images/1.jpg')) }}" alt="Logo" style="height: 120px;"> -->
            <h2 style="color: black; margin-top: 10px;">Hostel Admission Portal</h2>
        </div>

        <!-- Body -->
        <div style="padding: 30px;">
            <p>Dear {{ $name }},</p>

            <p>We received a request to verify your email for hostel admission.</p>

            <p style="font-size: 24px; font-weight: bold; text-align: center; color: #ff6f00;">
                Your OTP is: {{ $otp }}
            </p>

            <p>This OTP is valid for only a 10 minutes. Please do not share it with anyone.</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #555;">
            <p>If you did not initiate this request, please ignore this email.</p>
            <p>&copy; {{ date('Y') }}  Santhosha Vidhyalaya, All rights reserved.</p>
        </div>
    </div>
</body>
</html>
