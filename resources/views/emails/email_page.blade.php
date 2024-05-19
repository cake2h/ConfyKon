@extends('layouts.admin')
@section('title', 'Панель администратора')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
@endsection

@section('content')
    <div class="users">
        <h2>Адреса для отправки</h2>
        <select id="emailType">
            <option value="all">Все на сервисе</option>
            <option value="conference">Все на конфе</option>
            <option value="moderators">Модераторы</option>
            <option value="custom">Загрузить</option>
        </select>

        <form method="POST" action="{{ route('send.emails') }}">
            @csrf

            <textarea id="emailList" name="emails" readonly>
@foreach ($users as $user){{ trim("$user->email") }}
@endforeach
        </textarea>
            <button class="link" id="goBtn" type="submit">Рассылка</button>
        </form>
    </div>

    <div class="textBlock">
        <h2>Письмо для отправки</h2>
        <form action="{{ route('save.mail') }}" method="post">
            @csrf
            <textarea name="mail_text">{{ file_get_contents(resource_path('views/emails/mail.blade.php')) }}</textarea>
            <button class="link" id="saveBtn" type="submit">Сохранить</button>
            <button class="link" id="filesBtn" type="submit">Прикрепленные файлы</button>
        </form>
    </div>
@endsection

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">Закрыть</button>
    </div>
@endif

@section('scripts')
    <script>
        document.getElementById('emailType').addEventListener('change', function() {
            var emailType = this.value;
            var emailList = document.getElementById('emailList');

            if (emailType === 'custom') {
                emailList.readOnly = false;
                emailList.value = '';
            } else {
                emailList.readOnly = true;
                fetchEmails(emailType);
            }
        });

        function fetchEmails(type) {
            fetch('{{ route('get.emails') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type: type })
            })
                .then(response => response.json())
                .then(data => {
                    // Применение trim к каждому email и объединение их в строку
                    var trimmedData = data.map(function(email) {
                        return email.trim();
                    }).join("\n");

                    document.getElementById('emailList').value = trimmedData;
                });
        }
    </script>
@endsection
