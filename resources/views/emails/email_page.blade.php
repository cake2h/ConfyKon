@extends('layouts.admin')
@section('title', 'Панель администратора')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
@endsection

@section('content')
    <div class="users">
        <h2>Адреса для отправки</h2>
        <textarea readonly>
@foreach ($users as $user)
{{ $user->surname }} {{ $user->name }} {{ $user->midname }} | {{ $user->email }}
@endforeach
    </textarea>

        <form method="POST" action="{{ route('send.emails') }}">
            @csrf
            <button class="link" id="goBtn" type="submit">
                Рассылка
            </button>
        </form>
    </div>
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
