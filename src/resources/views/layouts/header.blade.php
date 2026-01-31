<header class="header">
	<div class="header__inner">
		<a href="/">
		<img src="{{ asset('images/logo.png') }}" alt="logo">
		</a>
	</div>

	<div class="header__center">
		<input class="header__input" type="text" placeholder="なにをお探しですか？">
	</div>

	<div class="header__content">
		@auth
		<form method="POST" action="{{ route('logout') }}">
			@csrf
			<button type="submit" class="logout-button">ログアウト</button>
		</form>
		<a href="{{ route('mypage') }}" class="mypage-link">マイページ</a>
		<a href="" class="sell-btn">出品</a>
		@endauth
	</div>
</header>
