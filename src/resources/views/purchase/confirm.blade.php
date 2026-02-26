@extends('layouts.app')

@section('content')
    <div>
        <p id="payment-method">
            {{ $purchase->payment_method }}
        </p>
    </div>
@endsection