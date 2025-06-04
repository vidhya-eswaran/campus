<form action="{{ route('donation.confirm') }}" method="POST" onsubmit="clearUncheckedFields()">
    @csrf
    <input type="hidden" name="donation_id" value="{{ $donation->id }}">
    <input type="hidden" name="donation_heading" value="{{ $donation->heading }}">

    <label>Name:</label>
    <input type="text" name="name" class="form-control" required>

    <label>Email:</label>
    <input type="email" name="email" class="form-control" required>

    <label>Phone Number:</label>
    <input type="text" name="phone_number" class="form-control" required>

    <label>PAN Card:</label>
    <input type="text" name="pan_card" class="form-control" required>

    <label>Address:</label>
    <input type="text" name="address_1" class="form-control" required>

    <label>City:</label>
    <input type="text" name="city_1" class="form-control" required>

    <label>State:</label>
    <input type="text" name="state_1" class="form-control" required>

    <label>Pincode:</label>
    <input type="text" name="pincode_1" class="form-control" required>

    <!-- Checkbox for Additional Address -->
    <div>
        <input type="checkbox" id="additionalAddressCheckbox">
        <label for="additionalAddressCheckbox"> Additional Address</label>
    </div>

    <!-- Additional Address Fields (Initially Hidden) -->
    <div id="additionalAddressFields" style="display:none;">
        <label>Address 2:</label>
        <input type="text" name="address_2" class="form-control">

        <label>City:</label>
        <input type="text" name="city_2" class="form-control">

        <label>State:</label>
        <input type="text" name="state_2" class="form-control">

        <label>Country:</label>
        <input type="text" name="country_2" class="form-control">

        <label>Pincode:</label>
        <input type="text" name="pincode_2" class="form-control">
    </div>

    <h4>Select Donation Amount:</h4>
    <div>
        <label>
            <input type="radio" name="amount" value="{{ $donation->btn_amt_1 }}" required> ₹{{ $donation->btn_amt_1 }}
        </label><br>
        <label>
            <input type="radio" name="amount" value="{{ $donation->btn_amt_2 }}"> ₹{{ $donation->btn_amt_2 }}
        </label><br>
        <label>
            <input type="radio" name="amount" value="{{ $donation->btn_amt_3 }}"> ₹{{ $donation->btn_amt_3 }}
        </label><br>
        <label>
            <input type="radio" name="amount" value="other" id="otherAmountCheckbox"> Other
        </label>
        <input type="text" name="other_amount" id="otherAmountInput" class="form-control" style="display:none;" placeholder="Enter custom amount">
    </div>

    <button type="submit" class="btn btn-success mt-3">Proceed to Confirm</button>
</form>

<!-- JavaScript -->
<script>
    document.getElementById("additionalAddressCheckbox").addEventListener("change", function() {
        var additionalFields = document.getElementById("additionalAddressFields");
        var inputs = additionalFields.querySelectorAll("input");

        if (this.checked) {
            additionalFields.style.display = "block";
            inputs.forEach(input => input.setAttribute("required", "required"));
        } else {
            additionalFields.style.display = "none";
            inputs.forEach(input => input.removeAttribute("required"));
        }
    });

    document.getElementById("otherAmountCheckbox").addEventListener("change", function() {
        var otherAmountInput = document.getElementById("otherAmountInput");
        if (this.checked) {
            otherAmountInput.style.display = "block";
            otherAmountInput.setAttribute("required", "required");
        } else {
            otherAmountInput.style.display = "none";
            otherAmountInput.removeAttribute("required");
        }
    });

    function clearUncheckedFields() {
        var additionalCheckbox = document.getElementById("additionalAddressCheckbox");
        if (!additionalCheckbox.checked) {
            document.querySelectorAll("#additionalAddressFields input").forEach(input => input.value = "");
        }

        var otherAmountCheckbox = document.getElementById("otherAmountCheckbox");
        if (!otherAmountCheckbox.checked) {
            document.getElementById("otherAmountInput").value = "";
        }
    }
</script>
