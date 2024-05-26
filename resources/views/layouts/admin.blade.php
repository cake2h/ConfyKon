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
                    <i class="material-icons {{ request()->routeIs('admin.index') ? ' active_icon' : '' }}">admin_panel_settings</i>
                    <p>Админ-панель</p>
                </a>

                <a href="#" class="control" onclick="openModal()">
                    <i class="material-icons">download</i>
                    <p>Выгрузка пользователей</p>
                </a>

                <div class="control">
                    <form method="POST" action="{{ route('send.emails') }}">
                        @csrf
                        <button class="controls__button" type="submit">
                            <i class="material-icons">forward_to_inbox</i>
                            Рассылка
                        </button>
                    </form>
                </div>
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
        @endauth
    </div>
</header>
<main class="content">
    @yield('content')
</main>

@if (Auth::user()->isAdmin())
    <div class="modal" id="exportModal">
        <div class="modal__container">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" action="{{ route('export_all_users') }}">
                @csrf
                <h1>Выгрузка пользователей</h1>
                <div class="form-group">
                    <label for="section_id">Секция:</label>
                    <select id="section_id" class="authInput" name="section_id">
                        <option value="" disabled selected hidden>Выберите секцию</option>
                        @foreach($conference->sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="button" type="submit">Выгрузить секцию</button>
            </form>
            <form method="POST" action="{{ route('export_conference_results') }}">
                @csrf
                <button class="button" type="submit">Выгрузить итоги конференции</button>
            </form>
        </div>
    </div>
@endif
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
