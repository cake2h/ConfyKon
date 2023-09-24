<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href='{{asset("/css/font.css")}}'>
    <link rel="stylesheet" href='{{asset("/css/lk.css")}}'>
</head>
<body class="antialiased ">
<div class="relative flex items-top justify-center min-h-screen bg-ex-fixed">
    <div class="container">
        <header class="flex-images between">
            <a href="https://www.utmn.ru/" class="otstup-l"><img src="https://www.utmn.ru/upload/medialibrary/47f/logo_utmn_mini2_rus.png" alt="ТюмГУ" width="120"/></a>
            <div class="hidden flex-images">
                <a href="{{ route('konf.index') }}" class="btn-secondary otstup-r">Список конференций</a>
                
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('konf.index') }}" class="btn-secondary otstup-r">Админ-панель</a>
                @endif

                <a href="#" class="btn-secondary otstup-r" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Выход</a>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
                    @csrf
                </form>
            </div>
        </header>
      


        <div class="square2 line">
            <div class="stick1">
                <div style="vertical-align: none; line-height: 30px;" class="margin-none">
                    <p><h3><strong>{{$data->surname}} {{$data->name}} {{$data->patronymic}}</strong></h3></p>
                    <p><strong>Год рождения:</strong> {{$data->age}}</p>
                    <p><strong>Почта:</strong> {{$data->email}}</p>
                    <p><strong>Город:</strong> {{$data->city}}</p>
                    <p><strong>Научная степень:</strong> {{$data->science_level}}</p>
                    <p><strong>Место учебы:</strong> {{$data->study_place}}</p>
                </div>
            </div>
        </div>
        @foreach($conferences as $conference)
        
        <div class="square line" style="">
            <div id="stick2" class="stick2 " >
                <div class="flex-images margin-none">
                    <a class="otstup-r"><strong>{{ $conference->name }}</strong></a>
                    <a href="#" onclick="toggleStick2(event)" class="btn3">Дополнительно</a>
                </div>
                <p style="display: none;">
                    <div style="vertical-align: none; line-height: 30px;" class="margin-none">
                        Страна: {{ $conference->country }}<br>
                        Город: {{ $conference->city }}<br>
                        Дата начала: {{ $conference->date_start }}<br>
                        Дата окончания: {{ $conference->date_end }}<br>
                        Дедлайн: {{ $conference->deadline }}<br>
                        Описание: {{ $conference->description }}<br>
                    </div>
                </p>
            </div>
        </div>
        @endforeach

        <div class="under" ></div>
    </div>
    <script>
        function toggleStick2(event) {
            var button = event.target;
            var stick2 = button.closest(".square").querySelector(".stick2");
            var hiddenText = stick2.querySelector("p");
            var square = button.closest(".square");
            event.preventDefault()

            if (stick2.classList.contains("stick2-visible")) {
                stick2.scrollTop = 0;
                stick2.classList.remove("stick2-visible");
                square.style.height = "80px";
            } else {
                stick2.classList.add("stick2-visible");
                square.style.height = "300px";
                
            }
        }
    </script>
</div>
</body>
</html>






