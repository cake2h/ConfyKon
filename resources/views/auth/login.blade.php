<style>
        form {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f5f5f5;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Стили для заголовка формы */
form h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

/* Стили для полей ввода */
form div {
  margin-bottom: 15px;
}

form div label {
  display: block;
  margin-bottom: 5px;
  color: #555;
}

form div select,
form div input[type="text"],
form div input[type="email"],
form div input[type="password"],
form div input[type="date"] {
  width: 100%;
  padding: 8px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

/* Стили для кнопок */
form div a {
  display: block;
  margin-bottom: 10px;
  color:#00aeef;
  text-decoration: none;
}

form div button {
  display: block;
  width: 100%;
  padding: 10px;
  font-size: 16px;
  background-color: #00aeef;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

/* Стили для ошибок валидации */
form div .error-message {
  color: #ff0000;
  margin-top: 5px;
  font-size: 14px;
}

/* Дополнительные стили */
body {
  background-color: #e9e9e9;
  font-family: Arial, sans-serif;
  line-height: 1.5;
}
        </style>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Почта')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Пароль')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Запомнить пользователя') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Забыл пароль?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Войти') }}
            </x-primary-button>
        </div>
    </form>
