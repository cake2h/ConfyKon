@extends('layouts.main')
@section('title', 'Главная')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <link rel="stylesheet" href="{{asset('css/main/conference.css')}}" />
@endsection

@php
    use Carbon\Carbon;

    if (Auth::user()) {
        $birthday = Auth::user()->birthday;
        $age = Carbon::parse($birthday)->age;
    }
@endphp

@section('content')
    <div class="container">
        <div class="sidebar">
            <h3>Поиск конференций</h3>
            <form method="GET" action="{{ route('conference.search') }}" id="searchForm">
                <input type="text" name="query" placeholder="Введите название" value="{{ request('query') }}">
                <button type="submit">Искать</button>

                <div class="month-filters">
                    <h3>Период</h3>
                    <div id="monthLinks"></div>
                </div>

                <input type="hidden" name="monthRange" id="monthRangeInput" value="{{ request('monthRange') }}">

                <button type="submit" id="searchButton" style="display: none;">Искать</button>
            </form>
        </div>

        <script>
            const monthNames = [
                'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 
                'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
            ];

            function generateMonthLinks() {
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth();
                
                const monthContainer = document.getElementById('monthLinks');
                monthContainer.innerHTML = '';  

                for (let i = currentMonth; i < monthNames.length; i++) {
                    addMonthElement(monthNames[i], currentYear, i + 1, monthContainer);
                }

                if (currentMonth === 11) { 
                    const nextYear = currentYear + 1;
                    addMonthElement(monthNames[0], nextYear, 1, monthContainer);
                }
            }

            function addMonthElement(monthName, year, monthIndex, container) {
                const monthElement = document.createElement('span');
                monthElement.textContent = `${monthName} ${year}`;
                monthElement.classList.add('month-item');
                monthElement.dataset.value = `${year}/${monthIndex.toString().padStart(2, '0')}`;

                monthElement.addEventListener('click', function () {
                    selectMonth(this.dataset.value);
                });

                container.appendChild(monthElement);
            }

            function selectMonth(selectedMonthData) {
                const [selectedYear, selectedMonthIndex] = selectedMonthData.split('/');
                const selectedMonthName = monthNames[parseInt(selectedMonthIndex) - 1];

                document.getElementById('monthRangeInput').value = `${selectedMonthName} ${selectedYear}`;

                document.getElementById('searchForm').submit();
            }

            window.onload = generateMonthLinks;
        </script>



        <div class="content">
            @foreach($conferences as $conference)
                <div class="conference">
                    <h2 class="title">{{ $conference->name }}</h2>
                    <div class="simple__info">
                        <p>Место проведения: {{ $conference->address }}</p>
                        <p>Дата проведения: {{ Carbon::parse($conference->date_start)->format('d-m-Y') }} - {{ Carbon::parse($conference->date_end)->format('d-m-Y') }} </p>
                        <p>Срок регистрации на конференцию до: <span style="color: #ff0000">{{ Carbon::parse($conference->date_start)->subDays(3)->format('d-m-Y') }} </span></p>
                        <p>Срок загрузки публикаций до: <span style="color: #ff0000">{{ Carbon::parse($conference->deadline)->addDays(7)->format('d-m-Y') }} </span></p>
                    </div>
                    <p>{!! nl2br(e($conference->description)) !!}</p>

                    @auth
                        @if($age < 35 and now() < Carbon::parse($conference->date_start)->subDays(2))
                            <p class="link" onclick="openModal()">Зарегистрироваться</p>
                        @elseif ($age > 35)
                            <button class="link" style="color: gray; opacity: 0.5" disabled>Ваш возраст превышает допустимый</button>
                        @else
                            <button class="link" style="color: gray; opacity: 0.5" disabled>Регистрация закончилась</button>
                        @endif
                    @else
                        <p class="message">Чтобы отправить заявку на участие, необходимо зарегистрироваться</p>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>


    <div class="modal" id="imageModal">
        <div class="modal__container">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" action="{{ route('conf.subscribe', $conference ?? '') }}" enctype="multipart/form-data">
                @csrf
                <h2 style="margin-left:40px">Регистрация на конференцию</h2>
                <div class="form-group">
                    <label for="name">Наименование доклада:</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label for="otherAuthors">Соавторы (ФИО <b>полностью</b> через запятую):</label>
                    <input type="text" name="otherAuthors">
                </div>

                <div class="form-group">
                    <label for="section_id">Секция:</label>
                    <select id="section_id" class="authInput" name="section_id">
                        <option value="" disabled selected hidden>Секция</option>
                        @foreach($conference->sections ?? [] as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="presentation_type_id">Форма участия:</label>
                    <select id="presentation_type_id" class="authInput" name="presentation_type_id">
                        <option value="" disabled selected hidden>Выберите форму</option>
                        @foreach($presentationTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="button" type="submit">Отправить</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openModal() {
            document.getElementById('imageModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
    </script>
@endsection
