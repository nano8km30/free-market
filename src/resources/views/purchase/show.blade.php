@php
    $addressId = $addressId ?? null;
@endphp
@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@section('title', '商品購入画面')

@section('content')
<div class="purchase">
    <div class="purchase-content">

        <div class="purchase-left">
            <div class="item-detail">
                @if($item->image && Str::startsWith($item->image, 'http'))
                    <img src="{{ $item->image }}" alt="{{ $item->name }}">
                @elseif($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                @else
                    <img src="{{ asset('images/no-image.png') }}" alt="No Image">
                @endif

                <div class="item-info">
                    <p class="item-name">{{ $item->name }}</p>
                    <p class="item-price">¥ {{ number_format($item->price) }}</p>
                </div>
            </div>

            <form class="from-payment" id="purchase-form" method="POST" action="{{ route('purchase.store', $item->id) }}">
                @csrf

                <div class="item-payment">
                    <label class="item-label">支払い方法</label>
                    <select class="payment-select" name="payment_method" id="payment_method">
                        <option value="">選択してください</option>
                        <option value="convenience"
                            {{ session('payment_method') === 'convenience' ? 'selected' : '' }}>
                            コンビニ払い
                        </option>
                        <option value="card"
                            {{ session('payment_method') === 'card' ? 'selected' : '' }}>
                            カード払い
                        </option>
                    </select>

                    @error('payment_method')
                        <p class="error">{{ $message }}</p>
                    @enderror
                    
                    @if($address)
                        <input type="hidden" name="address_id" value="{{ $addressId }}">
                    @endif

                </div>

                @php
                    $addressId = $addressId ?? session('purchase_address_' . $item->id);
                @endphp

                @include('purchase.address_partial', [
                    'address' => $address,
                    'addressId' => $addressId
                ])

            </form>
        </div>
        <div class="purchase-right">
            <div class="summary">
                <p class="price-confirmation">
                    <span class="label">商品代金</span>
                    <span class="price">¥ {{ number_format($item->price) }}</span>
                </p>
                <p class="payment-confirmation">支払い方法
                    <span id="selected-payment">
                        @if(session('payment_method') === 'convenience')
                            コンビニ支払い
                        @elseif(session('payment_method') === 'card')
                            カード支払い
                        @else
                            未選択
                        @endif
                    </span>
                </p>
            </div>

            <button class="buy-btn" type="submit" form="purchase-form">購入する</button>
        </div>
    </div> 
</div>

<script>
    const paymentSelect = document.getElementById('payment_method');
    const paymentText = document.getElementById('selected-payment');

    paymentSelect.addEventListener('change', function () {
        if (this.value === 'convenience') {
            paymentText.textContent = 'コンビニ支払い';
        } else if (this.value === 'card') {
            paymentText.textContent = 'カード支払い';
        } else {
            paymentText.textContent = '未選択';
        }
    });
</script>
@endsection
