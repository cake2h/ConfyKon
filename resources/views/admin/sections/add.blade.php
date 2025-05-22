@extends('layouts.admin')
@section('title', 'Добавление секции в ' . $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}" />
@endsection

@section('content')
    <h2>Добавление секции к конференции {{ $conference->name }}</h2>
    <form method="POST" action="{{ route('admin.sections.store', $conference) }}" class="conference-form">
        @csrf

        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="date_start">Дата начала:</label>
            <input type="date" name="date_start" required>
        </div>

        <div class="form-group">
            <label for="date_end">Дата завершения:</label>
            <input type="date" name="date_end" required>
        </div>

        <!-- <div class="form-group">
            <label for="event_place">Место проведения:</label>
            <input type="text" name="event_place" required>
        </div> -->

        <div class="form-group">
            <label for="moderator_email">Email модератора:</label>
            <input type="email" name="moderator_email" required>
        </div>

        <div class="form-group">
            @error('moderator_email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="button-container">
            <button type="submit" class="button" style="background-color: #92d0fa; color: black; border: none; border-radius: 6px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: background 0.2s;">Добавить</button>
            <button type="button" class="button cancel-button" onclick="history.back()">Отмена</button>
        </div>
    </form>
@endsection
