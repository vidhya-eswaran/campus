@extends('layouts.app')

@section('content')
<h2>Thank You for Your Donation!</h2>
<p>Your donation has been successfully received.</p>

<h3>Donation Details:</h3>
<p><strong>Amount:</strong> ₹{{ $donation->amount }}</p>
<p><strong>Name:</strong> {{ $donation->name }}</p>
<p><strong>Email:</strong> {{ $donation->email }}</p>
<p><strong>PAN Card:</strong> {{ $donation->pan_card }}</p>
<p><strong>Phone:</strong> {{ $donation->phone }}</p>

<a href="/donate">Make Another Donation</a>
@endsection
