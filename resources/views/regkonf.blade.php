
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заявление</title>
    <link rel="stylesheet" href='{{asset("/css/font.css")}}'>
    <link rel="stylesheet" href='{{asset("/css/regkonf.css")}}'>
</head>
<body class="antialiased ">
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0 bg-ex-fixed">
    <div class="container">
        <header class="flex between">
            <a href="https://www.utmn.ru/" class="otstup-l"><img src="https://www.utmn.ru/upload/medialibrary/47f/logo_utmn_mini2_rus.png" alt="ТюмГУ" width="120"/></a>
            <div class="hidden flex-images">
                @if(Auth::check())
                <a href="{{ route('lk') }}" class="btn-secondary otstup-r">Личный кабинет</a>
                <!-- HTML-код -->
                <a href="#" class="btn-secondary" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Выход</a>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
                    @csrf
                </form>
                @else
                <a href="{{ route('login') }}" class="btn-secondary otstup-r">Вход</a>
                <a href="{{ route('register') }}" class="btn-secondary otstup-r">Регистрация</a>
                @endif
            </div>
        </header>
        
        
      


        <div class="square line">
            <div id="stick2" class="stick2">
                <div style="vertical-align: none;" class="margin-none">
                    <h2 style="text-align: center">{{ $data->name }}</h2>
                    Страна: {{ $data->country }}<br>
                    Город: {{ $data->city }}<br>
                    Дата начала: {{ $data->date_start }}<br>
                    Дата окончания: {{ $data->date_end }}<br>
                    Дедлайн: {{ $data->deadline }}<br>
                    Описание: {{ $data->description }}<br>
                    <form method="POST" action="{{ route('konf.reg', ['id' => $data->id]) }}">
                        @csrf
                        <label for="name_project">Название доклада:</label>
                        <input type="text" name="name_project" required><br>

                        <button type="submit">Записаться</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="under" ></div>
    </div>
</div>
</body>
</html>





