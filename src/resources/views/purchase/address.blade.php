@extends('layouts.app')
@section('title', '配送先変更')

@section('content')
<div class="address-edit">
    <h1>配送先を選択</h1>

    <form method="POST" action="{{ route('purchase.address.update', $item->id) }}">
        @csrf

        <select name="address_id">
            @foreach($addresses as $a)
                <option value="{{ $a->id }}"
                    {{ (old('address_id', session('purchase_address_' . $item->id)) == $a->id) ? 'selected' : '' }}>
                    〒{{ $a->postal_code }} {{ $a->address }} {{ $a->building ?? '' }}
                </option>
            @endforeach
        </select>

        @error('address_id')
            <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit">保存</button>
    </form>
</div>
@endsection
