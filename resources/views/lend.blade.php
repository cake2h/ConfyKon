<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Научный форум</title>
    <!-- Montserrat font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .main-content {
            background: url('./img/land.jpg') no-repeat center center / cover;
            color: #fff;
        }
        html, body {
            scroll-behavior: smooth;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', Arial, sans-serif;
            background: #fff;
        }
        .navbar {
            width: 100%;
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 60px;
            min-height: 70px;
            box-sizing: border-box;
        }
        .navbar-left, .navbar-right {
            display: flex;
            gap: 32px;
        }
        .navbar-center {
            font-size: 1.35rem;
            font-weight: 400;
            letter-spacing: 0.01em;
        }
        .navbar a, .navbar span {
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 1rem;
            color: #111;
            text-decoration: none;
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }
        .navbar a.active {
            color: #1976f8;
        }
        .navbar a:active, .navbar a:focus {
            outline: none;
        }
        .navbar span {
            color: #111;
            opacity: 0.7;
            cursor: default;
        }
        @media (max-width: 900px) {
            .navbar { padding: 0 20px; }
            .navbar-left, .navbar-right { gap: 18px; }
        }
        @media (max-width: 600px) {
            .navbar { flex-direction: column; gap: 10px; padding: 0 5px; }
        }
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 70vh;
            text-align: center;
        }
        .main-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin: 60px 0 30px 0;
            letter-spacing: 0.01em;
        }
        .main-content p {
            font-size: 1.25rem;
            font-weight: 400;
            max-width: 700px;
            margin: 0 auto 50px auto;
            line-height: 1.5;
        }
        .main-content .start-btn {
            display: inline-block;
            background: #1976f8;
            color: #fff;
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            border: none;
            border-radius: 32px;
            padding: 18px 48px;
            margin-top: 10px;
            cursor: pointer;
            box-shadow: 0 2px 8px #1976f81a;
            transition: background 0.2s;
            text-decoration: none;
        }
        .main-content .start-btn:active, .main-content .start-btn:focus {
            outline: none;
        }
        @media (max-width: 600px) {
            .main-content h1 { font-size: 2rem; }
            .main-content p { font-size: 1rem; }
            .main-content .start-btn { padding: 14px 24px; font-size: 1rem; }
        }
        .features-block {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 0 auto 60px auto;
            max-width: 1100px;
            padding: 0 20px;
        }
        .feature-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px #0001;
            padding: 38px 32px 32px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 260px;
            max-width: 320px;
            flex: 1 1 0;
        }
        .feature-img {
            width: 80px;
            height: 80px;
            background: #f2f4f8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #888;
            margin-bottom: 24px;
            font-weight: 600;
        }
        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-align: center;
        }
        .feature-desc {
            font-size: 1rem;
            color: #222;
            text-align: center;
            line-height: 1.5;
        }
        @media (max-width: 900px) {
            .features-block { gap: 18px; }
            .feature-card { padding: 28px 12px 24px 12px; }
        }
        @media (max-width: 700px) {
            .features-block {
                flex-direction: column;
                align-items: center;
                gap: 24px;
            }
            .feature-card {
                min-width: 0;
                width: 100%;
                max-width: 400px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="/">Главная</a>
            <a href="#pricing-section">Цены</a>
            <a href="#contacts-footer">Контакты</a>
        </div>
        <div class="navbar-center">
            Сервис для организации научных конференций
        </div>
        <div class="navbar-right">
            <a href="/register">Регистрация</a>
            <a href="/login">Вход</a>
        </div>
    </nav>
    <div class="main-content">
        <h1>Упростите организацию конференции</h1>
        <p>Управляйте научной конференцией без лишних усилий. Принимайте заявки, проверяйте отчёты и получайте аналитику. Наша платформа поможет вам организовать все и повысить вовлечённость участников.</p>
        <a class="start-btn" href="/">Начать сейчас</a>
    </div>
    <div class="support-block">
        <div class="support-title">При поддержке</div>
        <div class="support-desc">
            Проект создан при поддержке Федерального государственного бюджетного учреждения «Фонд содействия развитию малых форм предприятий в научно-технической сфере» в рамках программы «Студенческий стартап» федерального проекта «Платформа университетского технологического предпринимательства»
        </div>
        <div class="support-logos">
            <div class="support-logo-col">
                <div class="support-logo-img">
                    <img src="{{ asset('img/tech.svg') }}" alt="Платформа университетского технологического предпринимательства" style="max-width: 200px;">
                </div>
                <div class="support-logo-caption">Платформа университетского технологического предпринимательства</div>
            </div>
            <div class="support-logo-col">
                <div class="support-logo-img">
                    <img src="{{ asset('img/fond.png') }}" alt="Фонд содействия инновациям" style="max-width: 250px;">
                </div>
                <div class="support-logo-caption">Фонд содействия инновациям</div>
            </div>
        </div>
    </div>
    <div class="features-section">
        <div class="features-title">Функции</div>
        <div class="features-desc">
            Наша платформа предлагает удобное приложение, автоматическую обработку заявок, проверку отчетов, статистику конференции и безопасные каналы связи.
        </div>
        <div class="features-list">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="material-icons">manage_accounts</i>
                </div>
                <div class="feature-text">
                    <div class="feature-item-title">Управление заявками участников</div>
                    <div class="feature-item-desc">Простая система подачи заявок на участие и прослушивание конференций</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="material-icons">verified</i>
                </div>
                <div class="feature-text">
                    <div class="feature-item-title">Проверка загружаемых материалов</div>
                    <div class="feature-item-desc">Организация экспертной оценки и проверки докладов перед публикацией</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="material-icons">analytics</i>
                </div>
                <div class="feature-text">
                    <div class="feature-item-title">Аналитика и отчёты</div>
                    <div class="feature-item-desc">Подробные статистические отчёты по итогам проведённых конференций</div>
                </div>
            </div>
        </div>
    </div>
    <div class="pricing-section" id="pricing-section">
        <div class="pricing-title">Цены</div>
        <div class="pricing-desc">
            Выберите тарифный план с гибкими ценами в зависимости от участников и функций. Свяжитесь с нами для предложения.
        </div>
        <div class="pricing-cards">
            <div class="pricing-card">
                <div class="pricing-img">
                    <img src="{{ asset('img/plan1.webp') }}" alt="Базовый план" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                </div>
                <div class="pricing-plan-title">Базовый план</div>
                <div class="pricing-price">5000₽</div>
                <div class="pricing-plan-desc">Подходит для небольших конференций с ограниченным количеством участников и функций.</div>
            </div>
            <div class="pricing-card">
                <div class="pricing-img">
                    <img src="{{ asset('img/plan2.jpg') }}" alt="Расширенный план" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                </div>
                <div class="pricing-plan-title">Расширенный план</div>
                <div class="pricing-price">10000₽</div>
                <div class="pricing-plan-desc">Включает расширенный набор функций для средних конференций с большим количеством участников.</div>
            </div>
        </div>
    </div>
    <footer class="site-footer" id="contacts-footer">
        <div class="footer-company">© ДИДЖИТАЛ САИНС СОФТ, 2025</div>
        <!-- <div class="footer-content">
            По всем вопросам: <a href="mailto:misha2004@gmail.com">misha2004@gmail.com</a>
        </div> -->
    </footer>
    <style>
        .support-block {
            background: #eaf4fb;
            padding: 56px 10px 48px 10px;
            text-align: center;
            margin: 0;
        }
        .support-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 24px;
        }
        .support-desc {
            font-size: 1.1rem;
            color: #222;
            max-width: 700px;
            margin: 0 auto 40px auto;
            line-height: 1.5;
        }
        .support-logos {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 80px;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .support-logo-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 220px;
            max-width: 320px;
        }
        .support-logo-img {
            width: 280px;
            height: 160px;
            background: trnasparent;
            border-radius: 8px;
            /* box-shadow: 0 2px 8px #0001; */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #888;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .support-logo-caption {
            font-size: 1rem;
            color: #111;
            font-weight: 500;
            margin-bottom: 8px;
        }
        @media (max-width: 900px) {
            .support-logos { gap: 32px; }
        }
        @media (max-width: 700px) {
            .support-logos {
                flex-direction: column;
                align-items: center;
                gap: 32px;
            }
            .support-logo-col {
                min-width: 0;
                width: 100%;
                max-width: 400px;
            }
        }
        .features-section {
            background: #fff;
            padding: 80px 10px 80px 10px;
            text-align: center;
        }
        .features-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 24px;
        }
        .features-desc {
            font-size: 1.15rem;
            color: #222;
            max-width: 800px;
            margin: 0 auto 48px auto;
            line-height: 1.5;
        }
        .features-list {
            display: flex;
            flex-direction: column;
            gap: 44px;
            align-items: center;
            max-width: 700px;
            margin: 0 auto;
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 32px;
            width: 100%;
        }
        .feature-icon {
            width: 56px;
            height: 56px;
            background: #fff;
            border: 2px solid #222;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #222;
            font-weight: 600;
            flex-shrink: 0;
        }
        .feature-text {
            text-align: left;
        }
        .feature-item-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .feature-item-desc {
            font-size: 1rem;
            color: #222;
            line-height: 1.5;
        }
        @media (max-width: 700px) {
            .features-list { gap: 28px; }
            .feature-item { flex-direction: column; align-items: center; gap: 12px; }
            .feature-text { text-align: center; }
        }
        .pricing-section {
            background: #eaf4fb;
            padding: 80px 10px 80px 10px;
            text-align: center;
        }
        .pricing-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 24px;
        }
        .pricing-desc {
            font-size: 1.15rem;
            color: #222;
            max-width: 800px;
            margin: 0 auto 48px auto;
            line-height: 1.5;
        }
        .pricing-cards {
            display: flex;
            justify-content: center;
            gap: 48px;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .pricing-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px #0001;
            padding: 32px 32px 32px 32px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 320px;
            max-width: 380px;
            flex: 1 1 0;
        }
        .pricing-img {
            width: 100%;
            height: 280px;
            background: #ddd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #888;
            margin-bottom: 24px;
            font-weight: 600;
        }
        .pricing-plan-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .pricing-price {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .pricing-plan-desc {
            font-size: 1rem;
            color: #222;
            line-height: 1.5;
        }
        @media (max-width: 900px) {
            .pricing-cards { gap: 24px; }
            .pricing-card { padding: 24px 10px 24px 10px; }
        }
        @media (max-width: 700px) {
            .pricing-cards {
                flex-direction: column;
                align-items: center;
                gap: 32px;
            }
            .pricing-card {
                min-width: 0;
                width: 100%;
                max-width: 400px;
            }
        }
        .site-footer {
            background: #fff;
            border-top: 1px solid #e0e0e0;
            padding: 32px 10px 24px 10px;
            text-align: center;
            font-size: 1rem;
            color: #222;
            margin-top: 0;
        }
        .footer-company {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 8px;
            letter-spacing: 0.04em;
        }
        .site-footer a {
            color: #1976f8;
            text-decoration: none;
            font-weight: 600;
        }
        .site-footer a:hover {
            text-decoration: underline;
        }
    </style>
</body>
</html>