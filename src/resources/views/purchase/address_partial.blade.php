<div class="address">
    <div class="address-header">
        <p class="item-label">配送先</p>
        <a class="address-change" href="{{ route('purchase.address.edit', $item->id) }}">
            変更する
        </a>
    </div>

    @error('address')
        <p class="error">{{ $message }}</p>
    @enderror

    @if($address)
        <div class="address-body">
            <p>〒 {{ $address->postal_code }}</p>
            <p>{{ $address->address }}</p>
            @if(!empty($address->building))
                <p>{{ $address->building }}</p>
            @endif
        </div>

        <input type="hidden" name="address_id" value="{{ $addressId }}">
    @else
        <p>配送先が登録されていません</p>
    @endif

    @php
        $addresses = auth()->user()->addresses ?? collect([]);
    @endphp

</div>
