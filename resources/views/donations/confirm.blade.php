@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">Confirm Your Donation</h2>
    <table class="table">
        <tr><th>Donation Campaign</th><td>{{ $data['donation_heading'] }}</td></tr>
        <tr><th>Name</th><td>{{ $data['name'] }}</td></tr>
        <tr><th>Email</th><td>{{ $data['email'] }}</td></tr>
        <tr><th>Phone</th><td>{{ $data['phone_number'] }}</td></tr>
        <tr><th>PAN Card</th><td>{{ $data['pan_card'] }}</td></tr>
        <tr><th>Address</th><td>{{ $data['address_1'] }}, {{ $data['city_1'] }}, {{ $data['state_1'] }} - {{ $data['pincode_1'] }}</td></tr>
        @if(!empty($data['address_2']))
        <tr><th>Additional Address</th><td>{{ $data['address_2'] }}, {{ $data['city_2'] }}, {{ $data['state_2'] }} - {{ $data['pincode_2'] }}</td></tr>
        @endif
        <tr><th>Amount</th><td>₹{{ $data['final_amount'] }}</td></tr>
        </table>

    <form action="{{ route('donation.payment') }}" method="POST">
      
        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button type="submit" class="btn btn-success">Proceed to Payment</button>
    </form>
</div>
<script>
      function submitForm() {
    const formData = new FormData(document.getElementById('donationForm'));

    fetch("{{ route('submitDonation') }}", {
      method: "POST",
      body: formData,
      headers: {
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      }
    })
    .then(response => response.json())
    .then(data => {
      alert(data.message);
      // Redirect to Payment Gateway URL or another page based on response
      window.location.href = data.payment_url;
    })
    .catch(error => console.error("Error:", error));
  }

</script>
@endsection
