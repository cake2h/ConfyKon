<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панель администратора</title>
    <link rel="stylesheet" href='{{asset("css/admin.blade.css")}}'>
    <link rel="stylesheet" href='{{asset("css/font.css")}}'>
    <link rel="stylesheet" href='{{asset("css/dashboard.css")}}'>
</head>

<body class="antialiased ">
    <div class="relative flex items-top justify-center min-h-screen bg-ex-fixed">
        <div class="container">
            <header class="flex-images between">
                <a href="https://www.utmn.ru/" class="otstup-l"><img src="https://www.utmn.ru/upload/medialibrary/47f/logo_utmn_mini2_rus.png" alt="ТюмГУ" width="120"/></a>
                <div class="hidden flex-images">

                    <a href="{{ route('conf.add') }}" class="btn-secondary otstup-r">
                        Добавить мероприятие
                    </a>

                    <a href="{{ route('conf.add') }}" class="btn-secondary otstup-r">
                        Рассылка оргаанизациям
                    </a>

                    <a href="{{ route('conf.add') }}" class="btn-secondary otstup-r">
                        Рассылка участникам
                    </a>

                    <a href="#" class="btn-secondary otstup-r" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Выход</a>
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
                        @csrf
                    </form>
                </div>
            </header>


            <h1 class="outlined-text">Список секций</h1>
            <div class="sticker-container">
                @foreach ($confs as $conf)<br>
                <div class="sticker">
                    <center><b>{{ $conf->name }}</b></center><br>
                    Страна: {{ $conf->country }}<br>
                    Город: {{ $conf->city }}<br>
                    Дата начала конференции: {{ $conf->date_start }}<br>
                    Дата окончания конференции: {{ $conf->date_end }}<br>
                    Дедлайн: {{ $conf->deadline }}<br>
                    Описание: {{ $conf->description }}<br>
                    <div class="conf-buttons">
                        <a class="btn delete-btn otstup-r" href="{{route('conf.destroy', $conf->id)}}">
                            Удалить
                        </a>
                        <a class="btn upd-btn" href="{{route('conf.update', $conf->id)}}">
                            Редактировать
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="under"></div>
        </div>
    </div>
</body>
</html>
