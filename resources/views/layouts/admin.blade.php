<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href='{{asset("/css/main.css")}}'>
    <link rel="stylesheet" href='{{asset("/css/normalize.css")}}'>
    @yield('some_styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap">
</head>

<body>
  <header class="header-buttons">
    <a href="https://www.utmn.ru/">
      <svg class="logo" viewBox="0 0 185.9 83.8">
        <path class="st0" d="M78.3,41.8h-8v22.6h-5.7V41.8h-8v-5.1h21.7V41.8z M139.2,50.2l1,14.1h5.2l-1.8-23.9h-0.2h-5l-7.9,18.1   l-7.7-18.1h-5h-0.2l-1.8,23.9h5.2l1-13.9l5.9,13.9h5L139.2,50.2z M100.8,40.2c-6,0-11,4.3-12.1,9.9h-2.6v-9.7h-5.7v23.9h5.7v-9.1   h2.7c1.2,5.5,6.1,9.6,12,9.6c6.8,0,12.3-5.5,12.3-12.3S107.6,40.2,100.8,40.2 M100.8,45.4c3.9,0,7.1,3.2,7.1,7.1s-3.2,7.1-7.1,7.1   s-7.1-3.2-7.1-7.1S96.9,45.4,100.8,45.4 M165.6,36.7l7.7,18.5l-0.7,1.9c-0.6,1.5-1,3.3-4.4,3.3v5.1c5.9,0,7.6-3.1,9.7-8.4l8-20.4   h-5.8L176,49.4l-4.5-12.7H165.6z M155.1,41.8v22.6h-5.7V36.8l0,0h15.2v5.1L155.1,41.8L155.1,41.8z"></path>
        <polygon class="st0" points="52.8,7.6 39.6,0 0,22.8 0,38.1 13.2,45.7 13.2,30.5  "></polygon>
        <polygon class="st0" points="26.4,83.8 26.4,38.1 13.2,45.7 13.2,76.2  "></polygon>
      </svg>
    </a>
    <div class="menu">
        @auth
            @if (Auth::user()->isAdmin())
                <a href="{{ route('conf.add') }}" class="menu__button">Добавить конференцию</a>
                <a href="{{ route('conf.add') }}" class="menu__button">Рассылка</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="menu__button" type="submit">Выйти из аккаунта</button>
            </form>
        @endauth
    </div>
  </header>

  <div class="content">
    @yield('content')
  </div>
  <footer>
    <p style="text-align: center">&copy; 2024 Сервис для организации конференций</p>
  </footer>
</body>
</html>
