@extends('disp.layout')
@section('title', 'Водители - taxi.wazir.kg')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<style>
.main__subheader {
    display: none;
}

.main__order-map-settings-item .main__btn-short {
    font-size: 18px
}

.main__subheader-drivers {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
}

.main__subheader-drivers-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: unset;
    gap: 20px;
}

/* Стили для статусов */
.status-free {
    color: green;
    font-weight: bold;
}

.status-busy {
    color: red;
    font-weight: bold;
}

/* Активная и неактивная пагинация */
.main__table-pagination-item button {
    cursor: pointer;
}

.main__table-pagination-active button {
    color: white;
}

.main__table-pagination-item:not(.main__table-pagination-active) button:hover {
    background-color: #ddd;
}

.main__table-pagination-prev button,
.main__table-pagination-next button {
    cursor: pointer;
}

.disabled {
    opacity: 0.5;
    cursor: not-allowed !important;
}

.delete-driver {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-driver:hover {
    background-color: #ff1a1a;
}
</style>
@endpush

@section('content')
<!-- Статистика и фильтры -->
<div class="main__subheader-drivers">
    <div class="main__subheader-add">
        <div class="main__header-search-item">
            <form action="#" style="background-color: #47484c;" id="search-form">
                <input type="search" placeholder="Поиск" id="search-input">
                <button style="padding: 0px;" type="submit">
                    <img src="{{ asset('assets/img/disp/ico/search.png') }}" alt="search">
                </button>
            </form>
        </div>
        <div class="main__subheader-filing">
            <form action="#">
                <select name="status" id="status-filter">
                    <option value="" disabled selected>Статусы</option>
                    <option value="confirmed">Подтвержден</option>
                    <option value="unconfirmed">Не подтвержден</option>
                </select>
            </form>
        </div>
        <div class="main__subheader-filing">
            <form action="#">
                <select name="state" id="state-filter">
                    <option value="" disabled selected>Состояние</option>
                    <option value="busy">Занят</option>
                    <option value="free">Свободен</option>
                    <option value="offline">Офлайн</option>
                </select>
            </form>
        </div>
    </div>
    <div class="main__subheader-drivers-info">
        <div class="main__header-tags main__subheader-drivers-tags">
            <ul>
                <li>На линии <span id="online-drivers-count">0</span> водителей</li>
                <li><span class="status-span free"></span> <span id="free-drivers-count">0</span> свободный</li>
                <li><span class="status-span busy"></span> <span id="busy-drivers-count">0</span> занят</li>
            </ul>
        </div>
        <div class="main__subheader-balance">
            <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
            <p>Баланс: <span id="total-balance">0</span></p>
        </div>
    </div>
</div>

<!-- Таблица водителей -->
<div class="main__drivers-table">
    <div class="main__table main__paybalance-table">
        <table>
            <thead>
                <tr>
                    <th>Статусы</th>
                    <th>Состояние</th>
                    <th>Позывной</th>
                    <th>Ф.И.О</th>
                    <th>Телефон</th>
                    <th>Условия работы</th>
                    <th>Баланс</th>
                    <th>ВУ</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody id="drivers-table-body">
                <!-- Данные о водителях будут загружены через AJAX -->
            </tbody>
        </table>
        <div class="main__table-footer">
            <div class="main__table-driver">
                <button id="drivers-count">Водители: 0</button>
            </div>
            <div class="main__table-pagination" id="pagination-container">
                <!-- Пагинация для водителей будет динамически генерироваться -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script>
// Глобальные переменные для отслеживания состояния водителей
let currentPage = 1;
let totalPages = 1;
let currentFilters = {
    status: '',
    state: '',
    search: '',
    per_page: 10
};

// Функция для обновления счетчиков водителей
function updateCounters(data) {
    if (data.total !== undefined) {
        $('#drivers-count').text('Водители: ' + data.total);
    }

    // Обновляем счетчики онлайн, свободных и занятых водителей
    $.ajax({
        url: "{{ route('dispatcher.backend.drivers.list') }}",
        method: "GET",
        data: {
            count_only: true
        },
        success: function(response) {
            if (response.counts) {
                $('#online-drivers-count').text(response.counts.online || 0);
                $('#free-drivers-count').text(response.counts.free || 0);
                $('#busy-drivers-count').text(response.counts.busy || 0);
            }

            // Добавляем AJAX запрос для получения общего баланса
            $.ajax({
                url: "{{ route('dispatcher.backend.get_total_balance') }}",
                method: "GET",
                success: function(balanceResponse) {
                    if (balanceResponse.success) {
                        $('#total-balance').text(balanceResponse.total_balance
                            .toLocaleString());
                    }
                }
            });
        }
    });
}

// Функция для генерации пагинации
function generatePagination(currentPage, lastPage) {
    let paginationHtml = '';

    // Кнопка "Предыдущая"
    const prevDisabled = currentPage <= 1 ? 'disabled' : '';
    paginationHtml += `
        <div class="main__table-pagination-prev ${prevDisabled}">
            <button ${prevDisabled} data-page="${currentPage - 1}">
                <img src="{{ asset('assets/img/disp/ico/prev.png') }}" alt="prev">
            </button>
        </div>
    `;

    // Определяем диапазон страниц для отображения
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(lastPage, startPage + 4);

    if (endPage - startPage < 4 && lastPage > 4) {
        startPage = Math.max(1, endPage - 4);
    }

    // Генерируем кнопки страниц
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === currentPage ? 'main__table-pagination-active' : '';
        paginationHtml += `
            <div class="main__table-pagination-item ${activeClass}">
                <button data-page="${i}">${i}</button>
            </div>
        `;
    }

    // Кнопка "Следующая"
    const nextDisabled = currentPage >= lastPage ? 'disabled' : '';
    paginationHtml += `
        <div class="main__table-pagination-next ${nextDisabled}">
            <button ${nextDisabled} data-page="${currentPage + 1}">
                <img src="{{ asset('assets/img/disp/ico/next.png') }}" alt="next">
            </button>
        </div>
    `;

    $('#pagination-container').html(paginationHtml);

    // Добавляем обработчики событий для кнопок пагинации
    $('#pagination-container button:not([disabled])').click(function() {
        const page = $(this).data('page');
        if (page) {
            currentPage = page;
            updateDrivers();
        }
    });
}

