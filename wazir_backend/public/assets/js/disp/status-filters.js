/**
 * Общие функции для работы с фильтрами статуса "Активные", "Заблокированные", "В ожидании", "Отмененные"
 */
$(document).ready(function () {
    // Инициализация кнопок фильтров
    initStatusFilters();

    /**
     * Инициализация кнопок фильтрации статусов
     */
    function initStatusFilters() {
        // Кнопки фильтрации
        const filterButtons = $('.main__subheader-add button');

        if (filterButtons.length === 0) return;

        // Активируем первую кнопку (Активные) по умолчанию
        $(filterButtons[0]).addClass('active');

        // Применяем фильтр "Активные" по умолчанию
        applyStatusFilter('active');

        // Обработчик нажатия на кнопки фильтрации
        filterButtons.click(function () {
            const index = filterButtons.index(this);

            // Установка активного состояния для нажатой кнопки
            filterButtons.removeClass('active');
            $(this).addClass('active');

            // Применяем фильтр в зависимости от нажатой кнопки
            switch (index) {
                case 0: // Активные
                    applyStatusFilter('active');
                    break;
                case 1: // Заблокированные
                    applyStatusFilter('blocked');
                    break;
                case 2: // В ожидании
                    applyStatusFilter('pending');
                    break;
                case 3: // Отмененные
                    applyStatusFilter('cancelled');
                    break;
            }
        });
    }

    /**
     * Применение фильтра к таблице 
     * @param {string} filterType - Тип фильтра (active, blocked, pending, cancelled)
     */
    function applyStatusFilter(filterType) {
        const rows = $('tbody tr');

        rows.show(); // Сначала показываем все строки

        switch (filterType) {
            case 'active':
                // Показываем только активных (те, у которых статус не "Ошибка" и кто подтвержден)
                rows.each(function () {
                    const statusText = $(this).find('td:last').text().trim();
                    const isConfirmed = !$(this).find('td:eq(1)').text().includes('Новый пользователь');

                    if (statusText === 'Ошибка' || statusText === 'Отменен' || statusText === 'Заблокирован' || !isConfirmed) {
                        $(this).hide();
                    }
                });
                break;

            case 'blocked':
                // Показываем только заблокированных 
                rows.each(function () {
                    const statusText = $(this).find('td:last').text().trim();

                    if (statusText !== 'Заблокирован') {
                        $(this).hide();
                    }
                });
                break;

            case 'pending':
                // Показываем только в ожидании подтверждения
                rows.each(function () {
                    const statusText = $(this).find('td:last').text().trim();

                    if (!statusText.includes('В ожидании подтверждения')) {
                        $(this).hide();
                    }
                });
                break;

            case 'cancelled':
                // Показываем только отмененных/с ошибкой
                rows.each(function () {
                    const statusText = $(this).find('td:last').text().trim();

                    if (statusText !== 'Ошибка' && statusText !== 'Отменен') {
                        $(this).hide();
                    }
                });
                break;
        }

        // Обновляем счетчик водителей
        updateDriverCount();
    }

    /**
     * Обновление счетчика водителей
     */
    function updateDriverCount() {
        const visibleRows = $('tbody tr:visible').length;
        $('.main__table-driver button').text('Водители: ' + visibleRows);
    }

    // Добавляем стили для активных кнопок статуса
    $('<style>')
        .text(`
            .main__subheader-add button {
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
            }
            .main__subheader-add button.active {
                background-color: #3498db;
                color: white;
                box-shadow: 0 0 10px rgba(52, 152, 219, 0.7);
                transform: translateY(-2px);
            }
            .main__subheader-add button.active span {
                background-color: white;
            }
        `)
        .appendTo('head');
}); 