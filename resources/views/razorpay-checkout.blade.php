<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
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
