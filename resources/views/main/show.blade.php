@extends('layouts.main')
@section('title', $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/main/conference.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
@endsection

@section('content')
    <div class="conference">
        <h1>{{ $conference->name }}</h1>
        <p class="section"><strong>Место проведения:</strong> {{ $conference->city }}, {{ $conference->country }}</p>
        <p class="section"><strong>Дата проведения:</strong> {{ date('d-m-Y', strtotime($conference->date_start)) }} - {{ date('d-m-Y', strtotime($conference->date_end)) }}</p>
        <p class="section"><strong>Крайний срок подачи заявок:</strong> {{ date('d-m-Y', strtotime($conference->deadline)) }}</p>
        <p class="section"><strong>Описание:</strong> {!! nl2br($conference->description) !!}</p>

        <div class="conference__sections">

            <h2>Направления конференции</h2>
            @foreach($sections as $section)
                <h3>{{$section->name}}</h3>
                <p><strong>Ответственный:</strong> {{$section->moder->name}}</p>
            @endforeach
        </div>

        <p class="link" onclick ="openModal()">Записаться</p>
    </div>

    
    <div class="modal" id="imageModal"> 
        <div class="modal__container">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" action="{{ route('conf.subscribe', $conference) }}" enctype="multipart/form-data">
                @csrf
                <h1>Записаться на конференцию</h1>
                <div class="form-group">
                    <label for="name">Название работы:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label for="file">Файл:</label>
                    <input type="file" name="file" required>
                </div>
                <div class="form-group">
                    <label for="section_id">Cекция:</label>
                    <select id="section_id" class="authInput" name="section_id" required>
                        <option value="" disabled selected>Выберите секцию</option>
                        @if($sections->count() > 0)
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>Нет доступных секций</option>
                        @endif
                    </select>
                    @if($sections->count() == 0)
                        <p style="color: red;">Внимание: Нет доступных секций для этой конференции</p>
                    @endif
                </div>
                <button class="button" type="submit">Отправить</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script> 
    function openModal() { 
        var modal = document.getElementById('imageModal'); 

        modal.style.display = 'block'; 
    } 

    function closeModal() { 
        var modal = document.getElementById('imageModal'); 
        modal.style.display = 'none'; 
    } 
</script>
@endsection
