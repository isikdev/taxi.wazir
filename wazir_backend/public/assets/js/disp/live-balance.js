/**
 * Скрипт для обновления баланса в реальном времени
 */
$(document).ready(function () {
    // Инициализация обновления баланса
    initBalanceUpdates();

    /**
     * Инициализация обновления баланса
     */
    function initBalanceUpdates() {
        // Получение и установка баланса из локального хранилища при загрузке
        let cachedBalance = localStorage.getItem('total_balance');
        let balanceElement = $('.main__subheader-balance p');

        if (cachedBalance && balanceElement.length) {
            updateBalanceDisplay(cachedBalance);
        }

        // Обновление баланса каждые 30 секунд
        setInterval(fetchTotalBalance, 30000);

        // Первичное получение баланса после загрузки страницы
        setTimeout(fetchTotalBalance, 1000);
    }

    /**
     * Получение общего баланса с сервера
     */
    function fetchTotalBalance() {
        $.ajax({
            url: '/backend/disp/get_total_balance',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success && response.total_balance) {
                    // Сохранение баланса в локальное хранилище
                    localStorage.setItem('total_balance', response.total_balance);

                    // Обновление отображения баланса
                    updateBalanceDisplay(response.total_balance);
                }
            },
            error: function (xhr) {
                console.error('Ошибка при получении баланса:', xhr.responseText);
            }
        });
    }

    /**
     * Обновление отображения баланса
     * @param {number} balance - Значение баланса
     */
    function updateBalanceDisplay(balance) {
        const balanceElement = $('.main__subheader-balance p');
        if (balanceElement.length) {
            balanceElement.text('Баланс: ' +
                new Intl.NumberFormat('ru-RU', { maximumFractionDigits: 0 }).format(balance));
        }
    }
}); 