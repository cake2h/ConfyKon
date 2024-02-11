@extends('layouts.main')
@section('title', $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/main/conference.css') }}">
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
            <div class="conference__direction">
                <h3>Математические и компьютерные методы решения задач</h3>
                <p><strong>Ответственный:</strong> Иванов Иван Иванович</p>
            </div>
            <div class="conference__direction">
                <h3>Методы, технологии и программные средства обработки и анализа данных</h3>
                <p><strong>Ответственный:</strong> Иванов Иван Иванович</p>
            </div>
            <div class="conference__direction">
                <h3>Модели и алгоритмы искусственного интеллекта</h3>
                <p><strong>Ответственный:</strong> Иванов Иван Иванович</p>
            </div>
            <div class="conference__direction">
                <h3>Cовременные мобильные и Интернет-технологии</h3>
                <p><strong>Ответственный:</strong> Иванов Иван Иванович</p>
            </div>
        </div>

        <a class="link" href="#" id="subscribeButton">Записаться</a>
    </div>
    <div class="modal" id="myModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="sectionList"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('myModal');
            var btn = document.getElementById('subscribeButton'); // Получаем кнопку по идентификатору "subscribeButton"
            var span = document.getElementsByClassName('close')[0];

            btn.onclick = function () {
                // AJAX запрос для получения списка секций конференции
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById('sectionList').innerHTML = xhr.responseText;
                        modal.style.display = 'block';
                    }
                };
                xhr.open('GET', '{{ route('conf.sections', $conference->id) }}', true);
                xhr.send();
            }

            span.onclick = function () {
                modal.style.display = 'none';
            }

            window.onclick = function (event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
@endsection