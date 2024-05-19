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
            <textarea name="description" rows="5" required>{{ $section->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="moderator_email">Email модератора:</label>
            <input type="email" name="moderator_email" value="{{ $moderatorEmail }}" required>
        </div>

        <button type="submit" class="button">Изменить</button>
    </form>
@endsection
