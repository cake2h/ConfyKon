<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://yastatic.net/s3/passport-sdk/autofill/v1/sdk-suggest-with-polyfills-latest.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='{{ asset('/css/auth.css') }}'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap">
    <title>Восстановление пароля</title>
</head>

<body class="bg-ex-fixed">
<div class="container">
    <div class="content">
        <div class="logo">
            <img src="/img/logo.png" alt="logo" style="max-width: 140px;" />
        </div>

        <h1 class="title">Восстановление пароля</h1>

        <form method="POST" class="formContainer" action="{{ route('password.email') }}">
            @csrf
                <input class="authInput" type="email" placeholder="E-mail" name="email"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <button type="submit" class="authButton">Восстановить пароль</button><br>
        </form>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <p class="link">Нет аккаунта? <a href={{ route('register.page') }}>Зарегистрируйтесь!</a></p>
    </div>
</div>
</body>

</html>

