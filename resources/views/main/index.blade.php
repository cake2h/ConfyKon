@extends('layouts.main')
@section('title', 'Главная')

@section('some_styles')
    
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <link rel="stylesheet" href="{{asset('css/main/conference.css')}}" />
@endsection

@section('content')
    <div class="conferences">
        @foreach($conferences as $conference)
            <div class="conference">
                <h1 class="title">{{ $conference->name }}</h1>
                
                <div class="simple__info">
                    <p>Место проведения: {{ $conference->country }}, {{ $conference->city }}
                    <p>Дата проведения: {{ $conference->date_start }} - {{ $conference->date_end }}</p>
                    <p>Крайний срок подачи заявок: {{ $conference->deadline }}</p>
                </div>

                
                <p>{!! nl2br($conference->description) !!}</p>

                @auth
                    <p class="link" onclick ="openModal()">Записаться</p>
                @else
                    <p class="message">Чтобы отправить заявку на участие, необходимо зарегистрироваться</p>
                @endauth

                <h2>Направления конференции</h2>
                <div class="sections">
                    @foreach($conference->sections as $section)
                        <div class="section">
                            <h3>{{ $section->name }}</h3>
                            <p class="moder"><strong>Ответственный: </strong>{{ $section->moder->surname }} {{ $section->moder->name }}</p>
                            <p>{!! nl2br($section->description) !!}</p>
                        </div>
                    @endforeach
                        
                    
                </div>
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
                            <select id="section_id" class="authInput" name="section_id">
                                <option value=""  disabled selected hidden>Секция</option>
                                @foreach($conference->sections as $section)
                                    <option name="section_id"  value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="button" type="submit">Отправить</button>
                    </form>
                </div>
            </div>
        @endforeach
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