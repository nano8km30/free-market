@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@section('title', '商品出品')

@section('content')
<div class="sell">
    <div class="sell-content">
        <h1 class="sell-title">商品の出品</h1>

        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
            @csrf


            <div class="form-group">
                <label class="form-label" for="image">商品画像</label>

                <label class="image-upload-box">
                    <span class="upload-button">画像を選択する</span>
                    <input class="file-input" type="file" name="image" id="image">
                </label>
            </div>


            <h2 class="detail-title">商品の詳細</h2>

            <div class="form-group">
                <label class="form-label">カテゴリー</label>

                <div class="select-box">
                    <select name="category_ids[]" class="form-select" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="condition">商品の状態</label>

                <div class="select-box">
                    <select class="form-select" name="condition" id="condition">
                        <option value="">選択してください</option>
                        <option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>良好</option>
                        <option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="状態が悪い" {{ old('condition') == '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                    </select>
                </div>
            </div>

            <h2 class="detail-title">商品名と説明</h2>

            <div class="form-group">
                <label class="form-label" for="name">商品名</label>
                <input class="form-input" type="text" name="name" id="name" value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="brand">ブランド名</label>
                <input class="form-input" type="text" name="brand" id="brand" value="{{ old('brand') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">商品の説明</label>
                <textarea class="form-item-detail" name="description" id="description">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="price">販売価格</label>
                <div class="price">
                    <span class="price-symbol">¥</span>
                    <input class="price-input" type="number" name="price" id="price" value="{{ old('price') }}">
                </div>
            </div>

            <button type="submit" class="sell-button">出品する</button>
        </form>
    </div>
</div>
@endsection
