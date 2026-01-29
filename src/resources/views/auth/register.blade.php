@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/register.css') }}" />
@section('title', '会員登録')

@section('content')
<div class="register">
    <div class="register-content">
        <h1 class="register__title">会員登録</h1>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">ユーザー名</label>
                <input class="form-input"
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input class="form-input"
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input class="form-input" 
                    type="password"
                    id="password"
                    name="password">
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">パスワード（確認）</label>
                <input class="form-input"
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation">
                @error('password_confirmation')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <button class="form-button" type="submit">登録する</button>

            <div class="auth-link">
                <a class="login-link" href="/login">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection
