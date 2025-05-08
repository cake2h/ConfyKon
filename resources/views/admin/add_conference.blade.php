@extends('layouts.admin')
@section('title', 'Добавление конференции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}" />
@endsection

@section('content')
        <h2>Добавление конференции</h2>
        <form method="POST" action="{{ route('conf.store') }}" class="conference-form">
            @csrf
            <div class="columns">
                <div class="left__column">
                    <div class="form-group">
                        <label for="name">Название:</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Место проведения:</label>
                        <input type="text" name="address" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Описание:</label>
                        <textarea name="description" rows="5" required></textarea>
                    </div>
                </div>
                <div class="right__column">
                    <div class="form-group">
                        <label for="date_start">Дата начала:</label>
                        <input type="date" name="date_start" required>
                    </div>

                    <div class="form-group">
                        <label for="date_end">Дата окончания:</label>
                        <input type="date" name="date_end" required>
                    </div>

                    <div class="form-group">
                        <label for="registration_deadline">Срок регистрации на конференцию:</label>
                        <input type="date" name="registration_deadline" required>
                    </div>

                    <div class="form-group">
                        <label for="publication_deadline">Срок загрузки публикаций:</label>
                        <input type="date" name="publication_deadline" required>
                    </div>

                    <div class="form-group">
                        <label for="min_age">Минимальный возраст:</label>
                        <input type="number" name="min_age" id="min_age" min="0">
                        <div class="checkbox-group">
                            <input type="checkbox" id="no_min_age" name="no_min_age">
                            <label for="no_min_age">Не указывать</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_age">Максимальный возраст:</label>
                        <input type="number" name="max_age" id="max_age" min="0">
                        <div class="checkbox-group">
                            <input type="checkbox" id="no_max_age" name="no_max_age">
                            <label for="no_max_age">Не указывать</label>
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="button">Добавить</button>
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
        });
    </script>
@endsection
