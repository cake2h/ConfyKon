@extends('layouts.main')
@section('title', 'Секции конференции ' . $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/main/conference.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main/sections.css') }}">
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
                    <p><strong>Даты проведения:</strong> {{ \Carbon\Carbon::parse($conference->date_start)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($conference->date_end)->format('d.m.Y') }}</p>
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
                                <p><strong>Дата проведения:</strong> {{ \Carbon\Carbon::parse($section->date_start)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($section->date_end)->format('d.m.Y') }}</p>
                                <p><strong>Время проведения:</strong> {{ \Carbon\Carbon::parse($section->date_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($section->date_end)->format('H:i') }}</p>
                                <p><strong>Место проведения:</strong> {{ $section->event_place ?: 'не определено' }}</p>
                                @if($section->link)
                                    <p><strong>Ссылка:</strong> <a href="{{ $section->link }}" target="_blank">{{ $section->link }}</a></p>
                                @endif
                                <p><strong>Модератор:</strong> {{ $section->moder->surname }} {{ $section->moder->name }} {{ $section->moder->patronymic }}</p>
                            </div>
                            @auth
                                @php
                                    $canRegister = true;
                                    $ageMessage = '';
                                    $isRegistered = auth()->user()->applications()
                                        ->where('section_id', $section->id)
                                        ->where('application_status_id', '!=', 2)
                                        ->exists();
                                    
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
                                    @if($isRegistered)
                                        <button class="button" disabled style="background-color: #92d0fa; color: black; border: none; border-radius: 6px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: background 0.2s;">Вы уже зарегистрированы</button>
                                    @else
                                        <button class="button" onclick="openModal({{ $section->id }})" style="background-color: #92d0fa; color: black; border: none; border-radius: 6px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: background 0.2s;">Зарегистрироваться</button>
                                    @endif
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
                <button class="button" type="submit" onclick="submitForm(event)" style="background-color: #92d0fa; color: black; border: none; border-radius: 6px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: background 0.2s;">Отправить</button>
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
                                <a href="{{ asset($file->file_path) }}" style="background-color: #92d0fa; color: black; border: none; border-radius: 6px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: background 0.2s;" class="download-button" download>Скачать</a>
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