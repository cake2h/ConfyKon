<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href='{{ asset('/css/main.css') }}'>
    <link rel="stylesheet" href='{{ asset('/css/normalize.css') }}'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    @yield('some_styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap">
</head>

<body>
    <header>
        <a class="logo" href="https://www.utmn.ru">
            <img src="{{ asset('img/logo2.png') }}" alt="logo">
        </a>
        <div class="controls">
            <a href="{{ route('conf.index') }}" class="control">
                <i class="material-icons {{ request()->routeIs('conf.index') ? ' active_icon' : '' }}">home</i>
                <p>Главная</p>
            </a>

            @auth
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="control">
                        <i
                            class="material-icons {{ request()->routeIs('admin.index') ? ' active_icon' : '' }}">admin_panel_settings</i>
                        <p>Админ-панель</p>
                    </a>

                    <a href="{{ route('conf.add') }}" class="control">
                        <i class="material-icons {{ request()->routeIs('conf.add') ? ' active_icon' : '' }}">add_box</i>
                        <p>Добавить конференцию</p>
                    </a>

                    <a class="control" href="{{ route('export_all_users') }}">
                        <i class="material-icons">download</i>
                        <p>Выгрузка пользователей</p>
                    </a>

                    <a class="control" href="{{ route('page.emails') }}">
                        <i class="material-icons">forward_to_inbox</i>
                        <p>Рассылка</p>
                    </a>
                @endif
                <div class="control">
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button class="controls__button" type="submit">
                            <i class="material-icons">logout</i>
                            <p>Выйти
                            <p>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </header>
    <main class="content">
        @yield('content')
    </main>
    @yield('scripts')
</body>

</html>
