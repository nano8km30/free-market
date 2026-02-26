@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@section('title', '商品詳細')

@section('content')
<div class="show">
    <div class="show-content">

        <div class="item-detail__left">
            @if($item->image && Str::startsWith($item->image, 'http'))
                <img src="{{ $item->image }}" alt="{{ $item->name }}">
            @elseif($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
            @else
                <img src="{{ asset('images/no-image.png') }}" alt="No Image">
            @endif
        </div>

        <div class="item-detail__right">
            <h1 class="item-title">{{ $item->name }}</h1>
            <p class="item-brand">{{ $item->brand }}</p>
            <p class="item-price">¥{{ $item->price }}（税込）</p>

            <div class="item-actions">
                <span class="item-actions">
                    <form action="{{ route('items.toggle-like', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="like-icon">
                            <img src="{{ $item->likes->contains('user_id', auth()->id()) ? asset('images/heart_pink.png') : asset('images/heart_default.png') }}" alt="like">
                        </button>
                    </form>
                    <span class="like-count-{{ $item->id }}">{{ $item->likes->count() }}</span>
                </span>

                <span>
                    <img class="comment-image" src="{{ asset('images/comment.png') }}" alt="コメント">
                    {{ $item->comments->count() }}
                </span>
            </div>

            @if($item->is_sold)
                <button class="sold-btn" disabled>Sold</button>
            @else
                <form action="{{ route('purchase.show', $item->id) }}" method="GET">
                    <button type="submit" class="buy-btn">購入手続きへ</button> 
                </form>
            @endif

            <section class="item-description">
                <h2 class="detail-title">商品説明</h2>
                <p>{{ $item->description }}</p>
            </section>

            <section class="item-info">
                <h2 >商品の情報</h2>

                <p class="info-label">商品の状態：{{ $item->condition }}</p>

                <div class="category-row">
                    <p class="categories">カテゴリー：</p>
                        @foreach ($item->categories as $category)
                            <span class="category-tag">{{ $category->name }}</span>
                        @endforeach
                </div>

                <h2>コメント（{{ $item->comments->count() }}）</h2>

                @foreach($item->comments as $comment)
                    <div class="comment">
                        <div class="comment-header">
                            <div class="user-icon"></div> 
                            <strong class="user-name">{{ $comment->user->name }}</strong>
                        </div>
                        <p class="comment-detail">{{ $comment->body }}</p>
                    </div>
                @endforeach

                @if($item->comments->isEmpty())
                    <p>コメントはまだありません。</p>
                @endif

                <h2 class="item-comment">商品へのコメント</h2>

                @auth
                <form class="form-comment" method="POST" action="{{ route('comments.store', $item) }}">
                    @csrf
                    <textarea name="body" ></textarea>
                    <button type="submit">コメントを送信する</button>
                </form>
                @endauth
            </section>
        </div>
    </div>
</div>
@endsection