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
                    <input type="date" name="date_start" required value="{{ $conf->date_start ? \Carbon\Carbon::parse($conf->date_start)->format('Y-m-d') : '' }}"> <br>
                </div>

                <div class="form-group">
                    <label for="date_end">Дата окончания:</label>
                    <input type="date" name="date_end" required value="{{ $conf->date_end ? \Carbon\Carbon::parse($conf->date_end)->format('Y-m-d') : '' }}">
                </div>

                <div class="form-group">
                    <label for="registration_deadline">Срок регистрации на конференцию:</label>
                    <input type="date" name="registration_deadline" value="{{ $conf->registration_deadline ? \Carbon\Carbon::parse($conf->registration_deadline)->format('Y-m-d') : '' }}" required>
                </div>

                <div class="form-group">
                    <label for="publication_deadline">Срок загрузки публикаций:</label>
                    <input type="date" name="publication_deadline" value="{{ $conf->publication_deadline ? \Carbon\Carbon::parse($conf->publication_deadline)->format('Y-m-d') : '' }}" required>
                </div>

                <div class="form-group">
                    <label for="min_age">Минимальный возраст:</label>
                    <input type="number" name="min_age" id="min_age" min="0" value="{{ $conf->min_age }}">
                    <div class="checkbox-group">
                        <input type="checkbox" id="no_min_age" name="no_min_age" {{ is_null($conf->min_age) ? 'checked' : '' }}>
                        <label for="no_min_age">Не указывать</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="max_age">Максимальный возраст:</label>
                    <input type="number" name="max_age" id="max_age" min="0" value="{{ $conf->max_age }}">
                    <div class="checkbox-group">
                        <input type="checkbox" id="no_max_age" name="no_max_age" {{ is_null($conf->max_age) ? 'checked' : '' }}>
                        <label for="no_max_age">Не указывать</label>
                    </div>
                </div>

                <div class="button-container">
                    <button type="submit" class="button">Изменить</button>
                    <button type="button" class="button cancel-button" onclick="history.back()">Отмена</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const noMinAgeCheckbox = document.getElementById('no_min_age');
            const noMaxAgeCheckbox = document.getElementById('no_max_age');
            const minAgeInput = document.getElementById('min_age');
            const maxAgeInput = document.getElementById('max_age');

            noMinAgeCheckbox.addEventListener('change', function() {
                minAgeInput.disabled = this.checked;
                if (this.checked) {
                    minAgeInput.value = '';
                }
            });

            noMaxAgeCheckbox.addEventListener('change', function() {
                maxAgeInput.disabled = this.checked;
                if (this.checked) {
                    maxAgeInput.value = '';
                }
            });

            // Инициализация состояния полей при загрузке страницы
            if (noMinAgeCheckbox.checked) {
                minAgeInput.disabled = true;
            }
            if (noMaxAgeCheckbox.checked) {
                maxAgeInput.disabled = true;
            }
        });
    </script>
@endsection

