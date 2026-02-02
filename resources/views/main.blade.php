@extends('layouts.main')
@section('title', 'Главная')
@section('some_styles')
  <style>
    .main__container {
      padding: 25px;
      background-color: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    .section {
      text-align: center;
      margin-bottom: 0;
      padding: 20px 40px;
      border-radius: 12px;
    }
    .support-block {
      text-align: center;
    }
    .section-title {
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 16px;
      color: #111;
    }
    .section-text {
      font-size: 1.1rem;
      line-height: 1.6;
      color: #333;
    }
    .support-desc {
      margin-bottom: 24px;
    }
    .support-logo {
      max-width: 250px;
      margin: 0 auto;
    }
    .button-container {
      text-align: center;
      margin: 40px 0;
    }
    .action-button {
      display: inline-block;
      background: #1976f8;
      color: #fff;
      padding: 16px 40px;
      font-size: 1.1rem;
      font-weight: 700;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.2s;
    }
    .action-button:hover {
      background: #1565d8;
    }
    .section-icon {
      font-size: 3rem;
      display: inline-block;
    }
    
    .section-title {
      font-size: 1.9rem;
      font-weight: 700;
      margin-bottom: 20px;
      color: #111;
      letter-spacing: -0.5px;
    }
    .section-text {
      font-size: 1.05rem;
      line-height: 1.8;
      color: #555;
      max-width: 90%;
      margin: 0 auto;
    }

    /* Новые стили для контактов */
    .contacts-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      max-width: 900px;
      margin: 0 auto;
      text-align: left;
    }

    .contact-card {
      background: linear-gradient(135deg, #f5f7fa 0%, #fff 100%);
      border-left: 4px solid #1976f8;
      padding: 20px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .contact-card:hover {
      box-shadow: 0 4px 12px rgba(25, 118, 248, 0.15);
      transform: translateY(-2px);
    }

    .contact-icon {
      font-size: 1.5rem;
      margin-bottom: 12px;
      display: block;
    }

    .contact-label {
      font-weight: 700;
      color: #111;
      font-size: 0.95rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
      display: block;
    }

    .contact-value {
      color: #555;
      font-size: 1rem;
      line-height: 1.5;
    }

    .contact-link {
      color: #1976f8;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .contact-link:hover {
      color: #1565d8;
      text-decoration: underline;
    }
  </style>
@endsection
@section('content')
<div class="main__container">
  <div class="section about-section">
    <h2 class="section-title">О компании</h2>
    <p class="section-text" style="text-align: center;">
      ООО "ДИДЖИТАЛ САЙНС СОФТ" — компания, специализирующаяся на разработке программного обеспечения 
      для цифровизации процессов в научной сфере. Мы создаём инновационные решения, 
      которые помогают организациям эффективнее управлять научными мероприятиями и улучшать качество работ.
    </p>
  </div>
  <div class="section about-section">
    <h2 class="section-title">О продукте</h2>
    <p class="section-text" style="text-align: center;">
      Мы разработали универсальную платформу для организации и проведения научных конференций. 
      Нас система позволяет автоматизировать управление заявками участников, проводить экспертную оценку докладов, 
      отслеживать статус работ и получать детальную аналитику по результатам мероприятия.
    </p>
  </div>
  <div class="section about-section support-block">
    <h2 class="section-title">При поддержке</h2>
    <div class="section-text support-desc" style="text-align: center;">
      Проект создан при поддержке Федерального государственного бюджетного учреждения 
      «Фонд содействия развитию малых форм предприятий в научно-технической сфере» 
      в рамках программы «Студенческий стартап» федерального проекта 
      «Платформа университетского технологического предпринимательства»
    </div>
    <img src="{{ asset('img/fond.png') }}" alt="Фонд содействия инновациям" class="support-logo" style="margin: 15px 0;">
  </div>
  <div class="button-container">
    <a href="{{ route('conf.index') }}" class="action-button">Перейти к конференциям</a>
  </div>
  <div class="section about-section">
    <div class="contacts-grid">
      <div class="contact-card">
        <span class="contact-icon">📍</span>
        <span class="contact-label">Адрес</span>
        <span class="contact-value">г. Тюмень, ул. Республики, д. 142, помещ. 35, кабинет 320</span>
      </div>
      <div class="contact-card">
        <span class="contact-icon">✉️</span>
        <span class="contact-label">Email</span>
        <a href="mailto:mgyndybin@gmail.com" class="contact-link">mgyndybin@gmail.com</a>
      </div>
      <div class="contact-card">
        <span class="contact-icon">👤</span>
        <span class="contact-label">Директор</span>
        <span class="contact-value">Гындыбин Михаил Викторович</span>
      </div>
    </div>
  </div>
</div>
@endsection