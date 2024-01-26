@extends('layouts.admin')
@section('title', 'Добавление конференции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}" />
@endsection

@section('content')
    <div class="form-container">
        <h2>Добавление конференции</h2>
        <form method="POST" action="{{ route('conf.store') }}" class="conference-form">
            @csrf

            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label for="country">Страна:</label>
                <input type="text" name="country" required>
            </div>

            <div class="form-group">
                <label for="city">Город:</label>
                <input type="text" name="city" required>
            </div>

            <div class="form-group">
                <label for="date_start">Дата начала:</label>
                <input type="date" name="date_start" required>
            </div>

            <div class="form-group">
                <label for="date_end">Дата конца:</label>
                <input type="date" name="date_end" required>
            </div>

            <div class="form-group">
                <label for="deadline">Дедлайн:</label>
                <input type="date" name="deadline" required>
            </div>

            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea name="description" rows="5" required></textarea>
            </div>

            <button type="submit" class="button">Добавить</button>
        </form>
    </div>
@endsection
