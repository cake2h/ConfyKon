<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://yastatic.net/s3/passport-sdk/autofill/v1/sdk-suggest-with-polyfills-latest.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='{{ asset('/css/auth.css') }}'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap">
    <title>Авторизация</title>
</head>

<body class="bg-ex-fixed">
    <div class="container">
        <div class="content">
            <div class="logo">
                <img src="/img/logo.png" alt="logo" style="max-width: 300px;" />
            </div>

            <h1 class="title">Авторизация</h1>

            <form method="POST" action="{{ route('login') }}" class="formContainer">
                @csrf
                <input class="authInput" type="email" placeholder="E-mail" name="email"
                    value="{{ old('email') }}" />
                <input class="authInput" type="password" placeholder="Пароль" name="password" />

                @error('email')
                    <p>{{ $message }}</p>
                @enderror

                @error('password')
                    <p>{{ $message }}</p>
                @enderror
                <button type="submit" class="authButton">Войти</button>
            </form>
            <a href="/login/yandex" class="yandexButton">Войти через Яндекс ID</a>

            <p class="link">Нет аккаунта? <a href={{ route('register.page') }}>Зарегистрируйтесь!</a></p>
            <span class="link"><a href={{ route('password.request') }}>Забыли пароль?</a></span>
        </div>
    </div>
</body>

</html>
