<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EUCTO CAMPUS - School Fee Payment</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background-color: #f8f9fa; /* Light background */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .header-container {
            background-color: #ffffff; /* White background for the header */
            padding: 30px 20px;
            border-bottom: 1px solid #e0e0e0;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .logo-text-group {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo {
            margin-bottom: 15px;
        }
        .logo img {
            max-width: 120px; /* Adjusted size for a cleaner look */
            height: auto;
            border-radius: 8px; /* Slightly rounded corners for the logo if it's a square image */
        }
        .title {
            font-size: 2.2em; /* Slightly larger for prominence */
            font-weight: 700; /* Bolder */
            color: #2c3e50; /* Darker, professional text color */
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 1.3em; /* Adjusted size */
            color: #7f8c8d; /* Muted color for subtitle */
            margin-bottom: 0; /* No bottom margin here as it's part of the header */
        }
        .content-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 90%;
            box-sizing: border-box;
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            font-size: 1.8em;
            color: #34495e; /* Darker color for main heading */
            margin-bottom: 25px;
        }
        .loading-message {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1em;
            color: #555;
        }
        .loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #3498db; /* A vibrant blue for the spinner */
            border-radius: 50%;
            width: 25px;
            height: 25px;
            animation: spin 1s linear infinite;
            margin-right: 15px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Optional: Hide body content until JS takes over for smoother load */
        body:not(.razorpay-loaded) .content-container {
            visibility: hidden;
        }
    </style>
</head>
<body>
    <header class="header-container">
        <div class="logo-text-group">
            <div class="logo">
                <img src="{{ asset('images/CampusLogo.png') }}" alt="EUCTO Logo">
            </div>
            <div class="title">EUCTO CAMPUS</div>
            <div class="subtitle">School Fee Payment</div>
        </div>
    </header>

    <div class="content-container">
        <h2>Initiating Secure Payment...</h2>
        <div class="loading-message">
            <div class="loading-spinner"></div>
            <p>Please wait while we redirect you to the Razorpay payment gateway.</p>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add a class to body once DOM is ready, for optional visual effects
            document.body.classList.add('razorpay-loaded');

            var options = @json($checkoutData);

            // Override the default handler to send data via POST
            options.handler = function (response) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = options.callback_url;
                form.style.display = 'none'; // Hide the form

                const fields = {
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature,
                    _token: '{{ csrf_token() }}' // Laravel CSRF token
                };

                for (const key in fields) {
                    if (fields.hasOwnProperty(key)) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = fields[key];
                        form.appendChild(input);
                    }
                }
                document.body.appendChild(form);
                form.submit();
            };

            // Enhance theme color for Razorpay modal
            options.theme = {
                "color": "#3498db" // A pleasant blue
            };

            var rzp1 = new Razorpay(options);

            // Handle payment failure
            rzp1.on('payment.failed', function (response){
                let errorMessage = "Payment failed. Please try again.";
                if (response.error && response.error.description) {
                    errorMessage = "Payment failed: " + response.error.description;
                }
                window.location.href = '{{ $checkoutData["callback_url"] }}?' + encodeURIComponent(JSON.stringify({
                    status: false,
                    msg: errorMessage
                }));
            });

            // Handle modal closed by user
            rzp1.on('modal.closed', function() {
                window.location.href = '{{ $checkoutData["callback_url"] }}?' + encodeURIComponent(JSON.stringify({
                    status: false,
                    msg: "Payment cancelled by user."
                }));
            });

            // Open Razorpay checkout immediately
            rzp1.open();
        });
    </script>
</body>
</html>