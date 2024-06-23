@extends('layouts.main')
@section('title', 'Личный кабинет')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}" />

@endsection

@section('content')
    <div class="user-info">
        <h2 class="user-name">{{ $user->name }}</h2>
        <ul class="user-details">
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Номер телефона:</strong> {{ $user->phone_number }}</li>
            <li><strong>Дата рождения:</strong> {{ \Carbon\Carbon::parse($user->birthday)->format('d-m-Y') }}</li>
            <li><strong>Город:</strong> {{ $user->city }}</li>
            <li><strong>Уровень образования:</strong> @if($user->edu_id != null) {{ $user->education_level->title }}@endif</li>
            <li><strong>Место обучения/работы:</strong> {{ $user->study_place }}</li>

            <br/>
            <a class="link" href="{{ asset('public/publications/Документ.docx') }}" download>Оформление статьи</a>
            <a class="link" href="{{ asset('public/publications/Документ.docx') }}" download>Условия загрузки статьи</a>
        </ul>
    </div>

    <div class="conference-applications">
        <h3>Мои регистрации</h3>
        @if (count(Auth::user()->applications) === 0)
            <p>Вы не зарегистрированы ни на одну секцию</p>
        @else
            <div class="applications">
                @foreach(Auth::user()->applications as $application)
                    <div class="application">
                        <div class="up">
                            <p>Соавторы: {{ $application->otherAuthors }}</p>
                            <p>Конференция: {{ $application->section->konf->name }}</p>
                            <p>Cекция: {{ $application->section->name }}</p>
                            <p>Дата проведения: {{ $application->section->event_date }}</p>
                            <p>Место проведения: {{ $application->section->event_place }}</p>
                        </div>
                        <div class="down">
                            <p>Название доклада: {{ $application->name }}</p>

                            <a class="link @if(!$currentDate->between($application->section->konf->conferenceDates->date_start,
                                    $application->section->konf->conferenceDates->deadline) and $application->status != 1) inactive @endif" onclick="openModal()">Прикрепить публикацию</a>

                        @if ($application->file_path)
                            <p>
                                Статус:
                                @if ($application->status == 1)
                                    <span class="status status-approved">Одобрено</span>
                                @elseif ($application->status == 2)
                                    <span class="status status-rejected">Отклонено</span>
                                @else
                                    <span class="status status-pending">В ожидании</span>
                                @endif
                            </p>
                        @else
                            <p>
                                Статус:
                                <span class="status status-pending">Не прикреплено</span>
                            </p>
                        @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="modal" id="imageModal">
        <div class="modal__container">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" action="{{ route('conf.dock') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="application_id" value="{{ isset($application) ? $application->id : '' }}">
                <h1>Публикация доклада</h1>
                <div class="form-group">
                    <label for="file">Файл:</label>
                    <input type="file" name="file" required>
                </div>
                <button class="button" type="submit">Отправить</button>
            </form>
        </div>
    </div>
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
