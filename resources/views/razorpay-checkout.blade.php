<!DOCTYPE html>
<html>
<head>
    <title>EUCTO CAMPUS - School Fee Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f5f5f5;
        }
        .logo {
            margin-bottom: 20px;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .subtitle {
            font-size: 20px;
            color: #555;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="{{ asset('images/CampusLogo.png') }}" alt="EUCTO Logo" style="width: 20%;">
    </div>
    <div class="title">EUCTO CAMPUS</div>
    <div class="subtitle">School Fee Payment</div>
    <h2>Redirecting to Razorpay...</h2>

    <script>
        var options = {
            "key": "{{ $checkoutData['key'] }}",
            "amount": "{{ $checkoutData['amount'] }}",
            "currency": "{{ $checkoutData['currency'] }}",
            "name": "{{ $checkoutData['name'] }}",
            "description": "{{ $checkoutData['description'] }}",
            "order_id": "{{ $checkoutData['order_id'] }}",
            "handler": function (response){
                window.location.href = "{{ $checkoutData['callback_url'] }}?payment_id=" + response.razorpay_payment_id + "&order_id=" + response.razorpay_order_id + "&signature=" + response.razorpay_signature;
            },
            "prefill": {
                "name": "{{ $checkoutData['prefill']['name'] }}",
                "email": "{{ $checkoutData['prefill']['email'] }}",
                "contact": "{{ $checkoutData['prefill']['contact'] }}"
            },
            "notes": {
                "transaction_id": "{{ $checkoutData['notes']['transaction_id'] }}"
            },
            "theme": {
                "color": "#F37254"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>
</body>
</html>
