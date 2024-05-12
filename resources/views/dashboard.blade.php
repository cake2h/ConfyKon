@extends('layouts.main')
@section('title', 'Личный кабинет')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}" />

@endsection

@section('content')
    <div class="user-info">
        <h2 class="user-name">{{ $user->surname }} {{ $user->name }} {{ $user->midname }}</h2>
        <ul class="user-details">
            <li><strong>Дата рождения:</strong> {{ $user->birthday }}</li>
            <li><strong>Почта:</strong> {{ $user->email }}</li>
            <li><strong>Город:</strong> {{ $user->city }}</li>
            <li><strong>Уровень образования:</strong> {{ $user->education_level->title }}</li>
            <li><strong>Место учебы:</strong> {{ $user->study_place }}</li>
        </ul>
    </div>

    <div class="conference-applications">
        <h3>Мои заявки</h3>
        @if (count(Auth::user()->applications) === 0)
            <p>Вы не отправили ни одной заявки.</p>
        @else
            <div class="applications">
                @foreach (Auth::user()->applications as $application)
                    <div class="application">
                        <p>Конференция: {{ $application->section->konf->name }}</p>
                        <p>Cекция: {{ $application->section->name }}</p>
                        <p>Название работы: {{ $application->name }}</p>
                        <p>Форма выступления: Очная</p>
                        <a class="link" onclick = "openModal()">Прикрепить публикацию</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if (count(Auth::user()->applications) > 0)
        <div class="modal" id="imageModal">
            <div class="modal__container">
                <span class="close" onclick="closeModal()">&times;</span>
                <form method="POST" action="{{ route('conf.dock') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="application_id" value="{{ $application->id }}">
                    <h1>Публикация доклада</h1>
                    <div class="form-group">
                        <label for="file">Файл:</label>
                        <input type="file" name="file" required>
                    </div>
                    <button class="button" type="submit">Отправить</button>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        function openModal() {
            var modal = document.getElementById('imageModal');
            modal.style.display = 'block';
        }

        function closeModal() {
            var modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }
    </script>
@endsection
