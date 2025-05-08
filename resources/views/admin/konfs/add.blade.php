@extends('layouts.admin')

@section('title', 'Добавление конференции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>Добавление конференции</h1>
        <form action="{{ route('admin.konfs.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Название конференции:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea name="description" id="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="date_start">Дата начала:</label>
                <input type="date" name="date_start" id="date_start" required>
            </div>

            <div class="form-group">
                <label for="date_end">Дата окончания:</label>
                <input type="date" name="date_end" id="date_end" required>
            </div>

            <div class="form-group">
                <label for="registration_deadline">Срок регистрации:</label>
                <input type="date" name="registration_deadline" id="registration_deadline" required>
            </div>

            <div class="form-group">
                <label for="deadline">Срок подачи заявок:</label>
                <input type="date" name="deadline" id="deadline" required>
            </div>

            <button type="submit">Создать конференцию</button>
        </form>
    </div>
@endsection 