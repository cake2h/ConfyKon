<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='{{asset("./css/register.css")}}'>
    <title>Регистрация</title>
</head>
<body class="bg-ex-fixed">
    <div class="content">
        <div class="logo">
            <img src="./img/logo.png" alt="logo"/>
        </div>

        <h1 class="title">Регистрация</h1>

        <form method="POST" action="{{ route('register') }}" class="formContainer">
            @csrf
            <input
                class="authInput"
                type="text" 
                placeholder="Фамилия" 
                name="surname"
                value="{{old('surname')}}"
            />
            @error('surname')
                <p>{{$message}}</p>
            @enderror
            <input
                class="authInput"
                type="text" 
                placeholder="Имя" 
                name="name"
                value="{{old('name')}}"
            />
            @error('name')
                <p>{{$message}}</p>
            @enderror
            <input
                class="authInput"
                type="text" 
                placeholder="Отчество" 
                name="patronymic"
                value="{{old('patronymic')}}"
            />
            @error('middle_name')
                <p>{{$message}}</p>
            @enderror
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
            />
            @error('password')
                <p>{{$message}}</p>
            @enderror
            <input
                class="authInput"
                type="password"
                placeholder="Подтвердите пароль" 
                name="password_confirmation"
            />
            @error('password_confirmation')
                <p>{{$message}}</p>
            @enderror
            <input
                class="authInput"
                type="text"
                placeholder="Город" 
                name="city"
                value="{{old('city')}}"
            />
            @error('city')
                <p>{{$message}}</p>
            @enderror
            <input
                class="authInput"
                type="text"
                placeholder="Место обучения" 
                name="study_place"
                value="{{old('study_place')}}"
            />
            @error('city')
                <p>{{$message}}</p>
            @enderror
            <input
                class="authInput"
                type="text"
                placeholder="Уровень образования" 
                name="science_level"
                value="{{old('science_level')}}"
            />
            @error('city')
                <p>{{$message}}</p>
            @enderror
            <input
                class="authInput"
                type="text"
                placeholder="Дата рождения" 
                name="age"
                value="{{old('age')}}"
                onfocus="(this.type='date')"
            />
            @error('birth_date')
                <p>{{$message}}</p>
            @enderror
            
            <button type="submit" class="authButton">Зарегистрироваться</button>
        </form>
        
        <p class="link">Уже есть аккаунт? <a href={{route('login')}}>Авторизируйтесь!</a></p>
    </div>
    
</body>
</html>