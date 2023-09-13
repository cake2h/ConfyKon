<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='{{asset("/css/login.css")}}'>
    <title>Авторизация</title>
</head>
<body class="bg-ex-fixed">
    <div class="content">
        <div class="logo">
            <img src="./img/logo.png" alt="logo"/>
        </div>

        <h1 class="title">Авторизация</h1>

        <form method="POST" action="{{ route('login') }}" class="formContainer">
            @csrf
            <input
                class="authInput"
                type="email" 
                placeholder="E-mail" 
                name="email"
                value="{{old('email')}}"
            />
            @error('email')
                <p>{{$message}}</p>
            @enderror
            <input
                class="authInput"
                type="password"
                placeholder="Пароль" 
                name="password"
                value="{{old('email')}}"
            />
            @error('password')
                <p>{{$message}}</p>
            @enderror
            <button type="submit" class="authButton">Войти</button>
        </form>
        
        <p class="link">Нет аккаунта? <a href={{route('register')}}>Зарегистрируйтесь!</a></p>
    </div>
    
</body>
</html>