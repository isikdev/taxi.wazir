<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статус заявки | Wazir.kg</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <style>
    .status-container {
        padding: 30px 0;
        text-align: center;
    }

    .status-box {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        max-width: 600px;
        margin: 0 auto 30px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .status-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .status-icon.waiting {
        color: #FFC107;
    }

    .status-icon.rejected {
        color: #F44336;
    }

    .status-icon.approved {
        color: #4CAF50;
    }

    .status-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }

    .status-description {
        color: #666;
        font-size: 16px;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .application-details {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 20px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        color: #888;
        font-weight: 500;
    }

    .detail-value {
        flex: 1;
        color: #333;
    }

    .rejection-reason {
        background: #FFF8F8;
        border: 1px solid #FFCCCC;
        border-radius: 8px;
        padding: 15px;
        margin: 20px 0;
        text-align: left;
        color: #D32F2F;
    }

    .refresh-button {
        display: inline-block;
        padding: 10px 20px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 6px;
        color: #666;
        font-size: 14px;
        text-decoration: none;
        margin-top: 20px;
        transition: all 0.2s ease;
    }

    .refresh-button:hover {
        background: #e0e0e0;
    }

    .buttons-container {
        margin-top: 25px;
    }

    .status-update {
        margin-top: 30px;
        color: #666;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .status-box {
            padding: 20px;
            margin: 0 15px 20px;
        }

        .detail-row {
            flex-direction: column;
        }

        .detail-label {
            margin-bottom: 5px;
        }
    }
    </style>
</head>

<body>
    <header>
        <div class="brand">
            <div class="container">
                <div class="brand__content">
                    <div class="logo">
                        <img src="{{ asset('assets/img/driver/logo.png') }}" alt="logo">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section>
            <div class="container">
                <div class="status-container">
                    @if($driver->survey_status == 'submitted')
                    <!-- СТАТУС: В ОЖИДАНИИ -->
                    <div class="status-box">
                        <div class="status-icon waiting">⏳</div>
                        <h1 class="status-title">Анкета на рассмотрении</h1>
                        <p class="status-description">
                            Ваша анкета успешно отправлена и находится на рассмотрении.
                            Наши специалисты проверят предоставленные данные в ближайшее время.
                        </p>

                        <div class="application-details">
                            <div class="detail-row">
                                <span class="detail-label">Номер заявки:</span>
                                <span class="detail-value">WZ-{{ $driver->id }}-{{ date('Ymd') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Дата отправки:</span>
                                <span class="detail-value">{{ $driver->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Статус:</span>
                                <span class="detail-value">На рассмотрении</span>
                            </div>
                        </div>

                        <p class="status-update">Последнее обновление: {{ now()->format('d.m.Y H:i') }}</p>

                        <div class="buttons-container">
                            <a href="{{ route('driver.survey.applicationStatus') }}" class="main__btn-active">Обновить
                                статус</a>
                        </div>
                    </div>

                    @elseif($driver->survey_status == 'rejected')
                    <!-- СТАТУС: ОТКАЗАНО -->
                    <div class="status-box">
                        <div class="status-icon rejected">✕</div>
                        <h1 class="status-title">Заявка отклонена</h1>
                        <p class="status-description">
                            К сожалению, ваша анкета была отклонена.
                            Пожалуйста, ознакомьтесь с причиной отказа и свяжитесь с нами для уточнения деталей.
                        </p>

                        <div class="application-details">
                            <div class="detail-row">
                                <span class="detail-label">Номер заявки:</span>
                                <span class="detail-value">WZ-{{ $driver->id }}-{{ date('Ymd') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Дата отправки:</span>
                                <span class="detail-value">{{ $driver->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Статус:</span>
                                <span class="detail-value">Отклонена</span>
                            </div>
                        </div>

                        <div class="rejection-reason">
                            <strong>Причина отказа:</strong><br>
                            {{ $driver->rejection_reason ?? 'Не соответствие требованиям таксопарка. Обратитесь в службу поддержки для получения дополнительной информации.' }}
                        </div>

                        <p class="status-update">Последнее обновление: {{ now()->format('d.m.Y H:i') }}</p>

                        <div class="buttons-container">
                            <a href="{{ route('driver.auth.step1') }}" class="main__btn-active">Новая заявка</a>
                        </div>
                    </div>

                    @elseif($driver->survey_status == 'approved')
                    <!-- СТАТУС: РАЗРЕШЕНО -->
                    <div class="status-box">
                        <div class="status-icon approved">✓</div>
                        <h1 class="status-title">Заявка одобрена</h1>
                        <p class="status-description">
                            Поздравляем! Ваша анкета была успешно одобрена.
                            Теперь вы можете начать работу с таксопарком.
                        </p>

                        <div class="application-details">
                            <div class="detail-row">
                                <span class="detail-label">Номер заявки:</span>
                                <span class="detail-value">WZ-{{ $driver->id }}-{{ date('Ymd') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Дата отправки:</span>
                                <span class="detail-value">{{ $driver->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Дата одобрения:</span>
                                <span
                                    class="detail-value">{{ $driver->approved_at ? date('d.m.Y H:i', strtotime($driver->approved_at)) : now()->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Статус:</span>
                                <span class="detail-value">Одобрена</span>
                            </div>
                        </div>

                        <p class="status-update">Последнее обновление: {{ now()->format('d.m.Y H:i') }}</p>

                        <div class="buttons-container">
                            <a href="{{ route('driver.profile') }}" class="main__btn-active">Перейти в профиль</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <script>
    // Автоматическое обновление страницы каждые 5 минут
    setTimeout(function() {
        window.location.reload();
    }, 5 * 60 * 1000);
    </script>
</body>

</html>