// Функция для получения и отображения водителей
function updateDrivers() {
    // Объединяем текущие фильтры с номером страницы
    const params = {
        ...currentFilters,
        page: currentPage
    };

    // Отправляем запрос к API
    $.ajax({
        url: "{{ route('dispatcher.backend.drivers.list') }}",
        method: "GET",
        data: params,
        success: function(response) {
            updateCounters(response);
            renderDriversTable(response);

            // Обновляем состояние пагинации
            totalPages = response.last_page || 1;
            generatePagination(currentPage, totalPages);
        },
        error: function(error) {
            console.error("Ошибка при загрузке водителей:", error);
        }
    });
}

// Функция для отображения данных водителей в таблице
function renderDriversTable(response) {
    const drivers = response.data || [];
    let html = '';

    if (drivers.length === 0) {
        html = '<tr><td colspan="8" class="text-center">Нет данных для отображения</td></tr>';
    } else {
        drivers.forEach(driver => {
            const isConfirmed = driver.is_confirmed ?
                '<span class="badge confirmed">Подтвержден</span>' :
                '<span class="badge unconfirmed">Не подтвержден</span>';

            let statusClass = '';
            let statusText = 'Неизвестно';

            switch (driver.status) {
                case 'free':
                    statusClass = 'status-free';
                    statusText = 'Свободен';
                    break;
                case 'busy':
                    statusClass = 'status-busy';
                    statusText = 'Занят';
                    break;
                case 'offline':
                    statusClass = '';
                    statusText = 'Офлайн';
                    break;
                case 'online':
                    statusClass = 'status-free';
                    statusText = 'Онлайн';
                    break;
            }

            html += `
                <tr>
                    <td>${isConfirmed}</td>
                    <td class="${statusClass}">${statusText}</td>
                    <td>${driver.callsign || '-'}</td>
                    <td>${driver.full_name || '-'}</td>
                    <td>${driver.phone || '-'}</td>
                    <td>${driver.service_type || '-'}</td>
                    <td>${driver.balance ? driver.balance.toLocaleString() + ' ₸' : '0 ₸'}</td>
                    <td>${driver.license_number || '-'}</td>
                    <td>
                        <button class="delete-driver" data-driver-id="${driver.id}">Удалить</button>
                    </td>
                </tr>
            `;
        });
    }

    $('#drivers-table-body').html(html);
}

// Инициализация при загрузке страницы
$(document).ready(function() {
    // Загружаем список водителей
    updateDrivers();

    // Обработчик событий для формы поиска водителей
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        currentFilters.search = $('#search-input').val();
        currentPage = 1;
        updateDrivers();
    });

    // Обработчики изменения фильтров для водителей
    $('#status-filter').on('change', function() {
        currentFilters.status = $(this).val();
        currentPage = 1;
        updateDrivers();
    });

    $('#state-filter').on('change', function() {
        currentFilters.state = $(this).val();
        currentPage = 1;
        updateDrivers();
    });

    // Автоматическое обновление данных каждые 30 секунд
    setInterval(function() {
        updateDrivers();
    }, 30000);

    // Обработчик удаления водителя
    $(document).on('click', '.delete-driver', function() {
        const driverId = $(this).data('driver-id');
        if (confirm('Вы уверены, что хотите удалить этого водителя?')) {
            $.ajax({
                url: `{{ route('dispatcher.backend.drivers.delete', ['driver' => ':driverId']) }}`
                    .replace(':driverId', driverId),
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('Водитель успешно удален');
                        updateDrivers();
                    } else {
                        alert(response.message || 'Произошла ошибка при удалении водителя');
                    }
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                        xhr.responseJSON.message :
                        'Произошла ошибка при удалении водителя';
                    alert(errorMessage);
                }
            });
        }
    });
});
</script>
@endpush