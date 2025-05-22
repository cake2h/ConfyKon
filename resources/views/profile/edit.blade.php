@extends('layouts.main')
@section('title', 'Редактирование профиля')
@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}" />
<style>
    .authButton.custom-blue {
        background: #92d0fa;
        border: none;
        border-radius: 6px;
        padding: 12px 32px;
        font-weight: 600;
        cursor: pointer;
        margin: 30px auto 0 auto;
        display: block;
        transition: background 0.2s;
        color: #000;
    }
    .authButton.custom-blue:hover {
        background: #4a90e2;
    }
    .profile-container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 40px auto;
    }
</style>
<div class="profile-container">
    <h1 style="text-align:center;">Редактирование профиля</h1>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <div class="form-group">
            <label>Фамилия</label>
            <input type="text" name="surname" class="authInput" value="{{ old('surname', $user->surname) }}" required>
            @error('surname')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Имя</label>
            <input type="text" name="name" class="authInput" value="{{ old('name', $user->name) }}" required>
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Отчество</label>
            <input type="text" name="patronymic" class="authInput" value="{{ old('patronymic', $user->patronymic) }}">
            @error('patronymic')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Номер телефона</label>
            <input type="text" name="phone_number" class="authInput" value="{{ old('phone_number', $user->phone_number) }}" required>
            @error('phone_number')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Дата рождения</label>
            <input type="date" name="birthday" class="authInput" value="{{ old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '' ) }}" required>
            @error('birthday')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Город</label>
            <select name="city_id" class="authInput" required>
                <option value="">Выберите город</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" @if(old('city_id', $user->city_id)==$city->id) selected @endif>{{ $city->name }}</option>
                @endforeach
            </select>
            @error('city_id')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Уровень образования</label>
            <select name="education_level_id" class="authInput" required>
                <option value="">Выберите уровень образования</option>
                @foreach($educationLevels as $level)
                    <option value="{{ $level->id }}" @if(old('education_level_id', $user->education_level_id)==$level->id) selected @endif>{{ $level->name }}</option>
                @endforeach
            </select>
            @error('education_level_id')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Место обучения/работы</label>
            <select name="study_place_id" class="authInput" required>
                <option value="">Выберите место</option>
                @foreach($studyPlaces as $place)
                    <option value="{{ $place->id }}" @if(old('study_place_id', $user->study_place_id)==$place->id) selected @endif>{{ $place->name }}</option>
                @endforeach
            </select>
            @error('study_place_id')<p class="error">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="authButton custom-blue">Сохранить изменения</button>
    </form>
</div>
@endsection 