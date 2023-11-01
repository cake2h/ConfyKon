<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Конференции</title>
    <!-- <link rel="stylesheet" href='{{asset("/css/font.css")}}'>
    <link rel="stylesheet" href='{{asset("/css/dashboard.css")}}'> -->

    <link rel="stylesheet" href='{{asset("/css/main.css")}}'>
    <link rel="stylesheet" href='{{asset("/css/views.css")}}'>
    <link rel="stylesheet" href='{{asset("/css/normalize.css")}}'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap">
</head>

<body>
<header class="header-buttons">
  <nav>
    <a href="https://www.utmn.ru/">
      <svg class="h-[45px]" viewBox="0 0 185.9 83.8">
        <path class="st0" d="M78.3,41.8h-8v22.6h-5.7V41.8h-8v-5.1h21.7V41.8z M139.2,50.2l1,14.1h5.2l-1.8-23.9h-0.2h-5l-7.9,18.1   l-7.7-18.1h-5h-0.2l-1.8,23.9h5.2l1-13.9l5.9,13.9h5L139.2,50.2z M100.8,40.2c-6,0-11,4.3-12.1,9.9h-2.6v-9.7h-5.7v23.9h5.7v-9.1   h2.7c1.2,5.5,6.1,9.6,12,9.6c6.8,0,12.3-5.5,12.3-12.3S107.6,40.2,100.8,40.2 M100.8,45.4c3.9,0,7.1,3.2,7.1,7.1s-3.2,7.1-7.1,7.1   s-7.1-3.2-7.1-7.1S96.9,45.4,100.8,45.4 M165.6,36.7l7.7,18.5l-0.7,1.9c-0.6,1.5-1,3.3-4.4,3.3v5.1c5.9,0,7.6-3.1,9.7-8.4l8-20.4   h-5.8L176,49.4l-4.5-12.7H165.6z M155.1,41.8v22.6h-5.7V36.8l0,0h15.2v5.1L155.1,41.8L155.1,41.8z"></path>
        <polygon class="st0" points="52.8,7.6 39.6,0 0,22.8 0,38.1 13.2,45.7 13.2,30.5  "></polygon>
        <polygon class="st0" points="26.4,83.8 26.4,38.1 13.2,45.7 13.2,76.2  "></polygon>
      </svg>
    </a>
    <div>
        <a href="{{ route('conf.index') }}">Список конференций</a>
        @if (Auth::user()->isAdmin())
            <a href="{{ route('admin.index') }}">Админ-панель</a>
        @endif

        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Выход</a>
        <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
            @csrf
        </form>
    </div>
  </nav>
</header>

<div class="container">
    <div class="user-info-container">
        <strong><h2>{{$user->surname}} {{$user->name}} {{$user->midname}}</h></strong>
        <p><strong>Дата рождения:</strong> {{$user->birthday}}</p>
        <p><strong>Почта:</strong> {{$user->email}}</p>
        <p><strong>Город:</strong> {{$user->city}}</p>
        <p><strong>Уровень образования:</strong> {{$user->education_level->title}}</p>
        <p><strong>Место учебы:</strong> {{$user->study_place}}</p>
    </div>

    <p>Мои заявки</p>
    @if (count($conferences) === 0)
        <p>Вы не отправили ни одной заявки (Щас стоит заглушка!)</p>
    @else
        @foreach($conferences as $conference)
            <section id='{{ $conference->id }}' class="program-section section-hoverable font-size">
                <h2>{{ $conference->name }}</h2>
                <div class="info-box">
                    <p>Тут будет дополнительная информация о секции.</p>
                </div>
            </section>
            <div class="full-info" id='{{ $conference->id }}-info'>
                <div class="content">
                <p>Страна: {{ $conference->country }}</p>
                <p>Город: {{ $conference->city }}</p>
                <p>Дата начала: {{ $conference->date_start }}</p>
                <p>Дата окончания: {{ $conference->date_end }}</p>
                <p>Дедлайн: {{ $conference->deadline }}</p>
                <p>Описание: {{ $conference->description }}</p>
                </div>
            </div>
        @endforeach
    @endif
    
</div>

<footer>
    <p style="margin-left: 10px"></p>
</footer>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
  $(document).ready(function() {
    var scrollPosition = 0;
    
    $(".section-hoverable").click(function() {
      var section = $(this);
      var sectionId = section.attr("id");
      var fullInfo = $("#" + sectionId + "-info");

      // Обновляем содержимое и переключаем класс
      var content = section.find(".full-info .content").html();
      fullInfo.find(".content").html(content);
      fullInfo.toggleClass("active");
    });

    $(".info-box").mouseenter(function() {
      scrollPosition = $(this).scrollTop();
      $(this).css("overflow-y", "scroll");
    });

    $(".info-box").mouseleave(function() {
      $(this).css("overflow-y", "hidden");
      $(this).stop().animate({ scrollTop: 0 }, 500);
    });
  });
</script>
<script>
  $(document).ready(function() {
    // При наведении мыши на блок info-box внутри section
    $(".section-hoverable .info-box").mouseenter(function() {
      var section = $(this).closest("section");

      section.addClass("active");
    });

    // При уходе мыши с блока info-box внутри section
    $(".section-hoverable .info-box").mouseleave(function() {
      var section = $(this).closest("section");
      
      section.removeClass("active");
    });
  });
</script>
</body>
</html>