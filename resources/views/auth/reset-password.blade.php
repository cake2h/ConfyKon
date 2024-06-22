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
            <img src="/img/logo.png" alt="logo" />
        </div>

        <h1 class="title">Новый пароль</h1>

        <form method="POST" class="formContainer" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <input id="email" style="display: none;" class="authInput"
                   type="email" name="email" value="{{ old('email', $request->email) }}"
                   required autofocus autocomplete="username" />

            <input id="password" class="authInput"
                   placeholder="Новый пароль" type="password" name="password"
                   required autocomplete="new-password" />

            <input id="password_confirmation" class="authInput"
                   type="password"
                   placeholder="Подтвердите пароль"
                   name="password_confirmation" required autocomplete="new-password" />

                <button type="submit" class="authButton">
                    {{ __('Восстановить пароль') }}
                </button>
        </form>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mt-2 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<script>
    // Log the form submission event to debug
    document.querySelector('form').addEventListener('submit', function(event) {
        console.log('Form submitted');
    });
</script>

</body>
</html>
