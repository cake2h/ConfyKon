
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/normalize.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">

    @yield('some_styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap">

</head>

<body>
<header>
        <img src="{{ asset('img/logo.png') }}" alt="logo" style="max-width: 65px;">
    <div class="controls">
        <a href="{{ route('conf.index') }}" class="control">
            <i class="material-icons {{ request()->routeIs('conf.index') ? ' active_icon' : '' }}">home</i>
            <p>Главная</p>
        </a>

        @auth
                <a href="{{ route('admin.index') }}" class="control">
                    <i class="material-icons {{ request()->routeIs('admin.index') ? ' active_icon' : '' }}">admin_panel_settings</i>
                    <p>Панель организатора</p>
                </a>

                <a class="control" href="{{ route('antiplagiat.upload.form') }}">
                    <i class="material-icons {{ request()->routeIs('antiplagiat.upload.form') ? ' active_icon' : '' }}">menu_book</i>
                    <p>Антиплагиат</p>
                </a>

                <div class="control">
                    <form method="GET" action="{{ route('page.emails') }}">
                        @csrf
                        <button class="controls__button" type="submit">
                            <i class="material-icons {{ request()->routeIs('page.emails') ? ' active_icon' : '' }}">forward_to_inbox</i>
                            Рассылка
                        </button>
                    </form>
                </div>
                <a href="{{ route('admin.konfs.create') }}" class="control">
                    <i class="material-icons {{ request()->routeIs('admin.konfs.create') ? ' active_icon' : '' }}">add</i>
                    <p>Создать конференецию</p>
                </a>

                @if (Auth::user()->isModerator())
                    <a href="{{ route('moderator.index') }}" class="control">
                        <i class="material-icons {{ request()->routeIs('moderator.index') ? ' active_icon' : '' }}">admin_panel_settings</i>
                        <p>Панель модератора</p>
                    </a>
                @endif

                <a class="control" href="{{ route('dashboard.index') }}">
                    <i class="material-icons {{ request()->routeIs('dashboard.index') ? ' active_icon' : '' }}">person</i>
                    <p>Профиль</p>
                </a>

            @endif
            <div class="control">
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button class="controls__button" type="submit">
                        <i class="material-icons">logout</i>
                        <p>Выйти<p>
                    </button>
                </form>
            </div>

    </div>
</header>

@yield('page_title')

<main class="content">
    @yield('content')
</main>

@yield('scripts')
<script>
    function openModal() {
        var modal = document.getElementById('exportModal');
        modal.style.display = 'flex';
    }

    function closeModal() {
        var modal = document.getElementById('exportModal');
        modal.style.display = 'none';
    }
</script>
</body>
</html>
