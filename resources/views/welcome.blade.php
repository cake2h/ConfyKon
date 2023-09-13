
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Титульник</title>
    <link rel="stylesheet" href='{{asset("/css/font.css")}}'>
    <link rel="stylesheet" href='{{asset("/css/welcome.css")}}'>
</head>
<body class="antialiased ">
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0 bg-ex-fixed">
    <div class="container">
        <header class="flex-images between">
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


        @foreach ($konfs as $konf)
        <div class="square line" style="">
            <div id="stick2" class="stick2">
                <div class="flex-images margin-none">
                    <a class="otstup-r"><strong>{{ $konf->name }}</strong></a>
                    <a href="#" onclick="toggleStick2(event)" class="btn3">Дополнительно</a>
                </div>
                <p style="display: none;">
                    <div style="vertical-align: none; line-height: 30px;" class="margin-none">
                        Страна: {{ $konf->country }}<br>
                        Город: {{ $konf->city }}<br>
                        Дата начала: {{ $konf->date_start }}<br>
                        Дата окончания: {{ $konf->date_end }}<br>
                        Дедлайн: {{ $konf->deadline }}<br>
                        Описание: {{ $konf->description }}<br>
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
                square.style.height = "380px";
                
            }
        }
    </script>
</div>
</body>
</html>
