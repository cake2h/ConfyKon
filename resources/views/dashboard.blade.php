@extends('layouts.main')
@section('title', 'Личный кабинет')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}" />
@endsection

@section('content')
    <div class="dashboard-container">
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
            @if (count($conferences) === 0)
                <p>Вы не отправили ни одной заявки (заглушка).</p>
            @else
                @foreach($conferences as $conference)
                    <div class="conference">
                        <h4>{{ $conference->name }}</h4>
                        <p><strong>Дата начала:</strong> {{ $conference->date_start }}</p>
                        <p class="conference-description">{{ $conference->description }}</p>
                        <a class="link" href="{{ route('conf.show', $conference->id) }}">Подробнее</a>
                    </div> 
                @endforeach
            @endif
        </div>
    </div>
@endsection
