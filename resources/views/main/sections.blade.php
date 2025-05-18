@extends('layouts.main')
@section('title', 'Секции конференции ' . $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/main/conference.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <style>
        .modal__container {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
            position: relative;
        }

        .modal__container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .modal__container h2 {
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
        }

        .form-group {
            width: 100%;
            max-width: 400px;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 200px;
        }

        .button:hover {
            background-color: #45a049;
        }

        .close {
            position: absolute;
            right: 10px;
            top: 5px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .page-container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }
        .main-content {
            flex: 1;
        }
        .sidebar {
            width: 300px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .theme-section {
            margin-bottom: 20px;
        }
        .theme-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .question-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .question-item {
            margin-bottom: 8px;
        }
        .question-link {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9em;
            display: block;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        .question-link:hover {
            background-color: #e9ecef;
            text-decoration: underline;
        }
        .faq-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .faq-modal__container {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 5px;
            position: relative;
        }
        .faq-modal__close {
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }
        .faq-modal__title {
            margin-top: 0;
            color: #333;
        }
        .faq-modal__answer {
            margin-top: 15px;
            color: #666;
            line-height: 1.5;
        }
    </style>
@endsection

@section('content')
    <div class="page-container">
        <div class="sidebar">
            <h2>Часто задаваемые вопросы</h2>
            @foreach($conference->faqs->groupBy('theme.name') as $themeName => $faqs)
                <div class="theme-section">
                    <h3 class="theme-title">{{ $themeName }}</h3>
                    <ul class="question-list">
                        @foreach($faqs as $faq)
                            <li class="question-item">
                                <a href="#" class="question-link" onclick="showFaqModal('{{ $faq->name }}', '{{ $faq->answer }}')">
                                    {{ $faq->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="main-content">
            <div class="conference">
                <h1>Конференция "{{ $conference->name }}"</h1>
                
                <div class="conference-info">
                    <p><strong>Город проведения:</strong> {{ $conference->city->name }}</p>
                    <p><strong>Место проведения:</strong> {{ $conference->address }}</p>
                    <p><strong>Дата проведения:</strong> {{ date('d-m-Y', strtotime($conference->date_start)) }} - {{ date('d-m-Y', strtotime($conference->date_end)) }}</p>
                    <p><strong>Крайний срок подачи заявок:</strong> {{ date('d-m-Y', strtotime($conference->deadline_applications)) }}</p>
                    <p><strong>Формат проведения:</strong> {{ $conference->format->name }}</p>
                    <p><strong>Организатор:</strong> {{ $conference->organizer->surname }} {{ $conference->organizer->name }} {{ $conference->organizer->patronymic }}</p>
                    <p><strong>Описание:</strong> {!! nl2br($conference->description) !!}</p>
                    <div class="conference-links">
                        <p class="link" onclick="showUploadConditions()">Дополнительные материалы</p>
                    </div>
                </div>

                <div class="conference__sections">
                    <h2>Секции конференции</h2>
                    @foreach($sections as $section)
                        <div class="section-card">
                            <h2>{{ $section->name }}</h2>
                            <p class="description">{{ $section->description }}</p>
                            <div class="section-info">
                                <p><strong>Дата проведения:</strong> {{ date('d-m-Y', strtotime($section->date_start)) }} - {{ date('d-m-Y', strtotime($section->date_end)) }}</p>
                                <p><strong>Модератор:</strong> {{ $section->moder->surname }} {{ $section->moder->name }} {{ $section->moder->patronymic }}</p>
                            </div>
                            @auth
                                @php
                                    $canRegister = true;
                                    $ageMessage = '';
                                    
                                    if (!is_null($conference->min_age) && $age < $conference->min_age) {
                                        $canRegister = false;
                                        $ageMessage = 'Ваш возраст меньше минимально допустимого';
                                    }
                                    
                                    if (!is_null($conference->max_age) && $age > $conference->max_age) {
                                        $canRegister = false;
                                        $ageMessage = 'Ваш возраст превышает максимально допустимый';
                                    }
                                @endphp

                                @if($canRegister)
                                    <button class="button" onclick="openModal({{ $section->id }})">Зарегистрироваться</button>
                                @else
                                    <p class="error-message">{{ $ageMessage }}</p>
                                @endif
                            @else
                                <p class="info-message">Для регистрации необходимо <a href="{{ route('login') }}">войти</a> в систему</p>
                            @endauth
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для FAQ -->
    <div id="faqModal" class="faq-modal">
        <div class="faq-modal__container">
            <span class="faq-modal__close" onclick="closeFaqModal()">&times;</span>
            <h3 class="faq-modal__title" id="faqModalTitle"></h3>
            <div class="faq-modal__answer" id="faqModalAnswer"></div>
        </div>
    </div>

    <!-- Остальные модальные окна -->
    <div class="modal" id="imageModal">
        <div class="modal__container">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" action="{{ route('conf.subscribe', $conference) }}" enctype="multipart/form-data" id="registrationForm">
                @csrf
                <input type="hidden" name="section_id" id="section_id" value="">
                <h2 style="margin-left:40px">Регистрация на секцию</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="role_id">Вид участия:</label>
                    <select id="role_id" class="authInput" name="role_id" required onchange="toggleFields()">
                        <option value="" disabled selected hidden>Выберите вид участия</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" data-name="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if($conference->format_id == 3)
                <div class="form-group">
                    <label for="presentation_type_id">Форма участия:</label>
                    <select id="presentation_type_id" class="authInput" name="presentation_type_id" required>
                        <option value="" disabled selected hidden>Выберите форму участия</option>
                        @foreach($presentationTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" name="presentation_type_id" value="{{ $conference->format_id }}">
                @endif

                <div id="speakerFields" style="display: none;">
                    <div class="form-group">
                        <label for="title">Наименование доклада:</label>
                        <input type="text" id="title" name="title" class="authInput">
                    </div>

                    <div class="form-group">
                        <label for="contributors">Соавторы (ФИО полностью через запятую):</label>
                        <input type="text" id="contributors" name="contributors" class="authInput">
                    </div>
                </div>
                <button class="button" type="submit" onclick="submitForm(event)">Отправить</button>
            </form>
        </div>
    </div>

    <div class="modal" id="uploadConditionsModal">
        <div class="modal__container">
            <span class="close" onclick="closeUploadConditions()">&times;</span>
            <h2>Дополительные материалы</h2>
            <div class="upload-conditions">
                @if($conference->files->count() > 0)
                    <div class="files-list">
                        @foreach($conference->files as $file)
                            <div class="file-item">
                                <span class="file-name">{{ $file->name }}</span>
                                <a href="{{ asset($file->file_path) }}" class="download-button" download>Скачать</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Файлы условий пока не загружены.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleFields() {
            const roleSelect = document.getElementById('role_id');
            const speakerFields = document.getElementById('speakerFields');
            const selectedRole = roleSelect.value;
            
            // Показываем поля для выступающего (id = 2)
            if (selectedRole === '2') {
                speakerFields.style.display = 'block';
                // Делаем поля обязательными для выступающего
                document.getElementById('title').required = true;
            } else {
                speakerFields.style.display = 'none';
                // Убираем обязательность полей для не-выступающего
                document.getElementById('title').required = false;
            }
        }

        function submitForm(event) {
            event.preventDefault();
            console.log('Form submission started');
            
            const form = document.getElementById('registrationForm');
            const formData = new FormData(form);
            
            // Выводим все данные формы в консоль
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Проверяем обязательные поля
            const sectionId = formData.get('section_id');
            const roleId = formData.get('role_id');
            const presentationTypeId = formData.get('presentation_type_id');
            
            if (!sectionId) {
                alert('Ошибка: не выбрана секция');
                return;
            }

            if (!roleId) {
                alert('Пожалуйста, выберите вид участия');
                return;
            }

            if (!presentationTypeId) {
                alert('Пожалуйста, выберите форму участия');
                return;
            }

            // Если выбран выступающий (id = 2), проверяем дополнительные поля
            if (roleId === '2') {
                const title = formData.get('title');
                if (!title) {
                    alert('Пожалуйста, введите наименование доклада');
                    return;
                }
            }

            // Если все проверки пройдены, отправляем форму
            form.submit();
        }

        function openModal(sectionId) {
            console.log('Opening modal for section:', sectionId);
            const modal = document.getElementById('imageModal');
            document.getElementById('section_id').value = sectionId;
            modal.style.display = "block";
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = "none";
        }

        function showUploadConditions() {
            document.getElementById('uploadConditionsModal').style.display = 'block';
        }

        function closeUploadConditions() {
            document.getElementById('uploadConditionsModal').style.display = 'none';
        }

        // Новые функции для FAQ
        function showFaqModal(question, answer) {
            const modal = document.getElementById('faqModal');
            document.getElementById('faqModalTitle').textContent = question;
            document.getElementById('faqModalAnswer').textContent = answer;
            modal.style.display = 'block';
        }

        function closeFaqModal() {
            document.getElementById('faqModal').style.display = 'none';
        }

        // Закрытие модальных окон при клике вне их области
        window.onclick = function(event) {
            const imageModal = document.getElementById('imageModal');
            const uploadConditionsModal = document.getElementById('uploadConditionsModal');
            const faqModal = document.getElementById('faqModal');
            
            if (event.target == imageModal) {
                imageModal.style.display = "none";
            }
            if (event.target == uploadConditionsModal) {
                uploadConditionsModal.style.display = "none";
            }
            if (event.target == faqModal) {
                faqModal.style.display = "none";
            }
        }
    </script>
@endsection

<style>
    .files-list {
        margin-top: 20px;
    }

    .file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .file-name {
        font-size: 16px;
        color: #333;
    }

    .download-button {
        padding: 8px 15px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .download-button:hover {
        background-color: #45a049;
    }
</style> 