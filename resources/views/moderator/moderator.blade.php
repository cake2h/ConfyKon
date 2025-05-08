@extends('layouts.admin')
@section('title', 'Панель модератора')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
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
                    <h1 class="title">{{ $section->name }}</h1>
                    @php
                        $sectionApplicants = $applicants->where('section_name', $section->name);
                    @endphp
                    
                    @if($sectionApplicants->count() > 0)
                        <ul>
                            @foreach ($sectionApplicants as $applicant)
                                <li>
                                    <span>{{ $applicant->user_name }} - {{ $applicant->work_name }}</span>
                                    @if ($applicant->file_path)
                                        <a href="{{ asset($applicant->file_path) }}" class="download-link">Скачать файл</a>
                                    @endif
                                    <div class="button-group">
                                        <form action="{{ route('application.approve', $applicant->application_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="approve-button">Одобрить</button>
                                        </form>
                                        <form action="{{ route('application.reject', $applicant->application_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="reject-button">Отклонить</button>
                                        </form>
                                    </div>
                                    @if ($applicant->status == 1)
                                        <span class="status status-approved">Одобрено</span>
                                    @elseif ($applicant->status == 2)
                                        <span class="status status-rejected">Отклонено</span>
                                    @else
                                        <span class="status status-pending">В ожидании</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Загруженных статей нет</p>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
@endsection
