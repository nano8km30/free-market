@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
@section('title', 'マイページ')

@section('content')
<div class="mypage">
    <div class="mypage-content">

        <div class="mypage-profile">
            <div class="profile-left">
                <div class="profile-icon">
                    @if(auth()->user()->icon_image)
                        <img
                            src="{{ asset('storage/' . auth()->user()->icon_image) }}"
                            alt="プロフィール画像"
                            class="profile-icon-img">
                    @else
                        <div class="icon-circle"></div>
                    @endif
                </div>
                <h2 class="profile-name">{{ auth()->user()->name }}</h2>
            </div>

            <div class="profile-right">
                <a href="{{ route('mypage.profile') }}" class="profile-edit-btn">
                    プロフィールを編集
                </a>
            </div>
        </div>

        <div class="mypage-tabs">
            <a href="{{ route('mypage', ['tab' => 'sell']) }}"
            class="tab-sell {{ request('tab', 'sell') === 'sell' ? 'active' : '' }}">
                出品した商品
            </a>

            <a href="{{ route('mypage', ['tab' => 'buy']) }}"
            class="tab-buy {{ request('tab') === 'buy' ? 'active' : '' }}">
                購入した商品
            </a>
        </div>

        <div class="mypage-items">
            @forelse($items as $item)
                <div class="item-card">
                    <div class="item-image">
                        @if($item->image && Str::startsWith($item->image, 'http'))
                            <img src="{{ $item->image }}" alt="{{ $item->name }}">
                        @elseif($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" alt="No Image">
                        @endif
                    </div>
                    <p class="item-name">{{ $item->name }}</p>
                </div>
            @empty
                <p class="no-item">商品がありません</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
