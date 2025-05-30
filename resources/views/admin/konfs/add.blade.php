@extends('layouts.admin')

@section('title', 'Добавление конференции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .page-title {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .faq-button {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal__container {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .theme-list {
            list-style: none;
            padding: 0;
        }
        .theme-item {
            padding: 10px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-radius: 4px;
            cursor: pointer;
        }
        .theme-item:hover {
            background-color: #e9ecef;
        }

        .info-panel {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .info-panel-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #495057;
            font-size: 1.1em;
            font-weight: 600;
        }
        .info-panel-header i {
            margin-right: 10px;
            color: #0d6efd;
        }
        .info-panel-content {
            color: #6c757d;
        }
        .info-panel-content p {
            margin-bottom: 10px;
        }
        .info-panel-content ul {
            margin-left: 20px;
            margin-bottom: 0;
        }
        .info-panel-content li {
            margin-bottom: 5px;
        }
        .columns {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }
        .left__column {
            flex: 2;
            min-width: 0;
        }
        .right__column {
            flex: 1;
            min-width: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">Добавление конференции</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- <button type="button" class="button faq-button" onclick="openFaqModal()">Часто задаваемые вопросы</button> -->

        <form action="{{ route('admin.konfs.store') }}" method="POST" class="conference-form" enctype="multipart/form-data">
            @csrf
            <div class="columns">
                <div class="left__column">
                    <div class="form-group">
                        <label for="name">Название конференции:</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Описание:</label>
                        <textarea name="description" id="description" rows="4">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="city_id">Город проведения:</label>
                        <select name="city_id" id="city_id" class="select2" required>
                            <option value="">Выберите город</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address">Адрес проведения:</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Например: ул. Ленина, 10">
                    </div>

                    <div class="form-group">
                        <label for="format_id">Формат конференции:</label>
                        <select name="format_id" id="format_id" class="select2" required>
                            <option value="">Выберите формат</option>
                            @foreach($formats as $format)
                                <option value="{{ $format->id }}" {{ old('format_id') == $format->id ? 'selected' : '' }}>
                                    {{ $format->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="education_levels">Уровни образования:</label>
                        <select name="education_levels[]" id="education_levels" multiple required>
                            @foreach($educationLevels as $level)
                                <option value="{{ $level->id }}" {{ in_array($level->id, old('education_levels', [])) ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> -->

                    <div class="form-group">
                        <label>Дополнительные материалы</label>
                        <div id="files-container">
                            <div class="file-input-group">
                                <input type="text" name="file_names[]" placeholder="Название файла">
                                <input type="file" name="files[]" accept=".pdf,.doc,.docx">
                                <button type="button" class="remove-file">Удалить</button>
                            </div>
                        </div>
                        <button type="button" id="add-file" class="button">Добавить файл</button>
                    </div>
                </div>

                <div class="right__column">
                    <div class="form-group">
                        <label for="deadline_applications">Срок регистрации:</label>
                        <input type="date" name="deadline_applications" id="deadline_applications" value="{{ old('deadline_applications') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="deadline_reports">Срок подачи докладов:</label>
                        <input type="date" name="deadline_reports" id="deadline_reports" value="{{ old('deadline_reports') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_start">Дата начала:</label>
                        <input type="date" name="date_start" id="date_start" value="{{ old('date_start') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_end">Дата окончания:</label>
                        <input type="date" name="date_end" id="date_end" value="{{ old('date_end') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="min_age">Минимальный возраст:</label>
                        <input type="number" name="min_age" id="min_age" value="{{ old('min_age') }}" min="0">
                        <div class="checkbox-group">
                            <input type="checkbox" id="no_min_age" name="no_min_age" {{ old('no_min_age') ? 'checked' : '' }}>
                            <label for="no_min_age">Не указывать</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_age">Максимальный возраст:</label>
                        <input type="number" name="max_age" id="max_age" value="{{ old('max_age') }}" min="0">
                        <div class="checkbox-group">
                            <input type="checkbox" id="no_max_age" name="no_max_age" {{ old('no_max_age') ? 'checked' : '' }}>
                            <label for="no_max_age">Не указывать</label>
                        </div>
                    </div>
                </div>
            </div>
            <div>
            <div>
                <div class="info-panel">
                    <div class="info-panel-header">
                        
                        <span>Информация о FAQ</span>
                    </div>
                    <div class="info-panel-content">
                        <p>Добавить ответы на часто задаваемые вопросы можно на странице редактирования конференции.</p>
                        <!-- <p>После создания конференции вы сможете:</p>
                        <ul>
                            <li>Добавлять вопросы и ответы по темам</li>
                            <li>Редактировать существующие FAQ</li>
                            <li>Удалять ненужные вопросы</li>
                        </ul> -->
                    </div>
                </div>
            </div>
            </div>
                <div class="button-container" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="button" style="background-color: #92d0fa; color: black; border: none; border-radius: 6px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: background 0.2s;">Создать конференцию</button>
                    <button type="button" class="button cancel-button" onclick="history.back()">Отмена</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Модальное окно для FAQ -->
    <div id="faqModal" class="modal">
        <div class="modal__container">
            <span class="close" onclick="closeFaqModal()">&times;</span>
            <h2>Выберите тему вопроса</h2>
            <ul class="theme-list">
                @foreach($questionThemes as $theme)
                    <li class="theme-item" onclick="selectTheme({{ $theme->id }})">{{ $theme->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Выберите значение",
                allowClear: true
            });

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

            if (noMinAgeCheckbox.checked) {
                minAgeInput.disabled = true;
            }
            if (noMaxAgeCheckbox.checked) {
                maxAgeInput.disabled = true;
            }

            document.getElementById('add-file').addEventListener('click', function() {
                const container = document.getElementById('files-container');
                const fileGroup = document.createElement('div');
                fileGroup.className = 'file-input-group';
                fileGroup.innerHTML = `
                    <input type="text" name="file_names[]" placeholder="Название файла">
                    <input type="file" name="files[]" accept=".pdf,.doc,.docx">
                    <button type="button" class="remove-file">Удалить</button>
                `;
                container.appendChild(fileGroup);
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file')) {
                    e.target.closest('.file-input-group').remove();
                }
            });
        });

        function openFaqModal() {
            document.getElementById('faqModal').style.display = 'block';
        }

        function closeFaqModal() {
            document.getElementById('faqModal').style.display = 'none';
        }

        function selectTheme(themeId) {
            window.location.href = '/admin/konfs/faq/' + themeId;
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('faqModal')) {
                closeFaqModal();
            }
        }
    </script>
@endsection 