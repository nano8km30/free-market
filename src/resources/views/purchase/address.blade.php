@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@section('title', '配送先変更')

@section('content')
<div class="address">
    <div class="address-content">
        <h1 class="address-title">住所の変更</h1>

        <form method="POST" action="{{ route('purchase.address.update', $item->id) }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="postal_code">郵便番号</label>
                <input
                    type="text"
                    class="form-input"
                    id="postal_code"
                    name="postal_code"
                    value="{{ old('postal_code', $address->postal_code ?? '') }}"
                >
                @error('postal_code')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="address">住所</label>
                <input
                    type="text"
                    class="form-input"
                    id="address"
                    name="address"
                    value="{{ old('address', $address->address ?? '') }}"
                >
                @error('address')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="building">建物名</label>
                <input
                    type="text"
                    class="form-input"
                    id="building"
                    name="building"
                    value="{{ old('building', $address->building ?? '') }}"
                >
            </div>

            <button type="submit" class="form-button">
                更新する
            </button>
        </form>
    </div>
</div>
@endsection
