@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@section('title', '商品一覧')

@section('content')
<div class="item">
    <div class="item-content">

        <div class="tab">
            <a href="{{ route('items.index', ['tab' => 'recommend', 'q' => request('q')]) }}"
               class="tab-item-recommend {{ $tab === 'recommend' ? 'active' : '' }}">
                おすすめ
            </a>
            @auth
                <a href="{{ route('items.index', ['tab' => 'mylist', 'q' => request('q')]) }}"
                   class="tab-item {{ $tab === 'mylist' ? 'active' : '' }}">
                    マイリスト
                </a>
            @endauth
        </div>

        <div class="item-grid">
            @foreach($items as $item)
                <div class="item-card">

                    @if($item->buyer_id)
                        <div class="sold-label">SOLD</div>
                    @endif
                    <a class="item-link" href="{{ url('/item/'.$item->id) }}">
                        <div class="item-image">
                            @if($item->image && Str::startsWith($item->image, 'http'))
                                <img src="{{ $item->image }}" alt="{{ $item->name }}">
                            @elseif($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Image">
                            @endif
                        </div>

                        <p>{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
