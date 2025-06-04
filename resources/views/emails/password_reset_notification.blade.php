<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Update Notification</title>
    <style>
        /* CSS styles for the email template */
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333333;
            margin-bottom: 20px;
        }

        p {
            color: #666666;
            margin-bottom: 10px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Password Updated Successful</h2>
    <p>Your password has been successfully Changed.</p>
    <p>Email: {{ $email }}</p>
    <p>Name: {{ $name }}</p>
    <p>{{ $newPassword }}</p>
    <p>If you did not initiate this password reset, please contact our support team immediately.</p>
    <p>Thank you.</p>

    <!-- Add the logo image -->
    <img class="logo" src="https://santhoshavidhyalaya.com/svsportaladmin/static/media/Svs-invoice.f86bd51493e0e8166940.jpg" alt="Santhosha Vidhyalaya Logo">
</body>
</html>
