@extends('layouts.admin')

@section('title', 'Редактирование конференции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <h1>Редактирование конференции</h1>
        <form action="{{ route('admin.konfs.update', $konf->id) }}" method="POST" class="conference-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="columns">
                <div class="left__column">
                    <div class="form-group">
                        <label for="name">Название конференции:</label>
                        <input type="text" name="name" id="name" value="{{ $konf->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Описание:</label>
                        <textarea name="description" id="description" rows="4">{{ $konf->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="city_id">Город проведения:</label>
                        <select name="city_id" id="city_id" class="select2" required>
                            <option value="">Выберите город</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ $konf->city_id == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address">Адрес проведения:</label>
                        <input type="text" name="address" id="address" value="{{ $konf->address }}" placeholder="Например: ул. Ленина, 10">
                    </div>

                    <div class="form-group">
                        <label for="format_id">Формат конференции:</label>
                        <select name="format_id" id="format_id" class="select2" required>
                            <option value="">Выберите формат</option>
                            @foreach($formats as $format)
                                <option value="{{ $format->id }}" {{ $konf->format_id == $format->id ? 'selected' : '' }}>
                                    {{ $format->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="education_levels">Уровни образования:</label>
                        <select name="education_levels[]" id="education_levels" multiple required>
                            @foreach($educationLevels as $level)
                                <option value="{{ $level->id }}" {{ in_array($level->id, $konf->educationLevels->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> -->

                    <div class="form-group">
                        <label>Дополнительные материалы</label>
                        <div id="files-container">
                            @foreach($konf->files as $file)
                                <div class="file-input-group">
                                    <input type="text" name="file_names[]" value="{{ $file->name }}" placeholder="Название файла">
                                    <input type="file" name="files[]" accept=".pdf,.doc,.docx">
                                    <input type="hidden" name="existing_files[]" value="{{ $file->id }}">
                                    <a href="{{ route('admin.konfs.download-file', $file->id) }}" class="button">Скачать</a>
                                    <button type="button" class="remove-file" data-file-id="{{ $file->id }}">Удалить</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-file" class="button">Добавить файл</button>
                    </div>
                </div>

                <div class="right__column">
                    <div class="form-group">
                        <label for="deadline_applications">Срок регистрации:</label>
                        <input type="date" name="deadline_applications" id="deadline_applications" value="{{ $konf->deadline_applications ? \Carbon\Carbon::parse($konf->deadline_applications)->format('Y-m-d') : '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="deadline_reports">Срок подачи докладов:</label>
                        <input type="date" name="deadline_reports" id="deadline_reports" value="{{ $konf->deadline_reports ? \Carbon\Carbon::parse($konf->deadline_reports)->format('Y-m-d') : '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_start">Дата начала:</label>
                        <input type="date" name="date_start" id="date_start" value="{{ $konf->date_start ? \Carbon\Carbon::parse($konf->date_start)->format('Y-m-d') : '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_end">Дата окончания:</label>
                        <input type="date" name="date_end" id="date_end" value="{{ $konf->date_end ? \Carbon\Carbon::parse($konf->date_end)->format('Y-m-d') : '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="min_age">Минимальный возраст:</label>
                        <input type="number" name="min_age" id="min_age" value="{{ $konf->min_age }}" min="0">
                        <div class="checkbox-group">
                            <input type="checkbox" id="no_min_age" name="no_min_age" {{ is_null($konf->min_age) ? 'checked' : '' }}>
                            <label for="no_min_age">Не указывать</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_age">Максимальный возраст:</label>
                        <input type="number" name="max_age" id="max_age" value="{{ $konf->max_age }}" min="0">
                        <div class="checkbox-group">
                            <input type="checkbox" id="no_max_age" name="no_max_age" {{ is_null($konf->max_age) ? 'checked' : '' }}>
                            <label for="no_max_age">Не указывать</label>
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="button">Сохранить изменения</button>
                        <a href="{{ route('admin.konfs.edit.faq', $konf->id) }}" class="button">Редактировать FAQ</a>
                        <button type="button" class="button cancel-button" onclick="history.back()">Отмена</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filesContainer = document.getElementById('files-container');
            const addFileButton = document.getElementById('add-file');

            addFileButton.addEventListener('click', function() {
                const fileGroup = document.createElement('div');
                fileGroup.className = 'file-input-group';
                fileGroup.innerHTML = `
                    <input type="text" name="file_names[]" placeholder="Название файла">
                    <input type="file" name="files[]" accept=".pdf,.doc,.docx">
                    <button type="button" class="remove-file">Удалить</button>
                `;
                filesContainer.appendChild(fileGroup);
            });

            filesContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file')) {
                    const fileGroup = e.target.closest('.file-input-group');
                    const fileId = e.target.dataset.fileId;
                    
                    if (fileId) {
                        // Если это существующий файл, добавляем его ID в скрытое поле для удаления
                        const deleteInput = document.createElement('input');
                        deleteInput.type = 'hidden';
                        deleteInput.name = 'delete_files[]';
                        deleteInput.value = fileId;
                        fileGroup.appendChild(deleteInput);
                        fileGroup.style.display = 'none';
                    } else {
                        // Если это новый файл, просто удаляем группу
                        fileGroup.remove();
                    }
                }
            });
        });
    </script>
@endsection 