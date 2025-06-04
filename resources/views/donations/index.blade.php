@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Donation Campaigns</h2>
    <div class="row">
        @foreach($donations as $donation)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('images/' . $donation->image) }}" class="card-img-top" alt="{{ $donation->heading }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $donation->heading }}</h5>
                        <p class="card-text">{{ $donation->short_description }}</p>
                        <a href="{{ route('donation.details', $donation->id) }}" class="btn btn-primary">Donate Now</a>
                        </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
