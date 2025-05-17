@extends('layouts.main')
@section('title', 'Часто задаваемые вопросы - ' . $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/main/conference.css') }}">
@endsection

@section('content')
    <div class="conference">
        <h1>Часто задаваемые вопросы</h1>
        <h2>Конференция "{{ $conference->name }}"</h2>
        
        <div class="faq-content">
            @if($conference->faqs && $conference->faqs->count() > 0)
                <div class="faq-list">
                    @foreach($conference->faqs as $faq)
                        <div class="faq-item">
                            <h3>{{ $faq->question }}</h3>
                            <p>{{ $faq->answer }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p>Часто задаваемые вопросы пока не добавлены.</p>
            @endif
        </div>

        <div class="back-link">
            <a href="{{ route('conf.sections.show', $conference->id) }}" class="link">Вернуться к секциям</a>
        </div>
    </div>
@endsection 