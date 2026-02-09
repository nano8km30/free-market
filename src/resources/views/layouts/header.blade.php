<header class="header">
	<div class="header__inner">
		<a href="/">
		<img src="{{ asset('images/logo.png') }}" alt="logo">
		</a>
	</div>

	<div class="header__center">
		<form action="{{ route('items.index') }}" method="GET" class="header-search">
            <input
                class="header__input" type="text"
                name="q" value="{{ request('q') }}"
                placeholder="なにをお探しですか？">
        </form>
	</div>

	<div class="header__content">
		@auth
		<form method="POST" action="{{ route('logout') }}">
			@csrf
			<button type="submit" class="logout-button">ログアウト</button>
		</form>
			<a href="/mypage" class="mypage-link">マイページ</a>
			<a href="/sell" class="sell-btn">出品</a>
		@endauth
	</div>
</header>
