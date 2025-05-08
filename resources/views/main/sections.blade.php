@extends('layouts.main')
@section('title', 'Секции конференции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/main/conference.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
@endsection

@section('content')
    <div class="conference">
        <h1>Конференция "{{ $conference->name }}"</h1>
        
        <div class="conference-info">
            <p><strong>Место проведения:</strong> {{ $conference->address }}</p>
            <p><strong>Дата проведения:</strong> {{ date('d-m-Y', strtotime($conference->date_start)) }} - {{ date('d-m-Y', strtotime($conference->date_end)) }}</p>
            <p><strong>Крайний срок подачи заявок:</strong> {{ date('d-m-Y', strtotime($conference->deadline)) }}</p>
            <p><strong>Описание:</strong> {!! nl2br($conference->description) !!}</p>
            <p class="link" onclick="showUploadConditions()">Условия загрузки статьи</p>
        </div>

        <div class="conference__sections">
            <h2>Секции конференции</h2>
            @foreach($sections as $section)
                <div class="section-card">
                    <h2>{{ $section->name }}</h2>
                    <p class="description">{{ $section->description }}</p>
                    <div class="section-info">
                        <p><strong>Дата проведения:</strong> {{ date('d-m-Y', strtotime($section->event_date)) }}</p>
                        <p><strong>Модератор:</strong> {{ $section->moder->name }}</p>
                    </div>
                    @auth
                        @php
                            $canRegister = true;
                            $ageMessage = '';
                            
                            // Проверяем минимальный возраст
                            if (!is_null($conference->min_age) && $age < $conference->min_age) {
                                $canRegister = false;
                                $ageMessage = 'Ваш возраст меньше минимально допустимого';
                            }
                            
                            // Проверяем максимальный возраст
                            if (!is_null($conference->max_age) && $age > $conference->max_age) {
                                $canRegister = false;
                                $ageMessage = 'Ваш возраст превышает максимально допустимый';
                            }
                        @endphp

                        @if($canRegister)
                            <div class="section-actions">
                                <p class="link" onclick="openModal('{{ $section->id }}')">Зарегистрироваться</p>
                                
                            </div>
                        @else
                            <button class="link" style="color: gray; opacity: 0.5" disabled>{{ $ageMessage }}</button>
                        @endif
                    @else
                        <button class="link" style="color: gray; opacity: 0.5" disabled>Для регистрации необходимо войти в аккаунт</button>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal" id="imageModal">
        <div class="modal__container">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" action="{{ route('conf.subscribe', $conference) }}" enctype="multipart/form-data" id="registrationForm">
                @csrf
                <h2 style="margin-left:40px">Регистрация на секцию</h2>

                <div class="form-group">
                    <label for="role_id">Роль участия:</label>
                    <select id="role_id" class="authInput" name="role_id" required onchange="toggleFields()">
                        <option value="" disabled selected hidden>Выберите роль</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" data-name="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="speakerFields" style="display: none;">
                    <div class="form-group">
                        <label for="name">Наименование доклада:</label>
                        <input type="text" name="name" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="otherAuthors">Соавторы (ФИО <b>полностью</b> через запятую):</label>
                        <input type="text" name="otherAuthors" id="otherAuthors">
                    </div>
                </div>
                <button class="button" type="submit">Отправить</button>
            </form>
        </div>
    </div>

    <div class="modal" id="uploadConditionsModal">
        <div class="modal__container">
            <span class="close" onclick="closeUploadConditions()">&times;</span>
            <h2>Условия загрузки статьи</h2>
            <div class="upload-conditions">
                <!-- Здесь будет контент с условиями загрузки -->
                <p>Условия загрузки статьи будут добавлены позже.</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openModal(sectionId) {
            const modal = document.getElementById('imageModal');
            const form = document.getElementById('registrationForm');
            form.action = `/conference/${sectionId}/subs`;
            modal.style.display = "block";
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = "none";
        }

        function showUploadConditions() {
            const modal = document.getElementById('uploadConditionsModal');
            modal.style.display = "block";
        }

        function closeUploadConditions() {
            const modal = document.getElementById('uploadConditionsModal');
            modal.style.display = "none";
        }

        function toggleFields() {
            const roleSelect = document.getElementById('role_id');
            const speakerFields = document.getElementById('speakerFields');
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            
            if (selectedOption.dataset.name === 'Докладчик') {
                speakerFields.style.display = 'block';
            } else {
                speakerFields.style.display = 'none';
            }
        }

        // Закрытие модальных окон при клике вне их области
        window.onclick = function(event) {
            const imageModal = document.getElementById('imageModal');
            const uploadConditionsModal = document.getElementById('uploadConditionsModal');
            
            if (event.target == imageModal) {
                imageModal.style.display = "none";
            }
            if (event.target == uploadConditionsModal) {
                uploadConditionsModal.style.display = "none";
            }
        }
    </script>
@endsection 