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

            <input
                class="authInput"
                type="text"
                placeholder="Имя"
                name="name"
                value="{{old('name')}}"
            />

            <input
                class="authInput"
                type="text"
                placeholder="Отчество"
                name="midname"
                value="{{old('midname')}}"
            />

            <input
                class="authInput"
                type="email"
                placeholder="E-mail"
                name="email"
                value="{{old('email')}}"
            />

            <input
                class="authInput"
                type="password"
                placeholder="Пароль"
                name="password"
            />

            <input
                class="authInput"
                type="password"
                placeholder="Подтвердите пароль"
                name="password_confirmation"
            />

            <input
                class="authInput"
                type="text"
                placeholder="Город"
                name="city"
                value="{{old('city')}}"
            />

            <input
                class="authInput"
                type="text"
                placeholder="Место обучения"
                name="study_place"
                value="{{old('study_place')}}"
            />

            <select class="authInput" name="edu_id">
                <option value="" style="color: #B1B1B7" disabled selected hidden>Уровень образования</option>
                @foreach($educationLevels as $educationLevel)
                    <option style="color: black" name="edu_id"  value="{{ $educationLevel->id }}">{{ $educationLevel->title }}</option>
                @endforeach
            </select>

            <input
                class="authInput"
                type="text"
                placeholder="Дата рождения"
                name="birthday"
                value="{{old('birthday')}}"
                onfocus="(this.type='date')"
            />
            @error('surname')
                <p>{{$message}}</p>
            @enderror

            @error('name')
                <p>{{$message}}</p>
            @enderror

            @error('midname')
                <p>{{$message}}</p>
            @enderror

            @error('email')
                <p>{{$message}}</p>
            @enderror

            @error('password')
                <p>{{$message}}</p>
            @enderror

            @error('password_confirmation')
                <p>{{$message}}</p>
            @enderror

            @error('city')
                <p>{{$message}}</p>
            @enderror

            @error('study_place')
                <p>{{$message}}</p>
            @enderror

            @error('edu_id')
                <p>{{$message}}</p>
            @enderror

            @error('birthday')
                <p>{{$message}}</p>
            @enderror

            <button type="submit" class="authButton">Зарегистрироваться</button>
        </form>

        <p class="link">Уже есть аккаунт? <a href={{route('login.page')}}>Авторизируйтесь!</a></p>
    </div>

</body>
</html>
