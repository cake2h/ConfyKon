<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='{{asset("./css/auth.css")}}'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>Регистрация</title>
    <style>
        .select2-container--default .select2-selection--single {
            height: 50px;
            border: 1px solid #DCDCDF;
            border-radius: 25px !important;
            background-color: white;
            display: flex;
            align-items: center;
            box-shadow: none;
            transition: border-color 0.2s;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 50px;
            padding-left: 15px;
            border-radius: 25px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff;
        }
        
        .select2-dropdown {
            border: 1px solid #B1B1B7;
            border-radius: 8px;
        }
        
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #B1B1B7;
            border-radius: 4px;
            padding: 8px;
        }
        
        .select2-container--default .select2-results__option {
            padding: 8px 15px;
        }
        

        
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #B1B1B7;
        }
        
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #007bff;
        }

        .select2-container--default .select2-selection--single {
            font-size: 16px;
            font-family: inherit;
        }

        .select2-container {
            width: 100% !important;
            margin-bottom: 15px;
        }

    </style>
</head>
<body class="bg-ex-fixed">
    <div class="container">
        <div class="content">
            <div class="logo">
                <img src="/img/logo.png" alt="logo" style="max-width: 300px;"/>
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
                    placeholder="Отчество (необязательно)"
                    name="patronymic"
                    value="{{old('patronymic')}}"
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
                    type="text"
                    placeholder="Номер телефона"
                    name="phone_number"
                    id="phone_number"
                    value="{{ old('phone_number') }}"
                />
                <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js" type="text/javascript"></script>
                <script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js" type="text/javascript"></script>
                <script src="js/jquery.maskedinput.min.js"></script>

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

                <select class="authInput" name="city_id" id="citySelect">
                    <option value="">Выберите город</option>
                </select>

                <select class="authInput studyPlaceSelect" name="study_place_id" id="studyPlaceSelect">
                    <option value="" disabled selected hidden>Место обучения/работы</option>
                    @foreach($studyPlaces as $studyPlace)
                        <option value="{{ $studyPlace->id }}">{{ $studyPlace->name }}</option>
                    @endforeach
                </select>

                <select class="authInput eduSelect" name="education_level_id" id="eduSelect">
                    <option value="" disabled selected hidden>Уровень образования</option>
                    @foreach($educationLevels as $educationLevel)
                        <option value="{{ $educationLevel->id }}">{{ $educationLevel->name }}</option>
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

                @error('patronymic')
                    <p>{{$message}}</p>
                @enderror

                @error('email')
                    <p>{{$message}}</p>
                @enderror

                @if ($errors->has('phone_number'))
                    <div class="error">{{ $errors->first('phone_number') }}</div>
                @endif

                @error('password')
                    <p>{{$message}}</p>
                @enderror

                @error('password_confirmation')
                    <p>{{$message}}</p>
                @enderror

                @error('city_id')
                    <p>{{$message}}</p>
                @enderror

                @error('study_place_id')
                    <p>{{$message}}</p>
                @enderror

                @error('education_level_id')
                    <p>{{$message}}</p>
                @enderror

                @error('birthday')
                    <p>{{$message}}</p>
                @enderror

                <button type="submit" class="authButton">Зарегистрироваться</button>
            </form>
            <a href="/login/yandex" class="yandexButton">Войти через Яндекс ID</a>
            <p class="link">Уже есть аккаунт? <a href={{route('login.page')}}>Авторизируйтесь!</a></p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            // Инициализация маски для телефона
            $("#phone_number").mask("+7 (999) 999 99 99");

            // Инициализация Select2 для городов
            $('#citySelect').select2({
                placeholder: 'Выберите город',
                allowClear: true,
                appe
                ajax: {
                    url: '{{ route("cities.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                language: {
                    inputTooShort: function() {
                        return 'Введите минимум 2 символа для поиска';
                    },
                    noResults: function() {
                        return 'Город не найден';
                    },
                    searching: function() {
                        return 'Поиск...';
                    }
                }
            });

            // Обработка изменения цвета для select образования
            $('#eduSelect').on('change', function() {
                if (this.value === "") {
                    this.style.color = '#B1B1B7';
                } else {
                    this.style.color = 'black';
                }
            });
        });
    </script>
</body>
</html>
