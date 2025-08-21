@extends('layouts.admin')
@section('title', 'Изменение секции ' . $section->name . ' в ' . $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}" />
@endsection

@section('content')

    <h2>Изменение секции {{ $section->name }} в конференции {{ $conference->name }}</h2>
    <form method="POST" action="{{ route('admin.sections.update', ['conference' => $conference->id, 'section' => $section->id]) }}" class="conference-form">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" name="name" value="{{ $section->name }}" required>
        </div>

        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea name="description" rows="4">{{ $section->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="date_start">Дата и время начала:</label>
            <input type="datetime-local" name="date_start" value="{{ $section->date_start ? \Carbon\Carbon::parse($section->date_start)->format('Y-m-d\TH:i') : '' }}" required>
        </div>

        <div class="form-group">
            <label for="date_end">Дата и время завершения:</label>
            <input type="datetime-local" name="date_end" value="{{ $section->date_end ? \Carbon\Carbon::parse($section->date_end)->format('Y-m-d\TH:i') : '' }}" required>
        </div>

        <div class="form-group">
            <label for="event_place">Место проведения (адрес и аудитория):</label>
            <input type="text" name="event_place" value="{{ $section->event_place }}">
        </div>

        <div class="form-group">
            <label for="link">Ссылка (необязательно):</label>
            <input type="url" name="link" value="{{ $section->link }}">
        </div>

        <div class="form-group">
            <label for="moderator_email">Email модератора:</label>
            <input type="email" name="moderator_email" value="{{ $moderatorEmail }}" required>
        </div>

        <div class="form-group">
            @error('moderator_email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="button-container">
            <button type="submit" class="button" style="background-color: #92d0fa; color: black; border: none; border-radius: 6px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: background 0.2s;">Сохранить изменения</button>
            <button type="button" class="button cancel-button" onclick="history.back()">Отмена</button>
        </div>
    </form>
@endsection
