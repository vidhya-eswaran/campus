@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Multi-Step Donation Form</h2>

    <div class="card">
        <div class="card-body">
            <form id="donationForm">
                <!-- Step Progress -->
                <div class="progress mb-4">
                    <div class="progress-bar" id="progressBar" style="width: 33%;"></div>
                </div>

                <!-- Step 1: Select Amount -->
                <div class="step" id="step1">
                    <h4>Step 1: Choose Donation Amount</h4>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="amount" value="10">
                        <label class="form-check-label">₹10</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="amount" value="30">
                        <label class="form-check-label">₹30</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="amount" value="40">
                        <label class="form-check-label">₹40</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="amount" value="other" id="otherAmountRadio">
                        <label class="form-check-label">Other</label>
                    </div>
                    <input type="number" class="form-control mt-2" id="customAmount" placeholder="Enter amount" style="display:none;">
                    <br>
                    <button type="button" class="btn btn-primary next">Next</button>
                </div>

                <!-- Step 2: Donor Details -->
                <div class="step" id="step2" style="display:none;">
                    <h4>Step 2: Your Details</h4>
                    <input type="text" class="form-control mb-2" id="name" placeholder="Full Name" required>
                    <input type="email" class="form-control mb-2" id="email" placeholder="Email" required>
                    <input type="text" class="form-control mb-2" id="pan_card" placeholder="PAN Card (Optional)">
                    <input type="text" class="form-control mb-2" id="phone" placeholder="Phone Number" required>
                    <br>
                    <button type="button" class="btn btn-secondary back">Back</button>
                    <button type="button" class="btn btn-primary next">Next</button>
                </div>

                <!-- Step 3: Preview -->
                <div class="step" id="step3" style="display:none;">
                    <h4>Step 3: Preview Your Details</h4>
                    <p><strong>Amount:</strong> <span id="previewAmount"></span></p>
                    <p><strong>Name:</strong> <span id="previewName"></span></p>
                    <p><strong>Email:</strong> <span id="previewEmail"></span></p>
                    <p><strong>PAN:</strong> <span id="previewPAN"></span></p>
                    <p><strong>Phone:</strong> <span id="previewPhone"></span></p>
                    <br>
                    <button type="button" class="btn btn-secondary back">Back</button>
                    <button type="submit" class="btn btn-success">Donate Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap & jQuery -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->

<script>
$(document).ready(function() {
    let currentStep = 1;
    let totalSteps = $(".step").length;

    function updateProgressBar() {
        let progress = (currentStep / totalSteps) * 100;
        $("#progressBar").css("width", progress + "%");
    }

    $(".next").click(function() {
        let isValid = true;

        if (currentStep === 1) {
            let selectedAmount = $("input[name='amount']:checked").val();
            let customAmount = $("#customAmount").val();

            if (!selectedAmount) {
                alert("Please select an amount.");
                isValid = false;
            } else if (selectedAmount === "other" && (!customAmount || customAmount <= 0)) {
                alert("Please enter a valid custom amount.");
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: '/donate/step1',
                    type: 'POST',
                    data: {
                        amount: selectedAmount === "other" ? customAmount : selectedAmount,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log("Step 1 Data Saved:", response);
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr.responseText);
                    }
                });
            }
        }

        if (currentStep === 2) {
            let name = $("#name").val().trim();
            let email = $("#email").val().trim();
            let pan_card = $("#pan_card").val().trim();
            let phone = $("#phone").val().trim();

            if (!name || !email || !phone) {
                alert("Please fill in all required fields.");
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: '/donate/step2',
                    type: 'POST',
                    data: {
                        name: name,
                        email: email,
                        pan_card: pan_card,
                        phone: phone,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log("Step 2 Data Saved:", response);
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr.responseText);
                    }
                });
            }
        }

        if (isValid) {
            $("#step" + currentStep).hide();
            currentStep++;
            $("#step" + currentStep).show();
            updateProgressBar();
        }
    });

    $(".back").click(function() {
        $("#step" + currentStep).hide();
        currentStep--;
        $("#step" + currentStep).show();
        updateProgressBar();
    });

    $("#donationForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '/donate/submit',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                window.location.href = "/thank-you";
            },
            error: function(xhr) {
                console.log("Error:", xhr.responseText);
            }
        });
    });

    $("input[name='amount']").change(function() {
        if ($(this).val() === "other") {
            $("#customAmount").show().focus();
        } else {
            $("#customAmount").hide();
        }
    });
});

</script>

@endsection
