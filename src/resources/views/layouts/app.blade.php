<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
</head>
<body>

     @if(Request::is('login') || Request::is('register'))
        @include('layouts.auth-header') <!-- ログイン/会員登録用ヘッダー -->
    @else
        @include('layouts.header') 
    @endif

    <main>
        @yield('content')
    </main>

</body>
</html>