/**
 * Общие функции для работы с фильтрами на всех страницах
 */
$(document).ready(function () {
    // Обработка выбора даты
    $('select[name="filing-date"]').change(function () {
        let value = $(this).val();
        let firstDateInput = $('#first-date-form');
        let lastDateInput = $('#last-date-form');

        // Сброс дат
        firstDateInput.val('');
        lastDateInput.val('');

        // Если выбрано "Указать период", активируем поля выбора даты
        if (value === 'custom') {
            firstDateInput.prop('disabled', false);
            lastDateInput.prop('disabled', false);

            // Изменяем внешний вид
            $('.main__subheader-date').css('opacity', '1');
        } else {
            firstDateInput.prop('disabled', true);
            lastDateInput.prop('disabled', true);

            // Изменяем внешний вид
            if (value === '') {
                $('.main__subheader-date').css('opacity', '0.5');
            } else {
                $('.main__subheader-date').css('opacity', '0.8');
            }

            // Устанавливаем даты в зависимости от выбранного периода
            if (value !== '') {
                let today = new Date();
                let startDate = new Date();
                let endDate = new Date();

                if (value === 'today') {
                    // Текущий день
                    startDate = today;
                    endDate = today;
                } else if (value === 'yesterday') {
                    // Вчерашний день
                    startDate.setDate(today.getDate() - 1);
                    endDate = new Date(startDate);
                } else if (value === 'week') {
                    // Начало недели (понедельник)
                    let day = today.getDay();
                    let diff = today.getDate() - day + (day === 0 ? -6 : 1); // корректировка для воскресенья
                    startDate = new Date(today.setDate(diff));
                    endDate = new Date(); // сегодня
                } else if (value === 'month') {
                    // Начало месяца
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = new Date(); // сегодня
                }

                // Форматирование дат для inputs
                firstDateInput.val(formatDate(startDate));
                lastDateInput.val(formatDate(endDate));
            }
        }

        // Запускаем событие change для активации фильтров
        if (typeof window.applyFilters === 'function') {
            window.applyFilters();
        }
    });

    // Переключение кнопок статуса
    $('.main__subheader-add button:not(:first-child)').click(function () {
        // Если кнопка уже активна, снимаем выделение
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            // Если кнопка не активна, снимаем выделение со всех и делаем активной текущую
            $('.main__subheader-add button:not(:first-child)').removeClass('active');
            $(this).addClass('active');
        }

        // Запускаем событие применения фильтров, если функция определена
        if (typeof window.applyFilters === 'function') {
            window.applyFilters();
        }
    });

    // Применение фильтров при изменении дат
    $('#first-date-form, #last-date-form').change(function () {
        // Проверяем, если оба поля даты заполнены
        if ($('#first-date-form').val() && $('#last-date-form').val()) {
            // Запускаем событие применения фильтров, если функция определена
            if (typeof window.applyFilters === 'function') {
                window.applyFilters();
            }
        }
    });

    // Функция форматирования даты в YYYY-MM-DD
    function formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    // Добавляем стили для активных кнопок статуса
    $('<style>')
        .text(`
            .main__subheader-add button.active {
                background-color: #3498db;
                color: white;
                box-shadow: 0 0 10px rgba(52, 152, 219, 0.7);
                transform: translateY(-2px);
            }
            .main__subheader-add button.active span {
                background-color: white;
            }
            .main__subheader-date input {
                transition: opacity 0.3s ease;
            }
        `)
        .appendTo('head');

    // Инициализация: по умолчанию кнопки дисейблим
    $('.main__subheader-date').css('opacity', '0.5');
});

// Функция получения объекта с параметрами фильтров
window.getFilterParams = function () {
    let datePreset = $('select[name="filing-date"]').val();
    let startDate = $('#first-date-form').val();
    let endDate = $('#last-date-form').val();

    // Определяем статус по выбранной кнопке
    let status = 'all'; // По умолчанию "все"
    let activeButton = $('.main__subheader-add button.active');

    if (activeButton.length) {
        const buttonText = activeButton.text().trim();
        if (buttonText === 'Свободные') {
            status = 'free';
        } else if (buttonText === 'Занятые') {
            status = 'busy';
        } else if (buttonText === 'Отмененные') {
            status = 'cancelled';
        }
    }

    // Формируем данные для запроса
    return {
        date_preset: datePreset || '',
        start_date: startDate || '',
        end_date: endDate || '',
        status: status
    };
}; 