<!-- resources/views/disp/layout.blade.php -->
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ош Титан Парк')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
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
                        <a class="navbar__links-content-active" href="#">
                            <img src="{{ asset('assets/img/disp/ico/disp.png') }}" alt="disp">
                        </a>
                        <a href="#">
                            <img src="{{ asset('assets/img/disp/ico/maps.png') }}" alt="maps">
                        </a>
                        <a href="{{ route('dispatcher.backend.drivers') }}">
                            <img src="{{ asset('assets/img/disp/ico/user.png') }}" alt="user">
                        </a>
                        <a href="{{ route('dispatcher.backend.cars') }}">
                            <img src="{{ asset('assets/img/disp/ico/car.png') }}" alt="car">
                        </a>
                        <a href="{{ route('dispatcher.backend.analytics') }}">
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
                            <a href="#">
                                <img src="{{ asset('assets/img/disp/ico/notif.png') }}" alt="notif">
                            </a>
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
                    <a href="#"><img src="{{ asset('assets/img/disp/ico/earth.png') }}" alt="earth"></a>
                </div>
                <div class="main__subheader-filing">
                    <form action="#">
                        <select name="filing-date">
                            <option value="Дата подачи" disabled selected>Дата подачи</option>
                            <option value="08.12.2024">08.12.2024</option>
                            <option value="09.01.2025">09.01.2025</option>
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
                    <p>Баланс: 10,000</p>
                </div>
            </div>

            {{-- Здесь будет уникальное содержимое каждой страницы --}}
            @yield('content')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/disp/script.js') }}"></script>
    @stack('scripts')
</body>

</html>