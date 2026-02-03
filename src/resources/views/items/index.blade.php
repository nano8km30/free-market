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

                    @if($item->is_sold)
                        <span class="sold-message">Sold</span>
                    @endif

                    <a href="{{ url('/item/'.$item->id) }}">
                        <div class="item-image">
                            <img src="{{ $item->image }}" alt="{{ $item->name }}">
                        </div>

                        <p class="item-name">{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
