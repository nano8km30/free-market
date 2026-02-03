@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@section('title', 'プロフィール設定')

@section('content')
<div class="profile">
    <div class="profile-content">
        <h1 class="profile__title">プロフィール設定</h1>

        <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="profile-image">
                <div class="profile-image__preview"></div>

                <label class="profile-image__button">
                    画像を選択する
                    <input type="file" name="avatar" hidden>
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">ユーザー名</label>
                <input
                    type="text"
                    name="name"
                    class="form-input"
                    value="{{ old('name', $user->name) }}"
                >
            </div>

            <div class="form-group">
                <label class="form-label">郵便番号</label>
                <input
                    type="text"
                    name="postcode"
                    class="form-input"
                    value="{{ old('postcode', $address?->postal_code) }}"
                >
            </div>

            <div class="form-group">
                <label class="form-label">住所</label>
                <input
                    type="text"
                    name="address"
                    class="form-input"
                    value="{{ old('address', $address?->address) }}"
                >
            </div>

            <div class="form-group">
                <label class="form-label">建物名</label>
                <input
                    type="text"
                    name="building"
                    class="form-input"
                    value="{{ old('building', $address?->building) }}"
                >
            </div>

            <button type="submit" class="form-button">
                更新する
            </button>
        </form>
    </div>
</div>
@endsection