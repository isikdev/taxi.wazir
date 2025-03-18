<!-- resources/views/disp/layout.blade.php -->
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ош Титан Парк')</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/disp/notifications.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <div class="navbar__settings navbar__menu">
            <div class="navbar__menu-links">
                <h4>Информация</h4>
                <nav>
                    <ul>
                        <li><a href="#">Документы</a></li>
                    </ul>
                </nav>
            </div>
            <div class="navbar__menu-links">
                <h4>Техподдержка</h4>
                <nav>
                    <ul>
                        <li><a href="#">Техподдержка</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="navbar__menu-info navbar__menu">
            <div class="navbar__menu-links">
                <h4>Диспетчерская</h4>
                <nav>
                    <ul>
                        <li><a href="{{ route('dispatcher.backend.analytics') }}">Статистика</a></li>
                        <li><a href="{{ route('dispatcher.backend.get_balance') }}">Запрос на баланс</a></li>
                        <li><a href="{{ route('dispatcher.backend.new_order') }}">Новый заказ</a></li>
                        <li><a href="{{ route('dispatcher.backend.maps') }}">Карта</a></li>
                    </ul>
                </nav>
            </div>
            <div class="navbar__menu-links">
                <h4>Автопарк</h4>
                <nav>
                    <ul>
                        <li><a href="{{ route('dispatcher.backend.drivers_control_edit') }}">Создание водителя</a></li>
                        <li><a href="{{ route('dispatcher.backend.pay_balance') }}">Пополнение баланса</a></li>
                        <li><a href="{{ route('dispatcher.backend.drivers') }}">Водители</a></li>
                        <li><a href="{{ route('dispatcher.backend.cars') }}">Автомобили</a></li>
                        <li><a href="{{ route('dispatcher.backend.chat') }}">Чат</a></li>
                    </ul>
                </nav>
            </div>
            <div class="navbar__menu-links">
                <h4>Контроль</h4>
                <nav>
                    <ul>
                        <li><a href="{{ route('dispatcher.backend.drivers_control') }}">Фото контроль</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="navbar__menu-settings navbar__menu">
            <div class="navbar__menu-links">
                <h4>Основные</h4>
                <nav>
                    <ul>
                        <li><a href="#">Профиль партнера</a></li>
                        <li><a href="#">Категория транзакций</a></li>
                    </ul>
                </nav>
            </div>
            <div class="navbar__menu-links" style="position: absolute; bottom: 10%;">
                <h4>Интерфейс</h4>
                <div class="main__subheader-filing">
                    <h4>Язык Интерфейса</h4>
                    <form action="#">
                        <select name="filing-date" style="width: 100%;">
                            <option value="Русский">Русский</option>
                            <option value="Киргизский">Киргизский</option>
                        </select>
                    </form>
                </div>
                <div class="main__subheader-filing">
                    <h4>Тема</h4>
                    <form action="#">
                        <select name="filing-date" style="width: 100%;">
                            <option value="Ночная Тема">Ночная Тема</option>
                            <option value="Светлая Тема">Светлая Тема</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="navbar">
            <div class="navbar__content">
                <div class="navbar__logo">
                    <a href="{{ route('dispatcher.backend.index') }}">
                        <img src="{{ asset('assets/img/disp/logo/logo_small.png') }}" alt="logo">
                    </a>
                </div>
                <div class="navbar__links">
                    <div class="navbar__links-content">
                        <a class="{{ request()->routeIs('dispatcher.backend.index') || request()->routeIs('dispatcher.index') ? 'navbar__links-content-active' : '' }}"
                            href="{{ route('dispatcher.backend.index') }}">
                            <img src="{{ asset('assets/img/disp/ico/disp.png') }}" alt="disp">
                        </a>
                        <a class="{{ request()->routeIs('dispatcher.backend.drivers') ? 'navbar__links-content-active' : '' }}"
                            href="{{ route('dispatcher.backend.drivers') }}">
                            <img src="{{ asset('assets/img/disp/ico/user.png') }}" alt="user">
                        </a>
                        <a class="{{ request()->routeIs('dispatcher.backend.cars') ? 'navbar__links-content-active' : '' }}"
                            href="{{ route('dispatcher.backend.cars') }}">
                            <img src="{{ asset('assets/img/disp/ico/car.png') }}" alt="car">
                        </a>
                        <a class="{{ request()->routeIs('dispatcher.backend.maps') ? 'navbar__links-content-active' : '' }}"
                            href="{{ route('dispatcher.backend.maps') }}">
                            <img src="{{ asset('assets/img/disp/ico/earth.png') }}" alt="map">
                        </a>
                        <a class="{{ request()->routeIs('dispatcher.backend.analytics') ? 'navbar__links-content-active' : '' }}"
                            href="{{ route('dispatcher.backend.analytics') }}">
                            <img src="{{ asset('assets/img/disp/ico/analytics.png') }}" alt="analytics">
                        </a>
                    </div>
                </div>
                <div class="navbar__links">
                    <div class="navbar__links-content">
                        <a href="#" class="support">
                            <img src="{{ asset('assets/img/disp/ico/info.png') }}" alt="info">
                        </a>
                        <a href="#" class="settings">
                            <img src="{{ asset('assets/img/disp/ico/settings.png') }}" alt="settings">
                        </a>
                        <a href="#" class="menu">
                            <img src="{{ asset('assets/img/disp/ico/menu.png') }}" alt="menu">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main">
            <div class="main__header">
                <div class="main__header-logo">
                    <h3>Ош Титан Парк</h3>
                </div>
                <div class="main__header-tags">
                    <ul>
                        <li><a href="{{ route('dispatcher.backend.drivers') }}">Водитель</a></li>
                        <li><a href="{{ route('dispatcher.backend.cars') }}">Автомобиль</a></li>
                        <li><a href="{{ route('dispatcher.backend.drivers_control') }}">Фото контроль</a></li>
                        <li>Тариф</li>
                        <li><a href="{{ route('dispatcher.backend.chat') }}">Чат</a></li>
                    </ul>
                </div>
                <div class="main__header-search">
                    <div class="main__header-search-item">
                        <form action="#">
                            <input type="search" placeholder="Поиск">
                            <button>
                                <img src="{{ asset('assets/img/disp/ico/search.png') }}" alt="search">
                            </button>
                        </form>
                    </div>
                    <div class="main__header-search-profile">
                        <div class="main__header-search-profile-item">
                            <div class="notifications-container">
                                <a href="#" class="notifications-icon" id="notifications-icon">
                                    <img src="{{ asset('assets/img/disp/ico/notif.png') }}" alt="notif">
                                    <span class="notifications-badge" id="notifications-badge">0</span>
                                </a>
                                <div class="notifications-dropdown" id="notifications-dropdown">
                                    <div class="notifications-header">
                                        <h3 class="notifications-title">Уведомления</h3>
                                        <button class="mark-all-read" id="mark-all-read">Отметить все как
                                            прочитанные</button>
                                    </div>
                                    <div id="notifications-list">
                                        <div class="notification-empty">У вас нет новых уведомлений</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="main__header-search-profile-item">
                            <a href="#">
                                <img src="{{ asset('assets/img/disp/ico/user.png') }}" alt="profile">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main__subheader">
                <div class="main__subheader-filter">
                    <a href="#"><img src="{{ asset('assets/img/disp/ico/burger.png') }}" alt="burger"></a>
                    <a href="#"><img src="{{ asset('assets/img/disp/ico/chart.png') }}" alt="chart"></a>
                </div>
                <div class="main__subheader-filing">
                    <form action="#">
                        <select name="filing-date">
                            <option value="" disabled selected>Дата подачи</option>
                            <option value="today">Сегодня</option>
                            <option value="yesterday">Вчера</option>
                            <option value="week">Текущая неделя</option>
                            <option value="month">Текущий месяц</option>
                            <option value="custom">Указать период</option>
                        </select>
                    </form>
                </div>
                <div class="main__subheader-date">
                    <form action="#">
                        <input type="date" name="first-date-form" id="first-date-form" disabled>
                        <p>-</p>
                        <input type="date" name="last-date-form" id="last-date-form" disabled>
                    </form>
                </div>
                <div class="main__subheader-add">
                    <button><a href="{{ route('dispatcher.backend.new_order') }}">+ Новый (F2)</a></button>
                    <button><span class="free"></span>Свободные</button>
                    <button><span class="busy"></span>Занятые</button>
                    <button><span class="cancelled"></span>Отмененные</button>
                </div>
                <div class="main__subheader-balance">
                    <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
                    <p>Баланс: {{ number_format($totalBalance ?? 0, 0, '.', ',') }}</p>
                </div>
            </div>

            {{-- Здесь будет уникальное содержимое каждой страницы --}}
            @yield('content')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/disp/script.js') }}"></script>
    <script src="{{ asset('assets/js/disp/filters.js') }}"></script>
    <script src="{{ asset('assets/js/disp/status-filters.js') }}"></script>
    <script src="{{ asset('assets/js/disp/live-balance.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('assets/js/disp/notifications.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script>
    // Предварительное кэширование баланса на клиенте для мгновенной загрузки
    document.addEventListener('DOMContentLoaded', function() {
        let cachedBalance = localStorage.getItem('total_balance');
        let balanceElement = document.querySelector('.main__subheader-balance p');

        // Используем кэшированное значение, если оно есть
        if (cachedBalance && balanceElement) {
            balanceElement.textContent = 'Баланс: ' + new Intl.NumberFormat('ru-RU').format(parseFloat(
                cachedBalance));
        }

        // Асинхронно обновляем кэш баланса через 1 секунду после загрузки страницы
        setTimeout(function() {
            fetch('{{ route("dispatcher.backend.get_total_balance") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.total_balance) {
                        localStorage.setItem('total_balance', data.total_balance);
                        if (balanceElement) {
                            balanceElement.textContent = 'Баланс: ' + new Intl.NumberFormat('ru-RU')
                                .format(data.total_balance);
                        }
                    }
                })
                .catch(error => console.error('Ошибка при загрузке баланса:', error));
        }, 1000);

        // Добавляем кнопку для тестирования уведомлений
        const testButton = document.createElement('button');
        testButton.textContent = 'Тест уведомления';
        testButton.style.position = 'fixed';
        testButton.style.bottom = '20px';
        testButton.style.right = '20px';
        testButton.style.padding = '10px 20px';
        testButton.style.backgroundColor = '#4CAF50';
        testButton.style.color = 'white';
        testButton.style.border = 'none';
        testButton.style.borderRadius = '5px';
        testButton.style.cursor = 'pointer';
        testButton.style.zIndex = '9999';
        testButton.onclick = function() {
            if (window.testNotification) {
                window.testNotification();
            } else {
                alert('Функция тестирования уведомлений не найдена!');
            }
        };
        document.body.appendChild(testButton);

        // Проверяем подключение к Pusher через консоль
        console.log('Pusher доступен:', typeof Pusher !== 'undefined');

        // Diagnostic WebSocket check
        if (typeof Pusher !== 'undefined') {
            const diagnosticPusher = new Pusher('4ts3aIX8F1orXMIidsijQtTiYR9u262FNLOMoma_JE8', {
                cluster: 'eu',
                forceTLS: true,
                enabledTransports: ['ws', 'wss']
            });

            diagnosticPusher.connection.bind('connected', function() {
                console.log('%c WebSocket подключение успешно установлено!',
                    'background: #4CAF50; color: white; padding: 5px;');
            });

            diagnosticPusher.connection.bind('error', function(err) {
                console.error('%c Ошибка WebSocket подключения:',
                    'background: #F44336; color: white; padding: 5px;', err);
            });
        }
    });
    </script>
    <script>
    // Настройки Pusher в браузере
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing Pusher with your keys...');

        // Для отладки
        Pusher.logToConsole = true;

        const pusher = new Pusher('f63d33c94e33b1f539e8', {
            cluster: 'ap2'
        });

        const channel = pusher.subscribe('my-channel');

        channel.bind('my-event', function(data) {
            console.log('Received event:', data);
            if (data && data.notification) {
                if (window.playNotificationSound) {
                    window.playNotificationSound();
                }

                if (window.showToast) {
                    window.showToast(data.notification);
                }

                if (window.addNotificationToList) {
                    window.addNotificationToList(data.notification);
                }

                if (window.updateNotificationCount) {
                    window.updateNotificationCount();
                }
            }
        });
    });
    </script>
    <script>
    // Проверка загрузки библиотек
    document.addEventListener('DOMContentLoaded', function() {
        // Проверяем Toastify
        if (typeof Toastify === 'undefined') {
            console.error('ОШИБКА: Библиотека Toastify не загружена!');
        } else {
            console.log('Toastify успешно загружен');
        }

        // Проверяем Pusher
        if (typeof Pusher === 'undefined') {
            console.error('ОШИБКА: Библиотека Pusher не загружена!');
        } else {
            console.log('Pusher успешно загружен');

            // Проверяем подключение через тестовое уведомление
            setTimeout(function() {
                console.log('Отправляем тестовое уведомление через Pusher...');
                const testEvent = {
                    notification: {
                        id: Date.now(),
                        type: 'balance',
                        title: 'Тестовое уведомление',
                        data: {
                            message: 'Это тестовое уведомление для проверки Pusher.'
                        },
                        created_at: new Date().toISOString()
                    }
                };

                if (window.showToast) {
                    window.showToast(testEvent.notification);
                } else {
                    console.error('Функция showToast не найдена!');
                }
            }, 3000); // Через 3 секунды после загрузки
        }
    });
    </script>
    @stack('scripts')
</body>

</html>