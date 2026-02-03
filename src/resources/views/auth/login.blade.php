@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@section('title', 'ログイン')

@section('content')
<div class="login">
    <div class="login-content">
        <h1 class="login__title">ログイン</h1>

        <form method="POST" action="{{ route('login') }}">
        @csrf

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input class="form-input"
                type="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input class="form-input"
                type="password" name="password">
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
        <button class="form-button" type="submit">ログイン</button>
        </form>

        <div class="auth-link">
            <a class="register-link" href="{{ route('register.show') }}">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection
