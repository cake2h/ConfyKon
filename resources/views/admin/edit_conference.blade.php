@extends('layouts.admin')
@section('title', 'Изменение конференции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}" />
@endsection

@section('content')
    <h2>Изменение конференции</h2>
    <form method="POST" action="{{ route('conf.update', $conf->id) }}" class="conference-form">
        @csrf
        <div class="columns">
            <div class="left__column">
                <div class="form-group">
                    <label for="name">Название:</label>
                    <input type="text" name="name" required value="{{ $conf->name }}"><br>
                </div>

                <div class="form-group">
                    <label for="address">Страна:</label>
                    <input type="text" name="address" required value="{{ $conf->address }}"><br>
                </div>

                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea name="description" required>{{ $conf->description }}</textarea><br>
                </div>
            </div>
            <div class="right__column">
                <div class="form-group">
                    <label for="date_start">Дата начала:</label>
                    <input type="date" name="date_start" required value="{{ $conf->date_start }}"> <br>
                </div>

                <div class="form-group">
                    <label for="date_end">Дата окончания:</label>
                    <input type="date" name="date_end" required value="{{ $conf->date_end }}">
                </div>

                <div class="form-group">
                    <label for="deadline">Дедлайн:</label>
                    <input type="date" name="deadline" required value="{{ $conf->deadline }}"><br>
                </div>
            </div>
        </div>
        <button type="submit" class="button">Изменить</button>
    </form>
@endsection

