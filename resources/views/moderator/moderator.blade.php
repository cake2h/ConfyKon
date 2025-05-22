@extends('layouts.admin')
@section('title', 'Панель модератора')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .rejected-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .rejected-section h2 {
            color: #666;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .rejected-applicant {
            opacity: 0.7;
            position: relative;
            padding-right: 60px;
        }
        .restore-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .restore-button:hover {
            background-color: #45a049;
        }
        .restore-button i {
            font-size: 1.2em;
        }
    </style>
@endsection

@section('content')
    <div class="conferences">
        @if(isset($error))
            <div class="error-message">
                <p>{{ $error }}</p>
            </div>
        @else
            @foreach($sections as $section)
                <div class="conference">
                    <h1 class="title">Регистрации секции {{ $section->name }}</h1>
                    <a href="{{ route('moderator.reports', $section->id) }}" class="link">Доклады</a>
                    
                    @php
                        $sectionActiveApplicants = $activeApplicants->where('section_name', $section->name);
                        $sectionRejectedApplicants = $rejectedApplicants->where('section_name', $section->name);
                    @endphp
                    
                    @if($sectionActiveApplicants->count() > 0)
                        <h2>Активные заявки: {{ $sectionActiveApplicants->count() }}</h2>
                        <ul>
                            @foreach ($sectionActiveApplicants as $applicant)
                                <li>
                                    <span>{{ $applicant->surname }} {{ $applicant->name }} {{ $applicant->patronymic }}</span></br>
                                    <span>Уровень образования: {{ $applicant->education_level_name }}</span></br>
                                    <span>Место обучения: {{ $applicant->study_place_name }}</span></br>
                                    <span>Вид участия: {{ $applicant->participation_type_name }}</span></br>
                                    @if ($applicant->report_theme)
                                        <span> Наименование доклада: {{ $applicant->report_theme }}</span></br>
                                    @endif
                                    <span>Форма участия: {{ $applicant->presentation_type_name }}</span></br>
                                    <div class="button-group">
                                        <form action="{{ route('application.reject-application', $applicant->application_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="reject-button">Отклонить</button>
                                        </form>
                                    </div>
                                </li></br>
                            @endforeach
                        </ul>
                    @else
                        <p>Активных заявок нет</p>
                    @endif

                    @if($sectionRejectedApplicants->count() > 0)
                        <div class="rejected-section">
                            <h2>Отклоненные заявки: {{ $sectionRejectedApplicants->count() }}</h2>
                            <ul>
                                @foreach ($sectionRejectedApplicants as $applicant)
                                    <li class="rejected-applicant">
                                        <div class="applicant-info">
                                            <span>{{ $applicant->surname }} {{ $applicant->name }} {{ $applicant->patronymic }}</span></br>
                                            <span>Уровень образования: {{ $applicant->education_level_name }}</span></br>
                                            <span>Место обучения: {{ $applicant->study_place_name }}</span></br>
                                            <span>Вид участия: {{ $applicant->participation_type_name }}</span></br>
                                            @if ($applicant->report_theme)
                                                <span> Наименование доклада: {{ $applicant->report_theme }}</span></br>
                                            @endif
                                            <span>Форма участия: {{ $applicant->presentation_type_name }}</span></br>
                                            <span class="status status-rejected">Отклонено</span>
                                        </div>
                                        <form action="{{ route('application.restore-application', $applicant->application_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="restore-button" title="Восстановить заявку">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    </li></br>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
@endsection
