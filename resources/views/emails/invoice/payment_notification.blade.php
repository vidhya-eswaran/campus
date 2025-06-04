<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Notification</title>
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

        h1 {
            color: #333333;
            margin-bottom: 20px;
        }

        p {
            color: #666666;
            margin-bottom: 10px;
        }

        .highlight {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }

        .no-reply {
            color: #999999;
            font-size: 12px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img class="logo" src="https://santhoshavidhyalaya.com/svsportaladmin/static/media/Svs-invoice.f86bd51493e0e8166940.jpg" alt="Santhosha Vidhyalaya Logo">
        <h1>Payment Notification</h1>
        <p>Dear {{ $user->name }},</p>

        <p>Your payment was successful. Here are the details:</p>

        <div class="highlight">
            <p>Transaction ID: {{ $paymentNotificationData->txnId }}</p>
            <p>Paid Amount: {{ $paymentNotificationData->paidAmount }}</p>
            <p>Invoice Numbers: {{ $paymentNotificationData->invoice_nos }}</p>
        </div>

        <p>Thank you for your payment!</p>
        <p>You can download the Invoice and Receipt from our Portal where the payment was made.</p>
        <p>If you have any questions, please contact our support team.</p>

        <p>
            Best regards,<br>
            Santhosha Vidhyalaya School Accounts Team
        </p>
        <p>Click below to navigate to the portal:</p>
        <p style="text-align: center;">
            <a class="btn" href="https://santhoshavidhyalaya.com">Payment Portal</a>
        </p>

        <p class="no-reply">Please do not reply to this email. This is an automated notification.</p>
    </div>
</body>
</html>
